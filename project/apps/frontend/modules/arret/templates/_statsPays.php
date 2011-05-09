<ul class="payscols">
<?php
foreach ($pays as $p) 
{
  $pays = preg_replace('/ /', '_', $p['key'][0]);
  echo '<li>'.link_to($p['key'][0].' ('.$p['value'].')','recherche/search?query=pays:'.$pays).'</li>';
}?></ul>
