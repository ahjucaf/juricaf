<?php

if ($path) {
    echo file_get_contents($path);
    unlink($path);
}