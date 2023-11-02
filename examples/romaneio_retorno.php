<?php
error_reporting(E_ALL);
ini_set('display_errors', 'Off');
require_once '../bootstrap.php';

use Fimacom\Pdf\Pdf;
//tipo = E - entradas é baseado no romaneio de saida (referencia)
//após ser confirmado esse romaneio lança os itens no estoque

$payload = [
    'romaneio' => '123456',
    'ordem_servico' => 23,
    'data' => '11/10/2023',
    'parceiro' => '	SEIREN DO BRASIL IND TEXTIL LTDA',
    'cor' => 'GRIS F00845',
    'processo' => 'MJ2345', //processo na tinturaria
    'codigo' => '2333TA',
    'produto' => 'FEEL FAST TINTO c/ amaciante',
    'total' => 0,
    'peso_liquido' => '',
    'peso_bruto' => '',
    'largura' => 1.52,
    'composicao' => '98.5% POLIÉSTER + 1,5% ELASTANO',
    'gramatura' => 95,
    'rendimento' => 6.93
];
$std = json_decode(json_encode($payload));
date_default_timezone_set('America/Sao_Paulo');
