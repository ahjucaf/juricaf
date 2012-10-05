<?php echo '<?xml version="1.0" encoding="utf8"?>'; ?>

<DOCUMENT>
<?php 

function printBalise($field, $balise) 
{
  if (!is_array($field)) {
    echo $field;
    return ;
  }
  if (array_keys($field))
    foreach ($field as $k => $v) {
      if (!is_int($k)) echo '<'.strtoupper($k).'>';
      printBalise($v, $k);
      if (!is_int($k)) echo '</'.strtoupper($k).'>';
    }
  else
    foreach($field as $v)
      printBalise($v, $balise);
}

foreach ($document->getFields() as $f) : 
if (preg_match('/^_/', $f))
  continue;
echo '<'.strtoupper($f).'>';
printBalise($document->{$f}, $f);
echo '</'.strtoupper($f).'>';
endforeach; ?>
</DOCUMENT>