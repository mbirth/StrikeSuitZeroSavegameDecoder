#!/usr/bin/php
<?php

$key = 'tkileyismoarawesome!!!!citationneede';

$header = file_get_contents('main_header.sav');
$data   = file_get_contents('main_data.sav');

$json = json_decode($data);
$crypt = json_encode($json);

$header = substr($header, 0, 36);
$header .= pack('L', strlen($crypt));


$version = substr($header, 32, 4);
$version_num = unpack('L', $version);
echo 'Version: ' . $version_num[1] . PHP_EOL;

$length = substr($header, 36, 4);
$length_num = unpack('L', $length);
echo 'Payload length: ' . $length_num[1] . ' Bytes' . PHP_EOL;


$output = $header;
for ($i=0; $i<strlen($crypt); $i++) {
    $keyidx = $i % strlen($key);

    $keychar = substr($key, $keyidx, 1);
    $char = substr($crypt, $i, 1);

    $newchar = ord($char) ^ ord($keychar);

    $output .= chr($newchar);
}

file_put_contents('main_new.sav', $output);
// goes to: ~/.local/share/Steam/userdata/119879364/209540/remote
