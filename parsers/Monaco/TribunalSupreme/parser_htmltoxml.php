<?php
$xmlfile='xmls/';
$mois=['01'=>'janvier','02'=>'février','03'=>'mars','04'=>'avril','05'=>'mai','06'=>'juin','07'=>'juillet','08'=>'août','09'=>'septembre','10'=>'octobre','11'=>'novembre','12'=>'décembre'];

$inputfile = $argv[1];
$c= file($inputfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$content='';
foreach($c as $num=>$ligne){
  $ligne=trim($ligne);
  $content=$content.$ligne;
}

preg_match("#<p class=\"date\">(.+)</p>#iU",$content,$date);
$date=$date[1];
$j=$date[0].$date[1];
$m=$date[3].$date[4];
$a=$date[6].$date[7].$date[8].$date[9];
$date=$a.'-'.$m.'-'.$j;

preg_match('/<p class="date">.+<\/p><h1>([^<]+)/',$content,$titre);
$titre=$titre[1];
preg_match('/[0-9]+[-]+.+[0-9]/',$titre,$numero);

if ($numero==null){
  $numero=='';
}
else{
  $numero=$numero[0];
}

$juridiction1='Tribunal suprême';
$juridiction2='Tribunal Suprême';


preg_match("#<div class=\"content\">.+<div class=\"print-button\">#",$content,$content);
$content=$content[0];
$titre=html_entity_decode($titre);


preg_match('/[0-9]+[-]+.+[0-9](.+?)c\//',$titre,$demandeur);
if(count($demandeur)>1){
  $demandeur=$demandeur[1];
}
else{
  $demandeur='';
}
preg_match('/c\/(.+)/',$titre,$defendeur);
if(count($defendeur)>1){
// print_r($defendeur);
  $defendeur=$defendeur[1];
}
else{
  $defendeur='';
}

if($demandeur=='' && $defendeur==''){

    if(strpos($content,'Affaire')!=null && strpos($content,'Contre')!=null){
        preg_match('/Affaire(.+?)Contre/',$content,$parties);   //regarde si c'est le cas avec l'arret Affaire et Contre
        // print_r($parties);

        if (count($parties)>1){                    //si c'est le cas avec l'arret Affaire et Contre
            preg_match('/<strong>(.+?)<\/strong><\/p>/',$parties[1],$demandeur);
            // print_r($demandeur);
            // echo($v);
            if (count($demandeur)>1){
                $demandeur=$demandeur[0];
                $demandeur=strip_tags($demandeur);
            }
            else{
                $demandeur='';
            }
      }

        preg_match('/Contre.+<p style="text-align: right;"><strong>(.+?)<h1/',$content,$def);
        // print_r($def);
        if(count($def)>1){
          preg_match('/(.+?)<\/p>/',$def[1],$defendeur);
          // print_r($defendeur);
          $defendeur=$defendeur[1];
          $defendeur=strip_tags($defendeur);
        }
        else{
          $defendeur='';
        }
    }

    elseif(strpos($content,'En la cause de')!=null && strpos($content,'Contre')!=null){

        preg_match('/<p><strong>En(.+?)<\/strong><\/p><p><strong>(.+?)<\/strong>/',$content,$demandeur);
        // print_r($defendeur);
        if(count($demandeur)>1){
        $demandeur=$demandeur[2];
      }
        else{
          $demandeur='';
        }
        // $defendeur='Honorine';
        preg_match('/Contre<\/strong> :<\/p><p><strong>(.+?)<\/strong>/',$content,$defendeur);
        // print_r($defendeur);
        // echo($content);
        if(count($defendeur)>1){
        $defendeur=$defendeur[1];
      }
        else{
          $defendeur='';
        }
    }
    else{
      $demandeur='';
      $defendeur='';
    }
}

$content=html_entity_decode($content);
$content=preg_replace('/\s+/', " ", $content);
$content=preg_replace('#</h[1-4]*>#',"\n",$content);
$content=preg_replace('#</p>#',"\n",$content);
$content=preg_replace('#<b>#',"\n",$content);
$content=strip_tags($content);
$content=preg_replace("/\n/","<br/>",$content);

// $content=trim($content);
// preg_match('/<strong>([^<]+)/',$content,$parties);

$d=new DateTime($date);
$date_min=new DateTime('2019-05-01');



if($d>$date_min && $numero!=''){


    $sources = json_decode (file_get_contents('tmp/urls.json'), true);
    $source=$sources[basename($inputfile)];
    $name=preg_replace('/\//','_',$source);
    $output = fopen($xmlfile.'TS_'.$name.'.xml', 'w');
    fwrite($output, '<?xml version="1.0" encoding="utf8"?>');
    fwrite($output,"\n");
    fwrite($output, "<DOCUMENT>\n");
    fwrite($output, "<DATE_ARRET>$date</DATE_ARRET>\n");
    fwrite($output, "<JURIDICTION>$juridiction1</JURIDICTION>\n");
    fwrite($output,"<FONDS_DOCUMENTAIRE>www.tribunal-supreme.mc</FONDS_DOCUMENTAIRE>\n");
    fwrite($output, "<NUM_ARRET>TS/$numero</NUM_ARRET>\n");
    fwrite($output, "<PAYS>Monaco</PAYS>\n");
    fwrite($output, "<TEXTE_ARRET>$content</TEXTE_ARRET>\n");
    fwrite($output,"<PARTIES><DEMANDEURS><DEMANDEUR>$demandeur</DEMANDEUR></DEMANDEURS><DEFENDEURS><DEFENDEUR>$defendeur</DEFENDEUR></DEFENDEURS></PARTIES>\n");
    $datefr=$j.' '.$mois[$m].' '.$a;
    fwrite($output,"<TITRE>Monaco, $juridiction2, $datefr, TS/$numero</TITRE>\n");
    fwrite($output,"<SOURCE>$source</SOURCE>");
    fwrite($output, "<TYPE>arret</TYPE>\n");
    fwrite($output, "</DOCUMENT>\n");
    fclose($output);

}




?>
