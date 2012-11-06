<?php

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

// Imprime na tela o resultado obtido:

echo "Servi&ccedil;o: " . $resposta['servico'] . " <br />";
echo "Valor do Frete: " . $resposta['valor'] . " <br />";
echo "Prazo de Entrega: " . $resposta['prazoEntrega'] . " <br />";
echo "M&atilde;o Pr&oacute;pria: " . $resposta['maoPropria'] . " <br />";
echo "Aviso de Recebimento: " . $resposta['avisoRecebimento'] . " <br />";
echo "Valor Declarado: " . $resposta['valorDeclarado'] . " <br />";
echo "Entrega Domiciliar: " . $resposta['entregaDomiciliar'] . " <br />";
echo "Entrega S&aacute;bado: " . $resposta['entregaSabado'] . " <br />";
echo "Erro: " . $resposta['erro'] . " <br />";
echo "Mensagem de Erro: " . $resposta['msgErro'];
