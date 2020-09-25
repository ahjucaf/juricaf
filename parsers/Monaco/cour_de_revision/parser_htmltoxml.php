<?php

$xmlfile='xmls/';
$mois=['janvier'=>'01','février'=>'02','mars'=>'03','avril'=>'04','mai'=>'05','juin'=>'06','juillet'=>'07','août'=>'08','septembre'=>'09','octobre'=>'10','novembre'=>'11','décembre'=>'12'];
$inputfile='tmp/pages/arret13.html'; //$arg[1]
$content=file_get_contents($inputfile);

preg_match('/var url="\/305\/legismc\.nsf\/(.+)!/',$content,$numero);
$numero=$numero[1];


$content=html_entity_decode($content);
$content=preg_replace("/\n/"," ",$content);
// preg_match('/colspan=6 rowspan=1>(.+?)<\/td>/',$content,$titre_principale);
preg_match('/<td colspan=6 rowspan=1>(.+?)<\/table>/',$content,$titre_principale);

$titre_principale=$titre_principale[1];
$titre_principale=strip_tags($titre_principale);
// echo($titre_principale);

// preg_match('/<p class=resume>.+<\/p>[^<]+(.+[^<]+<p.+)[^<]+<div class=argument>/',$content,$sommaire);
preg_match('/<p class=resume>.+?<\/p>(.+<p class=par-resume>.+?)<div class=argument>/',$content,$sommaire);

$sommaire=$sommaire[1];
$sommaire=strip_tags($sommaire);

preg_match('/<p class=date-decision>(.+?)<\/p>/',$content,$datefr);
$datefr=$datefr[1];
$j=$datefr[0].$datefr[1];
preg_match('/\d\d (.+?) /',$datefr,$m);
$m=$m[1];
$m=$mois[$m];
preg_match('/.+? (\d+)/',$datefr,$a);
$a=$a[1];
$date=$a.'-'.$m.'-'.$j;

$juridiction="Cour de Révision";
$pays="Monaco";
$type="arret";
preg_match('/<p class=demandeur>(.+?)<\/p>  <p class=defendeur>c\/(.+?)<\/p>/',$content,$parties);

$demandeur=$parties[1];
$defendeur=$parties[2];


preg_match('/<div class=argument>(.+?)<\/html>/',$content,$content);
$content=$content[1];
$content=preg_replace('#</p>#',"\n",$content);
$content=strip_tags($content);
// $source=regarder le .json avec l url qui est associé au .html;
$sources = json_decode (file_get_contents('tmp/urls.json'), true);
$source=$sources[basename($inputfile)];
$name=preg_replace('/\//','_',$source);
$output = fopen($xmlfile.'CS_'.$name.'.xml', 'w');


fwrite($output, '<?xml version="1.0" encoding="utf8"?>');
fwrite($output,"\n");
fwrite($output, "<DOCUMENT>\n");
fwrite($output, "<ANALYSE>\n<TITRE_PRINCIPAL>$titre_principale</TITRE_PRINCIPAL><SOMMAIRE>$sommaire</SOMMAIRE></ANALYSE>");
fwrite($output, "<DATE_ARRET>$date</DATE_ARRET>\n");
fwrite($output, "<JURIDICTION>$juridiction</JURIDICTION>\n");
fwrite($output,"<FONDS_DOCUMENTAIRE>www.legimonaco.mc</FONDS_DOCUMENTAIRE>\n");
fwrite($output, "<NUM_ARRET>TS/$numero</NUM_ARRET>\n");
fwrite($output, "<PAYS>Monaco</PAYS>\n");
fwrite($output, "<TEXTE_ARRET>$content</TEXTE_ARRET>\n");
fwrite($output,"<PARTIES><DEMANDEURS><DEMANDEUR>$demandeur</DEMANDEUR></DEMANDEURS><DEFENDEURS><DEFENDEUR>$defendeur</DEFENDEUR></DEFENDEURS></PARTIES>\n");
fwrite($output,"<TITRE>Monaco, $juridiction, $datefr, TS/$numero</TITRE>\n");
fwrite($output,"<SOURCE>$source</SOURCE>");
fwrite($output, "<TYPE>arret</TYPE>\n");
fwrite($output, "</DOCUMENT>\n");
fclose($output);






?>
