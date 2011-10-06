#!/usr/bin/perl

$phpoutput = shift;

while(<STDIN>) {
    /^( *)<node ID="(\d+)" label="([^"]+)"/; #"
    $depth = (length($1) - 2)/2;
    next if ($depth < 0);
    $id = $2;
    $label = $3;
    $tree[$depth] = $label;
    $ids{$id} = '';
    for ($i = 1 ; $i < $depth ; $i++) {
	$ids{$id} .= $tree[$i].' - ';
    }
    $ids{$id} .= $label;
}

if ($phpoutput) {
    print "<?php\n\$id2analyses = array(";
    foreach $id (keys %ids) {
	print '"'.$id.'" => "'.$ids{$id}."\",\n";
    }
    print '"" => "");';
    exit;
}

foreach $id (sort {$a <=> $b} keys %ids) {
    print '"'.$id.'";"'.$ids{$id}."\";\n";
}
