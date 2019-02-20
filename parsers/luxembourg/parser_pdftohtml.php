<?php

$content = file_get_contents('php://stdin');
$content = preg_replace("/&(#160|nbsp);/", " ", $content);
$content = html_entity_decode($content);
$content = preg_replace("/ <br.>\n[0-9]* <br.>\n<hr.>\n<a name=[0-9]*><.a>/", '', $content);

$juridiction = "";
$numero = "";
$date = "";
$partie1 = "";
$partie2 = "";

if (preg_match('/(COUR[^<]*[^ :]|TRIBUNAL[^<]*[^ :])[ :]*</i', $content, $match)) {
  $juridiction = $match[1];
}
if (preg_match('/(Numéros?|N°|Nos?) ([A-Z0-9\-, et\+]+) du(<br.?>|\n| )*(rôle|registre)/i', $content, $match) || preg_match('/(Numéros?|N°|Nos?)  *du rôle[ :<b>]*([A-Z0-9][A-Z0-9 ]+[A-Z0-9])/', $content, $match)) {
  $numero = $match[2];
}
if (preg_match('/Audience publique( extraordinaire | )du *([^<]*[0-9]) *</i', $content, $match)){
  $date = $match[2];
}elseif (preg_match('/(Luxembourg|publique)( |<br.?>|<b>)+du( |<br.?>|<b>)+(lundi.....[^<]*|mardi......[^<]*|mercredi.......[^<]*|jeudi......[^<]*|vendredi....[^<]*|samedi......[^<]*|dimanche......[^<]*) *</i', $content, $match)){
  $date = $match[4];
}elseif (preg_match('/du ([0-9]{1,2}[.\/][0-9]{1,2}[.\/][0-9]{4})./', $content, $match)){
  $date = $match[1];
}

if (preg_match('/Recours formé par ([^<]*) *<.*\ncontre ([^<]*) */', $content, $match)) {
  $partie1 = $match[1];
  $partie2 = $match[2];
}
$ligne = "$juridiction;$numero;$date;$partie1;$partie2";
$ligne = preg_replace('/<[^>]*>/', '', $ligne);
$ligne = preg_replace('/\n/', ' ', $ligne);
$ligne = preg_replace('/  */', ' ', $ligne);
$ligne = preg_replace('/; */', ';', $ligne);
$ligne = preg_replace('/ *;/', ';', $ligne);

print "$ligne\n";
