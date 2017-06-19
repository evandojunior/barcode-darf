<?php
namespace App;

use mPDF;
use ForceUTF8\Encoding;

class DarfGenerate
{
    public static function gerarGuia($data)
    {
        try {
            $url= "http://www.fazenda.df.gov.br//aplicacoes/dar_icms/icms_valida.cfm?id_menu=4";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            $response = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            $output = self::getFormProperties($response);
            $html = self::validar($output);
            self::parserPDF($html);
        } catch (\Exception $e) {
            echo '<pre>';
            print_r($e);
            exit();
        }
    }
    public static function validar($data)
    {
        $url = "http://www.fazenda.df.gov.br//aplicacoes/dar_icms/icms_pagamento.cfm?id_menu=4";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        if ($error = curl_error($ch)) {
            throw new \Exception("Falha ao processar dados na Sefaz");
        }
        $response = curl_exec($ch);
        curl_close($ch);
        return self::parserBarcode($response);
    }
    public static function getFormProperties($html)
    {
        $itens = [];
        $dom = new \DOMDocument;
        @$dom->loadHTML($html);
        foreach ($dom->getElementsByTagName('input') as $tag) {
            $href = $tag->getAttribute('value');
            $hidden = $tag->getAttribute('type');
            $name = $tag->getAttribute('name');
            if ($hidden == 'Hidden') {
                $itens[$name] = ($href);
            }
        }
        if (!count($itens)) {
            throw new \Exception("Falha ao retornar campos");
        }
        return $itens;
    }

    public static function parserBarcode($html)
    {
        $generator = new \Picqer\Barcode\BarcodeGeneratorHTML();
        $barcode = $generator->getBarcode(self::getCode($html), $generator::TYPE_INTERLEAVED_2_5, 1, 50);
        return self::addStyle(self::substituirCodigo($html, $barcode));
    }

    public static function getCode($html)
    {
        $code = [];

        if (!preg_match('/(<td class="titulo" align="center"[^>]*>)(.*?)(<\/td>)/', $html, $code)) {
            throw new \Exception("Falha ao renderizar código de barras");
        }

        $codigo = preg_replace("/[^0-9 ]/", "", $code[0]);
        return trim(preg_replace("/(\d){0}(\d |\d$)+/", "", $codigo));
    }

    public static function parserPDF($html)
    {
        $mpdf = new mPDF('p', 'A4', 12, "Arial", 15, 15, 10, 10, 10, 10);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    public static function substituirCodigo($html, $codigo)
    {
        $count = 0;
        $count2 = 0;
        $tmp = preg_replace('/(<td colspan="3" align="center"[^>]*>)(.*?)(<\/td>)/s', '<td colspan="3" align="center">'.$codigo.'</td>', $html, -1, $count);
        $tmp2 = preg_replace('/(<td align="center" class="Boleto"[^>]*>)(.*?)(<\/td>)/s', '<td colspan="3" align="center">'.$codigo.'</td>', $html, -1, $count2);

        if ($count) {
            return $tmp;
        } elseif ($count2) {
            return $tmp2;
        } else {
            throw new \Exception("Falha ao realizar parser do código de barra da guia");
        }
    }

    public static function addStyle($html)
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

        </style>";

        $html = str_replace('<table border="1" bordercolor="E1E1E1" width="650">', '<table border="1" bordercolor="E1E1E1" width="520" cellpadding="1" cellspacing="0">', $html);
        $html .= $style;
        return $html;
    }
}
