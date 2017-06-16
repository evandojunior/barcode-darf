<?php

namespace App;

use Picqer\Barcode\BarcodeGeneratorPNG as BarcodeGenerator;

class BarcodeRender
{
    public static function parse($html)
    {
        $array = [];

        if (preg_match("/linha = \"(.*)\"/", $html, $array)) {
            $barcode = preg_replace("/[^0-9]/", "", $array[0]);
        } elseif (preg_match('/(<td class="titulo" align="center"[^>]*>)(.*?)(<\/td>)/', $html, $array)) {
            $barcode = preg_replace("/[^0-9]/", "", $array[0]);
        }
        $response = self::replaceBarcode(self::generate($barcode), $html);
        echo self::setStyle($response);
    }

    public static function generate($barcode)
    {
        $generator = new BarcodeGenerator;
        return '<img width="330" height="50" src="data:image/png;base64,' . base64_encode($generator->getBarcode($barcode, $generator::TYPE_INTERLEAVED_2_5_CHECKSUM)) . '">';
    }

    public static function getBarcode($html)
    {
        $code = [];
        preg_match("/linha = \"(.*)\"/", $html, $code);
      // if (!count($code) > 0) {
      //     throw new \Exception("Barcode nao localizado", 500);
      // }
      return $code[1];
    }

    public static function setStyle($html)
    {
        $style = "
        <style>
            table {
                margin-top: -3px;
            }

            tr > td {
                padding: 0px; !important
                word-break:break-all; !important
                table-layout: fixed; !important
            }

            .Boleto {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 7px;
                font-weight: bold;
                font-style: normal;
            }


        </style>";

        $html = str_replace('<table border="1" bordercolor="E1E1E1" width="650">', '<table border="1" bordercolor="E1E1E1" width="520" cellpadding="1" cellspacing="0">', $html);
        $html = str_replace('<img width="330"', '<img style="margin:2px;padding-left:360px;" width="340"', $html);
        $html .= $style;
        return $html;
    }

    public static function replaceBarcode($barcode, $html)
    {
        return preg_replace("/<td align=\"center\" class=\"Boleto\"[^>]*>.*?<\/td>/", '<td align="center">'.$barcode.'</td>', $html);
    }
}
