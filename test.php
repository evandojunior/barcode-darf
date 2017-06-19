<?php
require_once 'vendor/autoload.php';

$codigoOriginal = "856600000041 450700091009 417000087524 598904739656";
// $codigo = preg_replace("/(\d){0}(\d |\d$)+/", "", $codigoOriginal);

$codigoOriginal = "856200000003 034900091603 617000087560 154506739650";

$codigoLinha = "85620000000034900091606170000875615450673965";
$codigoRegex = preg_replace("/(\d){0}(\d |\d$)+/", "", $codigoOriginal);




$generator = new Picqer\Barcode\BarcodeGeneratorHTML();
echo $generator->getBarcode($codigoLinha, $generator::TYPE_INTERLEAVED_2_5, 1, 50);
echo "<hr>";

$generator = new Picqer\Barcode\BarcodeGeneratorHTML();
echo $generator->getBarcode($codigoRegex, $generator::TYPE_INTERLEAVED_2_5, 1, 50);
echo "<hr>";
