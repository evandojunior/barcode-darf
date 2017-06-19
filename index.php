<?php
require_once 'vendor/autoload.php';

$data = array(
  'dar1' => "0734742800180", // CFDF
  'dar2' => "1568", // Cod Guia - 1566, 1568, 1549, 1317
  'dar3' => "06", // Cota
  'dar4' => "16/06/2017", // Data de Vencimento
  'dar5' => "2017", // Referencia
  'dar6' => "", // Inscricao
  'dar7' => "", // Placa/Chassi
  'dar8' => "", // NºProc./AIA/Not./DI
  'dar9' => "", // CFP/CGC
  'dar10' => "0009", // Unid. Adm
  'dar11' => "75", // Res. SEF
  'dar13' => "00000000000349", // Valor Principal
  'dar14' => "00000000000000", // Multa
  'dar15' => "00000000000000", // Juros
  'dar16' => "00000000000000", // Outros
  'dar17' => "00000000000349", // Valor Total
  'Razao' => "SUPER VAREJAO DA FARTURA LTDA                                         ", // Razao Social
  'End' => "SCL/SUL QD 203 BL D LOJA 35                                           ", // Endereco
  'pagamento' => "16/06/2017", // Data para pagamento
  'tributo' => "ICMS-ST - Pelas Entradas/Aquisi&ccedil;&otilde;es", // Tributo
  'observacao' => "", // Observacao da guia
  'vencido' => "0" // 0 - Nao Vencido 1 - Vencido
  );

App\DarfGenerate::gerarGuia($data);
