<?php
/**
 * Totem — Sonho dá Sorte (versão PHP)
 * Injeta configuração no index.html. Se existir resultado.json na mesma pasta,
 * os valores dele sobrescrevem os padrões (ex.: número real do sorteio).
 *
 * Exemplo de resultado.json:
 * { "sorteioNum": 115, "numero": "482913", "ganhadores": 187,
 *   "drawTime": "10:00", "nextDrawLabel": "Hoje às 11h", "nextDrawSec": 1488 }
 */

header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');
date_default_timezone_set('America/Recife');

$config = [
    'drawIntervalSec' => 600,   // produção: 10 min entre sorteios na tela
    'drawTime'        => '10:00',
    'nextDrawLabel'   => 'Hoje às 11h',
    'qrUrl'           => '',    // ex.: 'qr.png'
    'logoUrl'         => '',    // ex.: 'logo.png'
    'reloadMinutes'   => 60,
];

$arquivo = __DIR__ . '/resultado.json';
if (is_file($arquivo)) {
    $dados = json_decode(file_get_contents($arquivo), true);
    if (is_array($dados)) {
        $config = array_merge($config, $dados);
    }
}

$html = file_get_contents(__DIR__ . '/index.html');
$script = '<script>window.TOTEM_CONFIG = '
        . json_encode($config, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP)
        . ';</script>';

echo str_replace('<!--TOTEM_CONFIG-->', $script, $html);
