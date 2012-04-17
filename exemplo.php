<?php

require_once('RsCorreios.php');

$frete = new RsCorreios();

// Informa o cep de origem
$frete->setCepOrigem("88101000");
// Informa o cep de destino
$frete->setCepDestino("01310200");
// Informa o peso da encomenda
$frete->setPeso("1");
// Informa a altura da encomenda
$frete->setAltura("5");
// Informa o comprimento da encomenda
$frete->setComprimento("20");
// Informa a largura da encomenda
$frete->setLargura("15");
// Informa o serviço. 41106 = PAC
$frete->setServico("41106");

// Consulta o frete
$resposta = $frete->getDadosFrete();

// Imprime na tela o resultado obtido:

echo "Servi&ccedil;o: " . $resposta['servico'] ." <br />";
echo "Valor do Frete: " . $resposta['valor'] ." <br />";
echo "Prazo de Entrega: " . $resposta['prazoEntrega'] ." <br />";
echo "M&atilde;o Pr&oacute;pria: " . $resposta['maoPropria'] ." <br />";
echo "Aviso de Recebimento: " . $resposta['avisoRecebimento'] ." <br />";
echo "Valor Declarado: " . $resposta['valorDeclarado'] ." <br />";
echo "Entrega Domiciliar: " . $resposta['entregaDomiciliar'] ." <br />";
echo "Entrega S&aacute;bado: " . $resposta['entregaSabado'] ." <br />";
echo "Erro: " . $resposta['erro'] ." <br />";
echo "Mensagem de Erro: " . $resposta['msgErro'];

?>