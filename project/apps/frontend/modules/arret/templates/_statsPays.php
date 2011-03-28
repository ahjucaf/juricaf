<ul>
<?php
foreach ($pays as $p) 
{
  $sep = '';
  if (preg_match('/ /', $p['key'][0]))
    $sep = '"';
  echo '<li>'.link_to($p['key'][0].' ('.$p['value'].')','recherche/search?query=pays:'.$sep.$p['key'][0].$sep).'</li>';
}?></ul>
