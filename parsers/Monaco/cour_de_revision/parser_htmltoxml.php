<?php

$xmlfile='xmls/';
$mois=['janvier'=>'01','février'=>'02','mars'=>'03','avril'=>'04','mai'=>'05','juin'=>'06','juillet'=>'07','août'=>'08','septembre'=>'09','octobre'=>'10','novembre'=>'11','décembre'=>'12'];
$inputfile= $argv[1];
$content=file_get_contents($inputfile);


preg_match('/var url="\/305\/legismc\.nsf\/.+\/(.+)!/',$content,$numero);
$numero=$numero[1];
//echo($numero."\n");
$content=html_entity_decode($content);
$content=preg_replace("/\n/"," ",$content);

preg_match('/<td colspan=6 rowspan=1>(.+?)<\/table>/',$content,$titre_principale);
if(count($titre_principale)>1){
  $titre_principale=$titre_principale[1];
  $titre_principale=strip_tags($titre_principale);
}
else{
  $titre_principale="";
}


if ($numero=="e48f11b666607816c125858200339fa8"){
  preg_match('/<p class=resume>.+?<\/p>(.+<p class=par-resume>.+?)<\/div>/',$content,$sommaire);
  $sommaire=$sommaire[1];
  $sommaire=strip_tags($sommaire);
}
else{
  preg_match('/<p class=resume>.+?<\/p>(.+<p class=par-resume>.+?)<div class=argument>/',$content,$sommaire);
  if(count($sommaire)>1){
    $sommaire=$sommaire[1];
    $sommaire=strip_tags($sommaire);
  }
  else{
    $sommaire="";
  }
}



preg_match('/<p class=date-decision>(.+?)<\/p>/',$content,$datefr);
$datefr=$datefr[1];
$j=$datefr[0].$datefr[1];
preg_match('/\d\d (.+?) /',$datefr,$m);
$m=$m[1];
$m=$mois[$m];
preg_match('/.+? (\d+)/',$datefr,$a);
$a=$a[1];
$date=$a.'-'.$m.'-'.$j;
$datefr=trim($datefr);

$juridiction="Cour de révision";
$pays="Monaco";
$type="arret";

preg_match('/<p class=demandeur>(.+?)<\/p>  <p class=defendeur>c\/(.+?)<\/p>/',$content,$parties);
if(count($parties)<3){
  $defendeur="";
  $demandeur="";
}
else{
  $demandeur=$parties[1];
  $defendeur=$parties[2];
}


if ($numero=="e48f11b666607816c125858200339fa8"){
  $content="";
}
else{
  preg_match('/<div class=argument>(.+?)<\/html>/',$content,$content);
  // print_r($content);
  $content=$content[1];
  $content=preg_replace('#</p>#',"\n",$content);
  $content=strip_tags($content);
  $content=preg_replace("/\n/","<br/>",$content);

}

$sources = json_decode (file_get_contents('tmp/urls.json'), true);
$source=$sources[basename($inputfile)];

$name=preg_replace('/\//','_',$source);
$name=preg_replace('/:/','',$name);

$output = fopen($xmlfile.'CS_'.$name.'.xml', 'w');

fwrite($output, '<?xml version="1.0" encoding="utf8"?>');
fwrite($output,"\n");
fwrite($output, "<DOCUMENT>\n");
fwrite($output, "<ANALYSES><ANALYSE>\n<TITRE_PRINCIPAL>$titre_principale</TITRE_PRINCIPAL><SOMMAIRE>$sommaire</SOMMAIRE></ANALYSE></ANALYSES>");
fwrite($output, "<DATE_ARRET>$date</DATE_ARRET>\n");
fwrite($output, "<JURIDICTION>$juridiction</JURIDICTION>\n");
fwrite($output,"<FONDS_DOCUMENTAIRE>www.legimonaco.mc</FONDS_DOCUMENTAIRE>\n");
fwrite($output, "<NUM_ARRET>$numero</NUM_ARRET>\n");
fwrite($output, "<PAYS>Monaco</PAYS>\n");
fwrite($output, "<TEXTE_ARRET>$content</TEXTE_ARRET>\n");
fwrite($output,"<PARTIES><DEMANDEURS><DEMANDEUR>$demandeur</DEMANDEUR></DEMANDEURS><DEFENDEURS><DEFENDEUR>$defendeur</DEFENDEUR></DEFENDEURS></PARTIES>\n");
fwrite($output,"<TITRE>Monaco, $juridiction, $datefr, $numero</TITRE>\n");
fwrite($output,"<SOURCE>$source</SOURCE>");
fwrite($output, "<TYPE>arret</TYPE>\n");
fwrite($output, "</DOCUMENT>\n");
fclose($output);


?>
