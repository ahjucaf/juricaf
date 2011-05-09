<?php echo '<?xml version="1.0" encoding="utf8"?>'; ?>

<JURICAF>
<?php 

function printBalise($field, $balise) 
{
  if (!is_object($field)) {
    echo $field;
    return ;
  }
  foreach ($field as $k => $v) {
    if (is_int($k)) {
      $k = preg_replace('/s$/i', '', $balise);
    }
    echo '<'.strtoupper($k).'>';
    printBalise($v, $k);
    echo '</'.strtoupper($k).'>';
  }
}

foreach ($document->getFields() as $f) : 
if (preg_match('/^_/', $f))
  continue;
echo '<'.strtoupper($f).'>';
printBalise($document->{$f}, $f);
echo '</'.strtoupper($f).'>';
endforeach; ?>
</JURICAF>