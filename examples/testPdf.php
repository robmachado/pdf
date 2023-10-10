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
    'nfe' => '12345678901234567890123456789012345678901234',
    'data_nfe' => '09/10/2023',
    'ordem' => '12345678901234',
    'parceiro' => 'Fulano de Tal  mmmmmmmMMMMMMMMMMM MMMMMMMM MMMM MMMMMMMM Ltda',
    'representante' => 'JGG Representações',
    'atendente' => 'Margarete',
    'cidade' => 'Porto Alegre',
    'uf' => 'RS',
    'itens' => [
        0 => [
            'item' => 2,
            'sku' => '00000000234039',
            'lote' => '00000000234/A',
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
            'sku' => '00000000234033',
            'lote' => '00000000234/A',
            'codigo' => '2333TA',
            'produto' => 'FEEL SMART',
            'cor' => 'AZUL CALCINHA S00345',
            'peso' => 14.30,
            'peso_bruto' => 14.7,
            'metragem' => 56.63,
            'cuidados' => 'Ake#&u',
            'localizacao' => 'A12',
            'grade' => 'B'
        ],
        2 => [
            'item' => 1,
            'sku' => '00000000234001',
            'lote' => '00000000234/B',
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
            'sku' => '00000000234002',
            'lote' => '00000000234/A',
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
            'sku' => '00000000234003',
            'lote' => '00000000234/C',
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
            'sku' => '00000000234004',
            'lote' => '00000000234/C',
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
            'sku' => '00000000234005',
            'lote' => '00000000234/C',
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
            'sku' => '00000000234006',
            'lote' => '00000000234/A',
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
            'sku' => '00000000234010',
            'lote' => '00000000234/C',
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
            'sku' => '00000000234013',
            'lote' => '00000000234/C',
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
            'sku' => '00000000234017',
            'lote' => '00000000234/C',
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
            'sku' => '00000000234017',
            'lote' => '00000000234/C',
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
            'sku' => '00000000234017',
            'lote' => '00000000234/C',
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
            'sku' => '00000000234017',
            'lote' => '00000000234/C',
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
            'sku' => '00000000234017',
            'lote' => '00000000234/C',
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
            'sku' => '00000000234017',
            'lote' => '00000000234/C',
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
            'sku' => '00000000234017',
            'lote' => '00000000234/C',
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
            'sku' => '00000000234017',
            'lote' => '00000000234/C',
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
            'sku' => '00000000234017',
            'lote' => '00000000234/C',
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
            'sku' => '00000000234017',
            'lote' => '00000000234/C',
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
            'sku' => '00000000234017',
            'lote' => '00000000234/C',
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
            'sku' => '00000000234017',
            'lote' => '00000000234/C',
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
            'sku' => '00000000234017',
            'lote' => '00000000234/C',
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
            'sku' => '00000000234017',
            'lote' => '00000000234/C',
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
$pdf->addPage();
$pdf->setLineWidth(0.1);
$pdf->setTextColor(0,0,0);
$pdf->addFont('textilesym');
//image($file, $x = null, $y = null, $w = 0, $h = 0, $type = '', $link = '')
$logo = 'data://text/plain;base64,' . base64_encode(file_get_contents(__DIR__.'/fimacom_logo.png'));
$pdf->image($logo, 4, 2, 40, 15, 'png');
$aFont = ['font' => 'arial', 'size' => 14, 'style' => ''];
$pdf->textBox(2,2, $pdf->w-4, 15, 'ROMANEIO DE SAÍDA', $aFont, 'T', 'C', false);
$aFont = ['font' => 'arial', 'size' => 12, 'style' => 'I'];
$pdf->textBox(2,8, $pdf->w-4, 20, 'Output Picking List', $aFont, 'T', 'C', false);
$aFont = ['font' => 'arial', 'size' => 7, 'style' => 'I'];
$texto = "{$std->origem}  Atendente: {$std->atendente}";




$pdf->textBox(2,14, $pdf->w-4, 20, $texto, $aFont, 'T', 'C', false);
//code128(float $x, float $y, string $code, float $w, float $h)
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
$pdf->textBox(32,18, $w, 7, 'Destinátario', $aFont, 'T', 'L', true);
$pdf->textBox(32+$w,18, 120, 7, 'Cidade', $aFont, 'T', 'L', true);
$pdf->textBox(32+$w+120,18, 12, 7, 'UF', $aFont, 'T', 'L', true);
$pdf->textBox(2,25, $w, 7, 'Representante', $aFont, 'T', 'L', true);
$pdf->textBox(2+$w,25, $w, 7, 'NFe', $aFont, 'T', 'L', true);
$pdf->textBox(2+$w+$w,25, 30.5, 7, 'Data', $aFont, 'T', 'L', true);

$aFont = ['font' => 'arial', 'size' => 8, 'style' => 'B'];
$pdf->textBox(2,18, 30, 7, $std->ordem, $aFont, 'C', 'C', false);
$pdf->textBox(32,18, $w, 7, $std->parceiro, $aFont, 'C', 'C', false);
$pdf->textBox(32+$w,18, 120, 7, $std->cidade, $aFont, 'C', 'C', false);
$pdf->textBox(32+$w+120,18, 12, 7, $std->uf, $aFont, 'C', 'C', false);
$rep = !empty($std->representante) ? $std->representante : $std->atendente;
$pdf->textBox(2,25, $w, 7, $std->representante, $aFont, 'C', 'C', false);
$pdf->textBox(2+$w,25, $w, 7, !empty($std->nfe) ? $std->nfe : '', $aFont, 'C', 'C', false);
$pdf->textBox(2+$w+$w,25, 30.5, 7, !empty($std->data_nfe) ? $std->data_nfe : '', $aFont, 'C', 'C', false);
$h = 210-34-4;
$pdf->textBox(2,34, 297-4, 24*7, '', $aFont, 'T', 'L', true);

$y = 34;
$count = count($std->itens);
if ($count > 12) {
    $itens = array_chunk($std->itens, 12);
    $pages = $count % 24;
} else {
    $itens[] = $std->itens;
}

$i = 1;
foreach($itens[0] as $item) {
    $aFont = ['font' => 'arial', 'size' => 6, 'style' => 'I'];
    $pdf->textBox(2, $y, 10, 7, 'n.', $aFont, 'T', 'L', false);
    $pdf->textBox(12, $y, 25, 7, 'sku', $aFont, 'T', 'L', false);
    $pdf->textBox(37, $y, 25, 7, 'Código', $aFont, 'T', 'L', false);
    $pdf->textBox(62, $y, 66, 7, 'Produto', $aFont, 'T', 'L', false);
    $pdf->textBox(128, $y, 66, 7, 'Cor', $aFont, 'T', 'L', false);
    $pdf->textBox(297-82-20, $y, 20, 7, 'Peso Bruto', $aFont, 'T', 'L', false);
    $pdf->textBox(297-82, $y, 20, 7, 'Peso', $aFont, 'T', 'L', false);
    $pdf->textBox(297-62, $y, 20, 7, 'Metragem', $aFont, 'T', 'L', false);
    $pdf->textBox(297-42, $y, 40, 7, 'Cuidados', $aFont, 'T', 'L', false);

    $pdf->textBox(2, $y+6, 10, 7, 'Local', $aFont, 'T', 'L', false);
    //$pdf->textBox(37, $y, 25, 7, 'Lote', $aFont, 'T', 'L', false);


    $aFont = ['font' => 'arial', 'size' => 8, 'style' => 'B'];
    $pdf->textBox(2, $y, 10, 7, $i, $aFont, 'C', 'C', false);
    $pdf->textBox(12, $y, 25, 7, $item->sku, $aFont, 'C', 'C', false);
    //$pdf->textBox(37, $y, 25, 7, $item->lote, $aFont, 'C', 'C', false);
    $pdf->textBox(37, $y, 25, 7, $item->codigo, $aFont, 'C', 'C', false);
    $pdf->textBox(62, $y, 66, 7, $item->produto, $aFont, 'C', 'C', true);
    $pdf->textBox(128, $y, 67, 7, $item->cor, $aFont, 'C', 'C', true);
    $pdf->textBox(297-82-20, $y, 20, 7, $item->peso_bruto . ' kg', $aFont, 'C', 'R', true);
    $pdf->textBox(297-82, $y, 20, 7, $item->peso . ' kg', $aFont, 'C', 'R', false);
    $pdf->textBox(297-62, $y, 20, 7, $item->metragem . ' m', $aFont, 'C', 'R', false);
    $aFont = ['font' => 'textilesym', 'size' => 16, 'style' => ''];
    $cuidados = 'Ake#&u';
    $pdf->textBox(297-42, $y, 40, 14, $cuidados, $aFont, 'C', 'C', false);
    $aFont = ['font' => 'arial', 'size' => 8, 'style' => 'B'];
    $pdf->textBox(2, $y+7, 10, 7, $item->localizacao, $aFont, 'C', 'C', false);

    $y += 14;
    $pdf->line(2, $y, 297-2, $y);
    $i++;
}

$aFont = ['font' => 'arial', 'size' => 7, 'style' => 'I'];
$texto = "Impresso em " . date('d/m/Y') . " às " . date('H:i:s') . ', Fimacom Industria e Comercio Textil LTDA - Fabric®' ;
$pdf->textBox(2, 210-5, 297-2, 7, $texto, $aFont, 'T', 'L', false);
$actualPage = 1;
$totalPages = 1;
$texto = "Pagina:  {$actualPage}/{$totalPages}";
$pdf->textBox(2, 210-5, 297-4, 7, $texto, $aFont, 'T', 'R', false);

header('Content-Type: application/pdf');
echo $pdf->getPdf();
