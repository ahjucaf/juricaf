<?php 

$dossierArretsJSON = "./json";

$firstResult = 0;
$numberOfResults = 100; //on fera une boucle sur ça pour tous les récupérer.

while(true){
  
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, 'https://unik.caij.qc.ca/rest/search/?pipeline=unik&debug=0&errorsAsSuccess=1');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);

  
  curl_setopt($ch, CURLOPT_POSTFIELDS, "aq=(%40sourcedudroit%3D%3DJurisprudence)%20(%40sourcedudroit%3D%3DJurisprudence)%20(%40tribunal%3D%3D(%22Cour%20d'appel-QC%22%2C%22Cour%20supr%C3%AAme%20du%20Canada-CA%22))%20(%40syslanguage%3D%3DFrench)&cq=((%40syscollection%3D%3D(BIBLIO%2CTOPO%2CeDoctrine%2CeLois%2CBVCQ%2CConcerto%2CCanlii))%20(NOT%20%40documentonly))%20(%40recherchable%3D%3D1)&searchHub=Recherche&tab=unik&language=fr&firstResult=".$firstResult."&numberOfResults=".$numberOfResults."&excerptLength=400&retrieveFirstSentences=true");

  $result = curl_exec($ch);
  if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
  }
  curl_close($ch);

  $json_result = json_decode($result);
  $array = json_decode($result,true);

  
  if(array_key_exists('statusCode', $array)){
      break;
  }

  foreach($json_result->results as $arret){
    $name_json = str_replace(".html",".json",$arret->raw->sysfilename);
    file_put_contents($dossierArretsJSON."/".$name_json.'-meta', json_encode($arret, JSON_PRETTY_PRINT));
    
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

    file_put_contents($dossierArretsJSON."/".$name_json.'-content', $result);

    $source = $arret->raw->sysuri;
    echo "$name_json $source\n";

  }
  
  $firstResult = $numberOfResults;
  $numberOfResults += 100;
}