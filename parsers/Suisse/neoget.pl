
#!/usr/bin/perl

use LWP::Simple;
use Date::Parse;
use Date::Language;
use Encode;
use HTML::Entities;
use XML::LibXML;
use Data::Dumper;
use LWP::UserAgent;
use IO::Socket::SSL;

$parser = XML::LibXML->new();

$verbose = ($ARGV[0]);

my $matrix = {
  'fr'=>['droit','recours','contre'],
  'de'=>['recht','urteil','gegen'],
  'it'=>['diritto','ricorso','contro']
};

$ua = LWP::UserAgent->new();
my $ua = LWP::UserAgent->new(
  ssl_opts => {
        verify_hostname => 0,
        SSL_verify_mode => IO::Socket::SSL::SSL_VERIFY_NONE
  },
);
$ua->agent('Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:58.0) Gecko/20100101 Firefox/58.0');

$ENV{PERL_LWP_SSL_VERIFY_HOSTNAME} = 0;
$re = $ua->get('http://www.juricaf.org/recherche/+/facet_pays%3ASuisse');
$dehtml = $re->content;
$dehtml =~ /Suisse\.png.*?<a.*?(\d{8})/ and $predate = $1;

$date = str2time(substr($predate,0,4).'-'.substr($predate,4,2).'-'.substr($predate,6,2).'T01:01:01');
$redate = $date+(24*60*60);

$redate -= (13*24*60*60);
#$load = get('http://relevancy.bger.ch/php/aza/http/index_aza.php?lang=fr&mode=index');

my %deja;
while ($redate < time) {
  ($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime($redate);
  $string = sprintf("%04d%02d%02d",$year+1900,$mon+1,$mday);
  warn($string) if ($verbose);
  $ru = $ua->get('http://relevancy.bger.ch/php/aza/http/index_aza.php?date='.$string.'&lang=fr&mode=news');
  $dhtml = $ru->content;
  $dhtml =~ s/[\n\r\t\s]+/ /g;
  (@as) = grep { /show_document$/ } $dhtml =~ /href="(\/php\/aza.*?)"/ig;
  foreach $a (@as) {
    $a =~ /aza\:\/\/(\d\d)\-(\d\d)\-(\d{4})\-/ and $candy = "$3$2$1";
    $oadate = str2time(substr($candy,0,4).'-'.substr($candy,4,2).'-'.substr($candy,6,2).'T01:01:01');
    next() unless($oadate >= $date);
    next() if($deja{$a});
    $deja{$a}++;
    $a =~ s/&amp;/&/g;
    $url = 'http://relevancy.bger.ch'.$a;
    print " = $url\n" if ($verbose);
    $ra = $ua->get($url);
    $dchtml = encode('utf-8',decode_entities($ra->content));

    $dchtml =~ s/[\n\r\t\s]+/ /g;
    my %score;
    foreach $lan (keys %$matrix) {
      $ee = $$matrix{$lan};
      foreach $m (@$ee) {
        @hits = $dchtml =~ /$m/ig;
        $score{$lan} += @hits;
      }
    }
    ($lang) = sort { $score{$b} <=> $score{$a} } keys %score;
    if ($lang eq 'fr') {
      $dchtml =~ s/<a.*?>//gi;
      $dchtml =~ s/<\/a.*?>//gi;
      $dchtml =~ s/<\/?b>//g;
      ($title) = $dchtml =~ /<title>(.*?)<\/title>/i;
      (@ps) = $dchtml =~ /<div class="para">(.*?)<\/div>/ig;
      ($dater) = grep { /Arrêt du.*(\d+\s.*?\d{4})/ } @ps;
      @iter = @ps;
      my $format;
      while ($i = shift(@iter)) {
        if ($i =~  /Arrêt du /) {
          $format = shift(@iter);
          $format = shift(@iter) unless ($format =~  /\w/);
        }
      }
      ($reference,$datenum) = $title =~ /(.*?)\s(.*)/ or next();
      $doc = $parser->parse_string('<?xml version="1.0" encoding="utf-8"?><DOCUMENT />');
      $root = $doc->getDocumentElement();
      $f = $doc->createElement('PAYS');
      $f->appendChild($doc->createTextNode('Suisse'));
      $root->appendChild($f);
      $f = $doc->createElement('JURIDICTION');
      $f->appendChild($doc->createTextNode('Tribunal fédéral suisse'));
      $root->appendChild($f);
      if ($format) {
        $f = $doc->createElement('FORMATION');
        $f->appendChild($doc->createTextNode($format));
        $root->appendChild($f);
      }
      $f = $doc->createElement('FONDS_DOCUMENTAIRE');
      $f->appendChild($doc->createTextNode('www.bger.ch'));
      $root->appendChild($f);
      $f = $doc->createElement('NUM_ARRET');
      $f->appendChild($doc->createTextNode($reference));
      $root->appendChild($f);
      $datestring = join('/',split(/\./,$datenum));
      $f = $doc->createElement('DATE_ARRET');
      $f->appendChild($doc->createTextNode($datestring));
      $root->appendChild($f);
      $f = $doc->createElement('SOURCE');
      $f->appendChild($doc->createTextNode($url));
      $root->appendChild($f);
      $f = $doc->createElement('TITRE');
      $titre = "Suisse, Tribunal fédéral, ";
      $titre .= "$format, " if ($format);
      $titre.= "$dater, $reference";
      $titre =~ s/_/ /g;
      $f->appendChild($doc->createTextNode($titre));
      $root->appendChild($f);
      $texte = $doc->createElement('TEXTE_ARRET');
      foreach $p (@ps) {
        $p =~ s/<.*?>/ 	/g;
        $texte->appendChild($doc->createTextNode($p));
        $texte->appendChild($doc->createElement('br'));
      }
      $root->appendChild($texte);
      $reference =~ s/\//-/g;
      $filename = 'SUISSE_'.$reference.'.xml';
      $doc->toFile('tmp/'.$filename);
      $datenum =~ /(\d\d)\.(20\d\d)/ or die("ici pas d'année");
      rename('tmp/'.$filename, 'pool/'.$filename) or die('impossible de déplacer le fichier xml');
    }
  }
  $redate = $redate+(24*60*60);
}
