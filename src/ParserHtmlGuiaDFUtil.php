<?php
namespace App;
class ParserHtmlGuiaDFUtil
{
    public static function parse($html)
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
        // $html = str_replace('<img width="330"','<img style="margin:2px;" width="340"', $html);
        return $html;
    }
}
