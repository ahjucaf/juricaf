<style>
ul {margin: auto; width: 30% ;}
li { float: left; width: 30%; }
li:nth-child(odd) { clear: left; width: 70%}
</style>
<ul>
<?php
foreach ($pays as $p) 
{
  echo '<li>'.link_to($p['key'][0].' ('.$p['value'].')','recherche/search?query=pays:'.$p['key'][0]).'</li>';
}?></ul>
