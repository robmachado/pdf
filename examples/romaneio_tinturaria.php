<?php
error_reporting(E_ALL);
ini_set('display_errors', 'Off');
require_once '../bootstrap.php';

use Fimacom\Pdf\Pdf;

$payload = [
    'romaneio' => '123456',
    'ordem_servico' => 23,
    'data' => '11/10/2023',
    'previsao' => '02/11/2023',
    'parceiro' => '	SEIREN DO BRASIL IND TEXTIL LTDA',
    'cor' => 'GRIS F00845',
    'processo' => '', //processo na tinturaria
    'codigo' => '2333TA',
    'produto' => 'FEEL FAST TINTO c/ amaciante',
    'total' => 0,
    'peso_liquido' => '',
    'peso_bruto' => '',
    'largura' => 1.52,
    'composicao' => '98.5% POLIÉSTER + 1,5% ELASTANO',
    'gramatura' => 95,
    'rendimento' => 6.93,
    'obs1' => 'Na ORDENS DE SERVIÇO haverão até 4 linhas com até 80 caracteres em cada linha, para incluir observações para a tinturaria',
    'obs2' => 'para que se possa incluir instruções especificas em casos de reprocessamentos e desenvolvimentos',
    'obs3' => 'nesses campos vocês podem determinar ajustes especificos e outras coisas como alguns cuidados especificos',
    'obs4' => 'Ex. Ramar com 1.55 m'
];
$std = json_decode(json_encode($payload));
date_default_timezone_set('America/Sao_Paulo');

for($x=1; $x<87; $x++) {
    $pl = mt_rand(14, 15) + mt_rand(0, 99)/100;
    $pb = $pl + 0.4;
    $op = 1396;
    $std->itens[] = (object) [
        'sku' => '1396'. str_pad($x, 3, '0', STR_PAD_LEFT),
        'lote' => '1396',
        'peso' => $pl,
        'peso_bruto' => $pb
    ];
}
$pb = 0;
$pl = 0;
foreach ($std->itens as $item) {
    $pb += $item->peso_bruto;
    $pl += $item->peso;
}
$std->total = count($std->itens);
$std->peso_liquido = number_format($pl, 2, ',', '.');
$std->peso_bruto = number_format($pb, 2, ',', '.');

//echo "<pre>";
//print_r($std);
//echo "</pre>";
//210 x 297
$maxW = 210 - 2;
$pdf = new Pdf('P', 'mm', 'A4');
$pdf->setCreator('Fabric');
$pdf->setAuthor('Roberto');
$pdf->setMargins(2,2,2);
$pdf->setDrawColor(0,0,0);
$pdf->setFillColor(255,255,255);
$pdf->compress = 9;
$pdf->open();

$subs = [];
$count = count($std->itens);
if ($count > 64) {
    //cabem 64 paças na primeira pagina
    $subs[0] = array_slice($std->itens,0,64);
    $newitens = array_slice($std->itens,64);
    //cabem 76 peças na paginas seguintes
    $nsubs = array_chunk($newitens, 76);
    foreach($nsubs as $ns) {
        $subs[] = $ns;
    }
} else {
    $subs[] = $std->itens;
}
$totalPages = count($subs);
$actualPage = 0;
$i = 1;
foreach($subs as $itens) {
    $actualPage++;
    $pdf->addPage();
    $pdf->setLineWidth(0.1);
    $pdf->setTextColor(0, 0, 0);
    $pdf->addFont('textilesym');

    //cabeçalho presente em todas as páginas
    $logo = 'data://text/plain;base64,' . base64_encode(file_get_contents(__DIR__ . '/fimacom_logo_2024.png'));
    $pdf->image($logo, 4, 2, 40, 15, 'png');
    $aFont = ['font' => 'arial', 'size' => 14, 'style' => ''];
    $pdf->textBox(2, 2, $pdf->w - 4, 15, 'ROMANEIO DE TINTURARIA', $aFont, 'T', 'C', false);
    $aFont = ['font' => 'arial', 'size' => 12, 'style' => 'I'];
    $pdf->textBox(2, 8, $pdf->w - 4, 20, 'Output Picking List', $aFont, 'T', 'C', false);
    $aFont = ['font' => 'arial', 'size' => 7, 'style' => 'I'];
    $pdf->textBox(2, 2, $pdf->w - 4, 15, "Retorno Previsto para : {$std->previsao}", $aFont, 'B', 'C', false);

    $aFont = ['font' => 'arial', 'size' => 7, 'style' => ''];
    $pdf->textBox(175, 2, 20, 7, 'Romaneio', $aFont, 'T', 'L', false);
    $pdf->textBox(175, 9, 20, 7, 'Data', $aFont, 'T', 'L', false);
    $aFont = ['font' => 'arial', 'size' => 12, 'style' => 'B'];
    $pdf->textBox(175, 3, 20, 7, (int)$std->romaneio, $aFont, 'C', 'L', false);
    $pdf->textBox(175, 10, 25, 7, $std->data, $aFont, 'C', 'L', false);
    $y = 26;
    //dados a serem impressos apenas na primeira página
    if ($actualPage == 1) {
        $aFont = ['font' => 'arial', 'size' => 7, 'style' => 'I'];
        $pdf->textBox(2, 18, 80, 9, 'Tinturaria', $aFont, 'T', 'L', true);
        $pdf->textBox(82, 18, 96, 9, 'Cor/Processo', $aFont, 'T', 'L', true);
        $pdf->textBox(82 + 96, 18, 30, 9, 'Ordem Serviço', $aFont, 'T', 'L', true);
        $pdf->textBox(2, 27, 80, 9, 'Produto', $aFont, 'T', 'L', true);
        $pdf->textBox(82, 27, 210 - 80 - 4, 9, 'Composição', $aFont, 'T', 'L', true);
        $pdf->textBox(2, 36, 34, 9, 'Largura', $aFont, 'T', 'L', true);
        $pdf->textBox(36, 36, 34, 9, 'Gramatura', $aFont, 'T', 'L', true);
        $pdf->textBox(70, 36, 34, 9, 'Rendimento', $aFont, 'T', 'L', true);
        $pdf->textBox(104, 36, 34, 9, 'N. Peças Total', $aFont, 'T', 'L', true);
        $pdf->textBox(138, 36, 35, 9, 'Peso Líquido Total', $aFont, 'T', 'L', true);
        $pdf->textBox(173, 36, 35, 9, 'Peso Bruto Total', $aFont, 'T', 'L', true);

        $aFont = ['font' => 'arial', 'size' => 10, 'style' => 'B'];
        $pdf->textBox(2, 18, 80, 9, $std->parceiro, $aFont, 'C', 'L', false);
        $texto = $std->cor;
        if (!empty($std->processo)) {
            $texto = "{$std->cor} [{$std->processo}]";
        }
        $pdf->textBox(82, 18, 96, 9, $texto, $aFont, 'C', 'C', false);
        $pdf->textBox(82 + 96, 18, 30, 9, $std->ordem_servico, $aFont, 'C', 'C', false);
        $pdf->textBox(2, 27, 80, 9, "{$std->codigo} {$std->produto}", $aFont, 'C', 'C', false);
        $pdf->textBox(82, 27, 210 - 80 - 4, 9, $std->composicao, $aFont, 'C', 'C', false);

        $pdf->textBox(2, 36, 34, 9, "{$std->largura} m", $aFont, 'C', 'R', false);
        $pdf->textBox(36, 36, 34, 9, "{$std->gramatura} g/m²", $aFont, 'C', 'R', false);
        $pdf->textBox(70, 36, 34, 9, "{$std->rendimento} m/kg", $aFont, 'C', 'R', false);
        $pdf->textBox(104, 36, 34, 9, $std->total, $aFont, 'C', 'R', false);
        $pdf->textBox(138, 36, 35, 9, "{$std->peso_liquido} kg", $aFont, 'C', 'R', false);
        $pdf->textBox(173, 36, 35, 9, "{$std->peso_bruto} kg", $aFont, 'C', 'R', false);

        $aFont = ['font' => 'arial', 'size' => 7, 'style' => 'I'];
        $pdf->textBox(2, 46, 210 - 4, 19, 'Observações', $aFont, 'T', 'L', true);
        //4 linhas com 80 caracteres cada no maximo

        $aFont = ['font' => 'arial', 'size' => 10, 'style' => ''];
        $pdf->textBox(2, 49, 210 - 5, 7, $std->obs1, $aFont, 'T', 'L', false, '');
        $pdf->textBox(2, 53, 210 - 5, 7, $std->obs2, $aFont, 'T', 'L', false, '');
        $pdf->textBox(2, 57, 210 - 5, 7, $std->obs3, $aFont, 'T', 'L', false, '');
        $pdf->textBox(2, 61, 210 - 5, 7, $std->obs4, $aFont, 'T', 'L', false, '');
        $y = 68;
    }
    $aFont = ['font' => 'arial', 'size' => 10, 'style' => 'I'];
    $pdf->textBox(2, $y-4, 210 - 7, 297 - $y - 5, 'Peças/Rolos', $aFont, 'T', 'C', false);
    $pdf->textBox(2, $y, 210 - 4, 297 - $y - 5, '', $aFont, 'T', 'L', true);
    $pdf->line(210/2-2, $y, 210/2-2, 297-5);
    $oldy = $y;
    $x = 2;
    $flag = true;
    $lin = 0;
    foreach ($itens as $item) {
        //cabeçalho
        $aFont = ['font' => 'arial', 'size' => 6, 'style' => 'I'];
        $pdf->textBox($x, $y, 10, 7, 'n.', $aFont, 'T', 'L', false);
        $pdf->textBox($x+10, $y, 22, 7, 'sku', $aFont, 'T', 'L', false);
        $pdf->textBox($x+10+22, $y, 20, 7, 'Lote', $aFont, 'T', 'L', false);
        $pdf->textBox($x+10+22+20, $y, 20, 7, 'Peso Liquido', $aFont, 'T', 'L', false);
        $pdf->textBox($x+10+22+20+20, $y, 20, 7, 'Peso Bruto', $aFont, 'T', 'L', false);
        //conteudo
        $pdf->textBox($x, $y, 7, 7, $i, $aFont, 'C', 'R', false);
        $aFont = ['font' => 'arial', 'size' => 8, 'style' => 'B'];
        $pdf->textBox($x+10, $y, 22, 7, $item->sku, $aFont, 'C', 'L', false);
        $pdf->textBox($x+10+22, $y, 20, 7, $item->lote, $aFont, 'C', 'L', false);
        $pdf->textBox($x+10+22+20, $y, 20, 7, "{$item->peso} kg", $aFont, 'C', 'R', false);
        $pdf->textBox($x+10+22+20+20, $y, 20, 7, "{$item->peso_bruto} kg", $aFont, 'C', 'R', false);
        $i++;
        $y += 7;
        $lin++;
        if (($actualPage == 1 && $lin != 32) || ($actualPage > 1 && $lin != 38)) {
            $pdf->line(2, $y, 210 - 2, $y);
        }
        if ($actualPage == 1) {
            if ($lin > 31 && $flag) {
                $y = $oldy;
                $x = 34 + 20 + 20 + 20 + 15;
                $flag = false;
            }
        } else {
            if ($lin > 37 && $flag) {
                $y = $oldy;
                $x = 34 + 20 + 20 + 20 + 15;
                $flag = false;
                $lin = 0;
            }
        }
    }
    $aFont = ['font' => 'arial', 'size' => 7, 'style' => 'I'];
    $texto = "Impresso em " . date('d/m/Y') . " às " . date('H:i:s') . ', Fimacom Industria e Comercio Textil LTDA - Fabric®';
    $pdf->textBox(2, 297 - 4, 210 - 4, 7, $texto, $aFont, 'T', 'L', false);
    $texto = "Pagina:  {$actualPage}/{$totalPages}";
    $pdf->textBox(2, 297 - 4, 210 - 4, 7, $texto, $aFont, 'T', 'R', false);
}
header('Content-Type: application/pdf');
echo $pdf->getPdf();
