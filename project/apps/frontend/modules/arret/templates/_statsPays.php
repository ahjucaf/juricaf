<ul>
<?php
foreach ($pays as $p) 
{
  echo '<li>'.link_to($p['key'][0].' ('.$p['value'].')','recherche/search?query=pays:'.$p['key'][0]).'</li>';
}?></ul>
