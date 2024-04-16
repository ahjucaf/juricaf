#!/bin/perl

%data = ();
$is_arret = 0;
$has_ref = 0;
while(<STDIN>) {
    chomp;
    if (/<meta itemprop="([^"]+)" content="([^"]+)"/) {
        $data{"meta_$1"} = $2;
    } elsif (/infoValue/) {
        /data-l10n="([^"_]+)["_]/ ;
        $type = $1;
        s/<[^>]*>//g;
        $value = $_;
        if (!$type) {
            $type = 'date';
            if ( $value =~ /^\d+$/ ) {
                $type = 'id';
            }
        }
        $data{$type} = $value;
    } elsif (/<h1[^>]*>(.*)<\/h1/i) {
        $titre = $1;
        $titre =~ s/<[^>]*>//g;
        $data{'titre'} = $titre;
    } elsif ($is_arret) {
        $arret .= $_;
        if (/div id="extras"/) {
            $is_arret = 0;
        }
        $arret =~ s/<\/?(hr|div|section|nav|a|span|button|header)[^>]*>//ig;
        $arret =~ s/🔗Copier le texte de ce bloc//g;
    } elsif (!$is_arret && /title_text/) {
        s/<[^>]*>//g;
        s/🔗.*//;
        $section_type = $_;
        $section_co = '';
    } elsif (!$is_arret && $section_type && /section_co/) {
        $section_co = $_;
    } elsif (/casePart/) {
        $is_arret = 1;
    } elsif (/<hr/i) {
        $is_arret = 1;
        $arret = $_;
        $section_type = $section_co = ''
    } elsif (/docListBtn/) {
        /data-l10n="([^"]*)"/;
        $reftype = $1;
    } elsif (/referencedDocuments/ && /<li>/) {
        foreach $li (split '</li><li>') {
            $li =~ s/<[^>]*>//g;
            $refid++;
            $data{"reftype_".$reftype."_".$refid} = $li;
            $has_ref = 1;
        }
    }
        if ($section_type && $section_co) {
            $section_co .= $_;
            if (/<\/div/) {
                $section_co =~ s/<div[^>]*>//g;
                $section_co =~ s/<\/div>.*//g;
                $data{$section_type} = $section_co;
                $section_type = $section_co = ''
            }
        }
}
$arret =~ s/([0-9]-)[\s ]([0-9])/$1$2/g;
if ($arret =~ />(TS[^<]*[0-9])[\s ]*</ ) {
    $data{'meta_number'} = $1;
}elsif ($arret =~ /sous le (numéro|n°)[ \s]*(TS[ \s]*[0-9][^, \s]*[0-9])[, \s;]/i) {
    $data{'meta_number'} = $2;
}
if ($data{'meta_number'} =~ /TS/) {
    $data{'meta_number'} =~ s/[\s ]+//g;
    $data{'meta_number'} =~ s/TS/TS\//g;
    $data{'meta_number'} =~ s/,$//;
    $data{'titre'} .= ', '.$data{'meta_number'}
}

open(FH, '>/tmp/monaco.html');
print FH "$arret";
close FH;
open(LYNX, 'links -dump /tmp/monaco.html |');
@arret = <LYNX>;
$arret = "@arret";
close LYNX;

$arret =~ s/\n *\n/ø/g;
$arret =~ s/\n//g;
$arret =~ s/ø/\n\n/g;
$arret =~ s/  */ /g;
$arret =~ s/\n */\n/g;
$arret =~ s/&/&amp;/g;
$arret =~ s/</&lt;/g;
$arret =~ s/>/&gt;/g;

$data{'Résumé'} =~ s/<[^>]*>//g;
$data{'Abstract'} =~ s/<[^>]*>//g;

print '<?xml version="1.0" encoding="utf8"?>'."\n";
print "<DOCUMENT>\n";
print "<DATE_ARRET>".$data{'meta_date'}."</DATE_ARRET>\n";
print "<JURIDICTION>".$data{"juridiction"}."</JURIDICTION>\n";
print "<FONDS_DOCUMENTAIRE>tribunal-supreme.mc</FONDS_DOCUMENTAIRE>\n";
print "<NUM_ARRET>".$data{'meta_number'}."</NUM_ARRET>\n";
print "<PAYS>Monaco</PAYS>\n";
print "<ANALYSES>\n";
if ($data{'Résumé'}) {
    print "<ANALYSE><SOMMAIRE>".$data{'Résumé'}."</SOMMAIRE></ANALYSE>\n";
}
print "<ANALYSE><TITRE_PRINCIPAL>".$data{'thematic'}."</TITRE_PRINCIPAL></ANALYSE>\n";
if ($data{'Abstract'}) {
    $data{'Abstract'} =~ s/ ; / - /g;
    print "<ANALYSE><TITRE_SECONDAIRE>".$data{'Abstract'}."</TITRE_SECONDAIRE></ANALYSE>\n";
}
print "</ANALYSES>\n";
print "<TEXTE_ARRET>$arret</TEXTE_ARRET>\n";
if ($data{'meta_parties'} =~ /(.*) c\/ (.*)/) {
    print "<PARTIES>";
    print "<DEMANDEURS><DEMANDEUR>$1</DEMANDEUR></DEMANDEURS>";
    print "<DEFENDEURS><DEFENDEUR>$2</DEFENDEUR></DEFENDEURS>";
    print "</PARTIES>\n";
}
if ($has_ref) {
    print "<REFERENCES>\n";
    foreach $k (keys %data) {
        if ($k =~ /reftype_([^_]+)_/) {
            print "<REFERENCE>";
            print "<TYPE>CITATION_ARRET</TYPE>";
            print "<TITRE>".$data{$k}."</TITRE>";
            print "</REFERENCE>\n";
        }
    }
    print "</REFERENCES>\n";
}
print "<TITRE>".$data{'titre'}."</TITRE>\n";
print "<SOURCE>https://legimonaco.mc".$data{'meta_path'}."/</SOURCE>\n";
print "<TYPE>arret</TYPE>\n";
print "<ALIMENTATION_TYPE>parsers/Monaco</ALIMENTATION_TYPE>\n";
print "</DOCUMENT>\n";
