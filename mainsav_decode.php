#!/usr/bin/php
<?php

// can be found in ~/.local/share/Steam/userdata/119879364/209540/remote
$file = 'main.sav';

$data = file_get_contents($file);

$key = 'tkileyismoarawesome!!!!citationneede';

$header = substr($data, 0, 40);
$crypt  = substr($data, 40);

file_put_contents('main_header.sav', $header);

$version = substr($header, 32, 4);
$version_num = unpack('L', $version);
echo 'Version: ' . $version_num[1] . PHP_EOL;

$length = substr($header, 36, 4);
$length_num = unpack('L', $length);
echo 'Payload length: ' . $length_num[1] . ' Bytes' . PHP_EOL;

$output = '';
for ($i=0; $i<strlen($crypt); $i++) {
    $keyidx = $i % strlen($key);

    $keychar = substr($key, $keyidx, 1);
    $char = substr($crypt, $i, 1);

    $newchar = ord($char) ^ ord($keychar);

    $output .= chr($newchar);
}

#file_put_contents('main_data.sav', $output);

$json = json_decode($output);
$pretty = json_encode($json, JSON_PRETTY_PRINT);

file_put_contents('main_data.sav', $pretty);
