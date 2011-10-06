<?php

$dir = $argv[1];
$all = 0;
if (isset($argv[2]))
  $all = $argv[2];

if (!$dir) {
  print "ERROR : no directory name\n";
  print "USAGE : php ".$argv[0]." <directory> [all]\n\n";
  exit(1);
 }

include('config/config.php');

$mbox = imap_open('{'.$mailserver.':143/imap}INBOX', $mailaddress, $mailpassword);
$messageids = array();
$headers = imap_headers($mbox);
foreach ($headers as $val) {
  if ($all || preg_match('/^\s+[A-Z]/', $val)) {
    if (preg_match('/ (\d+)\)/', $val, $match))
      $messageids[] = $match[1];
  }
}

foreach ($messageids as $id) {
  $mail = imap_fetchstructure($mbox, $id);
  unset($mail->parts[0]);
  $pos = 2;
  foreach ($mail->parts as $part) {
    if ($part->subtype != 'MSWORD')
      continue;
    $fh = fopen($dir.'/'.$part->parameters[0]->value.'.tmp', 'w');
    fwrite($fh, imap_base64(imap_fetchbody($mbox, $id, $pos++)));
    fclose($fh);
    rename($dir.'/'.$part->parameters[0]->value.'.tmp', $dir.'/'.$part->parameters[0]->value);
    print "$dir/".$part->parameters[0]->value."\n";
  }
} 
imap_close($mbox);
 