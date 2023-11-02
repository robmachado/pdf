<?php
error_reporting(E_ALL);
ini_set('display_errors', 'Off');
require_once '../bootstrap.php';

use Fimacom\Pdf\Pdf;

$dados = [
    'romaneio' => str_pad('123', 15, '0', STR_PAD_LEFT),
    'tipo' => 'S',
    'origem' => 'Pedido Encomenda - Venda',
    'data' => '09/10/2023',
    'nfe' => 'N. 1234567 Série 001 de 09/10/2023',
    'peso_liquido' => 220.25,
    'peso_bruto' => 225.56,
    'ordem' => '12345678',
    'parceiro' => 'Fulano de Tal Ltda',
    'representante' => 'JGG Representações',
    'atendente' => 'Margarete',
    'cidade' => 'Porto Alegre',
    'uf' => 'RS',
    'itens' => [
        0 => [
            'item' => 2,
            'sku' => '234039',
            'lote' => '234/A',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'AZUL CALCINHA S00345',
            'peso' => 14.55,
            'peso_bruto' => 15.05,
            'metragem' => 56.65,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        1 => [
            'item' => 2,
            'sku' => '234033',
            'lote' => '234/A',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'AZUL CALCINHA S00345',
            'peso' => 14.30,
            'peso_bruto' => 14.7,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A1234',
            'grade' => 'B'
        ],
        2 => [
            'item' => 1,
            'sku' => '234001',
            'lote' => '234/B',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'MARROM COCO S00123',
            'peso' => 14.30,
            'peso_bruto' => 14.7,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        3 => [
            'item' => 1,
            'sku' => '234002',
            'lote' => '234/A',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'MARROM COCO S00123',
            'peso' => 15.10,
            'peso_bruto' => 15.35,
            'metragem' => 61.91,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        4 => [
            'item' => 3,
            'sku' => '234003',
            'lote' => '234/C',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'PRETO S01111',
            'peso' => 15.00,
            'peso_bruto' => 15.25,
            'metragem' => 61.50,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        5 => [
            'item' => 3,
            'sku' => '234004',
            'lote' => '234/C',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'PRETO S01111',
            'peso' => 14.30,
            'peso_bruto' => 14.55,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        6 => [
            'item' => 3,
            'sku' => '234005',
            'lote' => '234/C',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'PRETO S01111',
            'peso' => 14.30,
            'peso_bruto' => 14.55,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        7 => [
            'item' => 3,
            'sku' => '234006',
            'lote' => '234/A',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'PRETO S01111',
            'peso' => 14.30,
            'peso_bruto' => 14.55,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        8 => [
            'item' => 3,
            'sku' => '234010',
            'lote' => '234/C',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'PRETO S01111',
            'peso' => 14.30,
            'peso_bruto' => 14.55,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        9 => [
            'item' => 3,
            'sku' => '234013',
            'lote' => '234/C',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'PRETO S01111',
            'peso' => 14.30,
            'peso_bruto' => 14.55,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        10 => [
            'item' => 3,
            'sku' => '234017',
            'lote' => '234/C',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'PRETO S01111',
            'peso' => 14.30,
            'peso_bruto' => 14.55,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        11 => [
            'item' => 3,
            'sku' => '234017',
            'lote' => '234/C',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'PRETO S01111',
            'peso' => 14.30,
            'peso_bruto' => 14.55,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        12 => [
            'item' => 3,
            'sku' => '234017',
            'lote' => '234/C',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'PRETO S01111',
            'peso' => 14.30,
            'peso_bruto' => 14.55,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        13 => [
            'item' => 3,
            'sku' => '234017',
            'lote' => '234/C',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'PRETO S01111',
            'peso' => 14.30,
            'peso_bruto' => 14.55,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        14 => [
            'item' => 3,
            'sku' => '234017',
            'lote' => '234/C',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'PRETO S01111',
            'peso' => 14.30,
            'peso_bruto' => 14.55,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        15 => [
            'item' => 3,
            'sku' => '234017',
            'lote' => '234/C',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'PRETO S01111',
            'peso' => 14.30,
            'peso_bruto' => 14.55,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        16 => [
            'item' => 3,
            'sku' => '234017',
            'lote' => '234/C',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'PRETO S01111',
            'peso' => 14.30,
            'peso_bruto' => 14.55,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        17 => [
            'item' => 3,
            'sku' => '234017',
            'lote' => '234/C',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'PRETO S01111',
            'peso' => 14.30,
            'peso_bruto' => 14.55,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        18 => [
            'item' => 3,
            'sku' => '234017',
            'lote' => '234/C',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'PRETO S01111',
            'peso' => 14.30,
            'peso_bruto' => 14.55,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        19 => [
            'item' => 3,
            'sku' => '234017',
            'lote' => '234/C',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'PRETO S01111',
            'peso' => 14.30,
            'peso_bruto' => 14.55,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        20 => [
            'item' => 3,
            'sku' => '234017',
            'lote' => '234/C',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'PRETO S01111',
            'peso' => 14.30,
            'peso_bruto' => 14.55,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        21 => [
            'item' => 3,
            'sku' => '234017',
            'lote' => '234/C',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'PRETO S01111',
            'peso' => 14.30,
            'peso_bruto' => 14.55,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        22 => [
            'item' => 3,
            'sku' => '234017',
            'lote' => '234/C',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'PRETO S01111',
            'peso' => 14.30,
            'peso_bruto' => 14.55,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        23 => [
            'item' => 3,
            'sku' => '234017',
            'lote' => '234/C',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'PRETO S01111',
            'peso' => 14.30,
            'peso_bruto' => 14.55,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        24 => [
            'item' => 3,
            'sku' => '234017',
            'lote' => '234/C',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'PRETO S01111',
            'peso' => 14.30,
            'peso_bruto' => 14.55,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        25 => [
            'item' => 3,
            'sku' => '234017',
            'lote' => '234/C',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'PRETO S01111',
            'peso' => 14.30,
            'peso_bruto' => 14.55,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
    ]
];



$std = json_decode(json_encode($dados));
date_default_timezone_set('America/Sao_Paulo');

$pb = 0;
$pl = 0;
foreach ($std->itens as $item) {
    $pb += $item->peso_bruto;
    $pl += $item->peso;
}

$std->peso_liquido = number_format($pl, 2, ',', '.');
$std->peso_bruto = number_format($pb, 2, ',', '.');

//297 x 210
$maxW = 297 - 2;
$pdf = new Pdf('L', 'mm', 'A4');
$pdf->setCreator('Fabric');
$pdf->setAuthor('Roberto');
$pdf->setMargins(2,2,2);
$pdf->setDrawColor(0,0,0);
$pdf->setFillColor(255,255,255);
$pdf->compress = 9;
$pdf->open();



$subs = [];
$count = count($std->itens);
if ($count > 24) {
    $subs = array_chunk($std->itens, 24);
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
    $pdf->setTextColor(0,0,0);
    $pdf->addFont('textilesym');
    $logo = 'data://text/plain;base64,' . base64_encode(file_get_contents(__DIR__.'/fimacom_logo.png'));
    $pdf->image($logo, 4, 2, 40, 15, 'png');
    $aFont = ['font' => 'arial', 'size' => 14, 'style' => ''];
    $pdf->textBox(2,2, $pdf->w-4, 15, 'ROMANEIO DE VENDA', $aFont, 'T', 'C', false);
    $aFont = ['font' => 'arial', 'size' => 12, 'style' => 'I'];
    $pdf->textBox(2,8, $pdf->w-4, 20, 'Output Picking List', $aFont, 'T', 'C', false);
    $aFont = ['font' => 'arial', 'size' => 7, 'style' => 'I'];
    $texto = "{$std->origem}  Atendente: {$std->atendente}";
    $pdf->textBox(2,14, $pdf->w-4, 20, $texto, $aFont, 'T', 'C', false);
    $pdf->setFillColor(0,0,0);
    $pdf->code128(230, 2, $std->romaneio, 65, 15);
    $pdf->setFillColor(255,255,255);
    $aFont = ['font' => 'arial', 'size' => 7, 'style' => ''];
    $pdf->textBox(200,2, 20, 7, 'Romaneio', $aFont, 'T', 'L', false);
    $pdf->textBox(200,9, 20, 7, 'Data', $aFont, 'T', 'L', false);
    $aFont = ['font' => 'arial', 'size' => 12, 'style' => ''];
    $pdf->textBox(200,3, 20, 7, (int) $std->romaneio, $aFont, 'C', 'L', false);
    $pdf->textBox(200,10, 25, 7, $std->data, $aFont, 'C', 'L', false);
    $w = ($maxW-32)/2;
    $aFont = ['font' => 'arial', 'size' => 6, 'style' => 'I'];
    $pdf->textBox(2,18, 30, 7, 'Pedido Venda', $aFont, 'T', 'L', true);
    $pdf->textBox(32,18, $w+0.5, 7, 'Destinátario', $aFont, 'T', 'L', true);
    $pdf->textBox(297-14-119,18, 119, 7, 'Cidade', $aFont, 'T', 'L', true);
    $pdf->textBox(297-14,18, 12, 7, 'UF', $aFont, 'T', 'L', true);

    $pdf->textBox(2,25, $w, 7, 'Representante', $aFont, 'T', 'L', true);
    $pdf->textBox(2+$w,25, 76.5, 7, 'NFe', $aFont, 'T', 'L', true);
    $pdf->textBox(297-32-30-25,25, 25, 7, 'N. Peças', $aFont, 'T', 'L', true);
    $pdf->textBox(297-32-30,25, 30, 7, 'Peso Líquido', $aFont, 'T', 'L', true);
    $pdf->textBox(297-32,25, 30, 7, 'Peso Bruto', $aFont, 'T', 'L', true);
    $aFont = ['font' => 'arial', 'size' => 8, 'style' => 'B'];
    $pdf->textBox(2,18, 30, 7, $std->ordem, $aFont, 'C', 'C', false);
    $pdf->textBox(32,18, $w, 7, $std->parceiro, $aFont, 'C', 'C', false);
    $pdf->textBox(32+$w,18, 120, 7, $std->cidade, $aFont, 'C', 'C', false);
    $pdf->textBox(32+$w+120,18, 12, 7, $std->uf, $aFont, 'C', 'C', false);
    $rep = !empty($std->representante) ? $std->representante : '';
    $pdf->textBox(2,25, $w, 7, $rep, $aFont, 'C', 'C', false);
    $pdf->textBox(2+$w,25, 76.5, 7, !empty($std->nfe) ? $std->nfe : '', $aFont, 'C', 'C', false);
    $pdf->textBox(297-32-30-25,25, 25, 7, $count, $aFont, 'C', 'C', false);
    $pdf->textBox(297-32-30,25, 30, 7, $std->peso_liquido . ' kg', $aFont, 'C', 'R', false);
    $pdf->textBox(297-32,25, 30, 7, $std->peso_bruto . ' kg', $aFont, 'C', 'R', false);
    $h = 210-34-4;
    $pdf->textBox(2,34, 297-4, 24*7, '', $aFont, 'T', 'L', true);
    $y = 34;
    foreach($itens as $item) {
        $aFont = ['font' => 'arial', 'size' => 6, 'style' => 'I'];
        $pdf->textBox(2, $y, 7, 7, 'n.', $aFont, 'T', 'L', false);
        $pdf->textBox(9, $y, 7, 7, 'Item', $aFont, 'T', 'L', false);
        $pdf->textBox(16, $y, 25, 7, 'sku', $aFont, 'T', 'L', false);
        $pdf->textBox(41, $y, 25, 7, 'Lote', $aFont, 'T', 'L', false);
        $pdf->textBox(66, $y, 10, 7, 'Local', $aFont, 'T', 'L', false);
        $pdf->textBox(76, $y, 23, 7, 'Código', $aFont, 'T', 'L', false);
        $pdf->textBox(99, $y, 69, 7, 'Produto', $aFont, 'T', 'L', false);
        $pdf->textBox(168, $y, 69, 7, 'Cor', $aFont, 'T', 'L', false);
        $pdf->textBox(297 - 40 - 20, $y, 20, 7, 'Peso', $aFont, 'T', 'L', false);
        $pdf->textBox(297 - 40, $y, 38, 7, 'Cuidados', $aFont, 'T', 'L', false);

        $aFont = ['font' => 'arial', 'size' => 8, 'style' => 'B'];
        $pdf->textBox(2, $y, 7, 7, $i, $aFont, 'C', 'R', false);
        $pdf->textBox(9, $y, 7, 7, $item->item, $aFont, 'C', 'R', false);
        $pdf->textBox(16, $y, 25, 7, $item->sku, $aFont, 'C', 'C', false);
        $pdf->textBox(41, $y, 25, 7, $item->lote, $aFont, 'C', 'C', false);
        $pdf->textBox(66, $y, 10, 7, $item->localizacao, $aFont, 'C', 'L', false);
        $pdf->textBox(76, $y, 23, 7, $item->codigo, $aFont, 'C', 'L', false);
        $pdf->textBox(99, $y, 69, 7, $item->produto, $aFont, 'C', 'L', false);
        $pdf->textBox(168, $y, 69, 7, $item->cor, $aFont, 'C', 'L', false);
        $pdf->textBox(297 - 40 - 20, $y, 20, 7, $item->peso . ' kg', $aFont, 'C', 'R', false);
        if (!empty($item->cuidados)) {
            $aFont = ['font' => 'textilesym', 'size' => 16, 'style' => ''];
            $pdf->textBox(297 - 40, $y, 38, 7, $item->cuidados, $aFont, 'C', 'C', false);
        }
        $y += 7;
        $pdf->line(2, $y, 297 - 2, $y);
        $i++;
    }
    $aFont = ['font' => 'arial', 'size' => 7, 'style' => 'I'];
    $texto = "Impresso em " . date('d/m/Y') . " às " . date('H:i:s') . ', Fimacom Industria e Comercio Textil LTDA - Fabric®' ;
    $pdf->textBox(2, 210-5, 297-2, 7, $texto, $aFont, 'T', 'L', false);
    $texto = "Pagina:  {$actualPage}/{$totalPages}";
    $pdf->textBox(2, 210-5, 297-4, 7, $texto, $aFont, 'T', 'R', false);
}
header('Content-Type: application/pdf');
echo $pdf->getPdf();
