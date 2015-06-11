<?php

ini_set('default_charset', 'UTF-8');
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

require_once('RsCorreios.php');

$frete = new RsCorreios();

$resposta = $frete
        ->setCepOrigem('88101000')
        ->setCepDestino('01310200')
        ->setLargura('15')
        ->setComprimento('20')
        ->setAltura('5')
        ->setPeso('1')
        ->setFormatoDaEncomenda(RsCorreios::FORMATO_CAIXA)
        ->setServico(empty($tipo) ? RsCorreios::TIPO_PAC : $data['tipo'])
        ->dados();
$estimado = strftime('%d de %B, %Y', strtotime(" + {$resposta['prazoEntrega']} DAYS", time()));
// Imprime na tela o resultado obtido:
echo <<<EOF
Serviço: {$resposta['servico']} <br />
Valor do Frete: {$resposta['valor']} <br />
Prazo de Entrega: {$resposta['prazoEntrega']} <br />
Data Estimada: {$estimado} <br />
Mão Própria: {$resposta['maoPropria']} <br />
Aviso de Recebimento: {$resposta['avisoRecebimento']} <br />
Valor Declarado: {$resposta['valorDeclarado']} <br />
Entrega Domiciliar: {$resposta['entregaDomiciliar']} <br />
Entrega Sábado: {$resposta['entregaSabado']} <br />
Erro: {$resposta['erro']} <br />
Mensagem de Erro: {$resposta['msgErro']} <br />
EOF;
