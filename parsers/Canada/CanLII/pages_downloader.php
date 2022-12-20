<?php 

$dossierArretsJSON = "./json";
$dossierArretsXML = "./xml";

$firstResult = 0;
$numberOfResults = 20;

$getdata = true;

while($getdata){
  
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, 'https://unik.caij.qc.ca/rest/search/?pipeline=unik&debug=0&errorsAsSuccess=1');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  
  curl_setopt($ch, CURLOPT_POSTFIELDS, "aq=(%40sourcedudroit%3D%3DJurisprudence)%20(%40format%3D%3D%22En%20ligne%22)%20(%40sourcedudroit%3D%3DJurisprudence)%20(%40tribunal%3D%3D(%22Cour%20supr%C3%AAme%20du%20Canada-CA%22))%20(%40syslanguage%3D%3DFrench)&cq=((%40syscollection%3D%3D(BIBLIO%2CTOPO%2CeDoctrine%2CeLois%2CBVCQ%2CConcerto%2CCanlii))%20(NOT%20%40documentonly))%20(%40recherchable%3D%3D1)&sortCriteria=fielddescending&sortField=%40datenum&searchHub=Recherche&tab=unik&language=fr&firstResult=".$firstResult."&numberOfResults=".$numberOfResults."&excerptLength=400&retrieveFirstSentences=true");

  $result = curl_exec($ch);
  if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
  }
  curl_close($ch);

  file_put_contents("test.txt",$result);

  $json_result = json_decode($result);
  $array = json_decode($result,true);

  foreach($json_result->results as $arret){
    $xmlfile = "$dossierArretsXML/".str_replace("html","xml",$arret->raw->sysfilename);

    if(file_exists($xmlfile)){
        // fwrite(STDERR, "xml $xmlfile déjà importé\n");
        $getdata = false;
        break;
    }

    $json_file = $dossierArretsJSON."/".str_replace(".html","-meta.json",$arret->raw->sysfilename);
    file_put_contents($json_file, json_encode($arret, JSON_PRETTY_PRINT));
    
    $ch = curl_init();
    $uniqid = $arret->uniqueId;
    $url = "https://unik.caij.qc.ca/rest/search/text?pipeline=unik&debug=0&uniqueId=".$uniqid."&errorsAsSuccess=1";

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);

    $html_file = $dossierArretsJSON."/".str_replace(".html","-content.json",$arret->raw->sysfilename);

    file_put_contents($html_file, $result);

    echo "$json_file $html_file\n";
  }

  $firstResult = $numberOfResults;
  $numberOfResults += 20;
}