<?php
/**
 * Testes da classe RsCorreios
 * 
 * PHP version 5.3.5
 * 
 * @category Pet_Projects
 * @package  RsCorreios
 * @author   Rodrigo dos Santos <falecom@rodrigodossantos.ws>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version  Release: <1.0>
 * @link     www.rodrigodossantos.ws
 */

require_once '../RsCorreios.php';

/**
 * Testes unitários da classe RsCorreios
 * 
 * @category Pet_Projects
 * @package  RsCorreios
 * @author   Rodrigo dos Santos <falecom@rodrigodossantos.ws>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version  Release: <1.0>
 * @link     www.rodrigodossantos.ws
 */
class RsCorreiosTest extends PHPUnit_Framework_Testcase
{

    protected $frete;
    protected $webServiceUrl;
    protected $resposta;
 
    /**
     * Setup para iniciação de cada teste
     * 
     * @return void
     */
    public function setUp() 
    {
        parent::setUp();

        $this->frete          = new RsCorreios();
        $this->webServiceUrl  = "http://ws.correios.com.br/";
        $this->webServiceUrl .= "calculador/CalcPrecoPrazo.aspx";
        $this->resposta       = '<?xml version="1.0" encoding="ISO-8859-1" ?>';
        $this->resposta      .= '<Servicos><cServico>';
        $this->resposta      .= '<Codigo>41106</Codigo>';
        $this->resposta      .= '<Valor>10,50</Valor>';
        $this->resposta      .= '<PrazoEntrega>3</PrazoEntrega>';
        $this->resposta      .= '<ValorMaoPropria>0,00</ValorMaoPropria>';
        $this->resposta      .= '<ValorAvisoRecebimento>';
        $this->resposta      .= '0,00</ValorAvisoRecebimento>';
        $this->resposta      .= '<ValorValorDeclarado>0,00</ValorValorDeclarado>';
        $this->resposta      .= '<EntregaDomiciliar>S</EntregaDomiciliar>';
        $this->resposta      .= '<EntregaSabado>N</EntregaSabado>';
        $this->resposta      .= '<Erro>0</Erro>';
        $this->resposta      .= '<MsgErro></MsgErro>';
        $this->resposta      .= '</cServico></Servicos>';
    }

    /** 
    * Verifica se o get e set do CEP de Origem estão funcionando
    * 
    * @test
    * 
    * @return void
    */
    public function verificaGetterAndSetterDoCepDeOrigem()
    {
        $cepOrigem = "88101000";
        $this->frete->setCepOrigem($cepOrigem);
        $this->assertEquals($cepOrigem, $this->frete->getCepOrigem());
    }

    /**
     * Verifica se o get e set do CEP de Destino estão funcionando
     * 
     * @test
     * 
     * @return void
     */
    public function verificaGetterAndSetterDoCepDeDestino()
    {
        $cepDestino = '88101010';
        $this->frete->setCepDestino($cepDestino);
        $this->assertEquals($cepDestino, $this->frete->getCepDestino());
    }

    /**
     * Testa o filtro que retorna somente os números digitados para o CEP
     * 
     * @test
     * 
     * @return void
     */
    public function verificaFiltroDeCepParaRetornarSomenteNumeros()
    {
        $cepFormatado = '88000-000';
        $this->frete->setCepDestino($cepFormatado);
        $this->assertEquals('88000000', $this->frete->getCepDestino());

        $cepFormatado2 = '88.134-400';
        $this->frete->setCepOrigem($cepFormatado2);
        $this->assertEquals('88134400', $this->frete->getCepOrigem());

    }

    /** 
    * Verifica se os get e set do peso estão funcionando
    * 
    * @test
    * 
    * @return void
    */
    public function verificaGetterAndSetterDoPeso() 
    {
        $peso = '0.500';
        $this->frete->setPeso($peso);
        $this->assertEquals($peso, $this->frete->getPeso());
    }

    
    /** 
    * Testa a formatação do peso para o três casas decimais
    * 
    * @test
    * 
    * @return void
    */
    public function verificaSeEstaFormatandoOPeso() 
    {
        $peso = "1.10";
        $this->frete->setPeso($peso);
        $this->assertEquals("1.100", $this->frete->getPeso());
    }

    /** 
    * Testa a formatação do valor declarado para duas casas decimais
    * 
    * @test
    * 
    * @return void
    */
    public function verificaSeEstaFormatandoOValorDeclarado() 
    {
        $valor = "102.1";
        $this->frete->setValorDeclarado($valor);
        $this->assertEquals("102.10", $this->frete->getValorDeclarado());
    }

    /** 
    * Verifica se os get e set da altura estão funcionando
    * 
    * @test
    * 
    * @return void
    */
    public function verificaGetterAndSetterDaAltura()
    {
        $altura = '35';
        $this->frete->setAltura($altura);
        $this->assertEquals($altura, $this->frete->getAltura());
    }

    /** 
    * Verifica se os get e set do comprimento estão funcionando
    * 
    * @test
    * 
    * @return void
    */
    public function verificaGetterAndSetterDoComprimento()
    {
        $comprimento = '42';
        $this->frete->setComprimento($comprimento);
        $this->assertEquals($comprimento, $this->frete->getComprimento());
    }


    /** 
    * Verifica se os get e set da largura estão funcionando
    * 
    * @test
    * 
    * @return void
    */
    public function verificaGetterAndSetterDaLargura()
    {
        $largura = '12';
        $this->frete->setLargura($largura);
        $this->assertEquals($largura, $this->frete->getLargura());
    }

    /** 
    * Verifica se os get e set 'Aviso de Recebimento' está funcionando
    * 
    * @test
    * 
    * @return void
    */
    public function verificaGetterAndSetterDoAvisoDeRecebimento()
    {
        $avisoDeRecebimento = 'S';
        $this->frete->setAvisoDeRecebimento($avisoDeRecebimento);
        $this->assertEquals($avisoDeRecebimento,
            $this->frete->getAvisoDeRecebimento());

        $avisoDeRecebimento = 'N';
        $this->frete->setAvisoDeRecebimento($avisoDeRecebimento);
        $this->assertEquals($avisoDeRecebimento,
            $this->frete->getAvisoDeRecebimento());

    }


    /** 
    * Verifica se os get e set 'mão própria' está funcionando
    * 
    * @test
    * 
    * @return void
    */
    public function verificaGetterAndSetterMaoPropria()
    {
        $maoProria = 'S';
        $this->frete->setMaoPropria($maoProria);
        $this->assertEquals($maoProria, $this->frete->getMaoPropria());

        $maoProria = 'N';
        $this->frete->setMaoPropria($maoProria);
        $this->assertEquals($maoProria, $this->frete->getMaoPropria());
    }

    /**
     * Verifica se o get e set do Formato da Encomenda está funcionando
     * 
     * @test
     * 
     * @return void
     */
    public function verificaGetterAndSetterDoFormatoDaEncomenda()
    {
        $formato = 'caixa';
        $this->frete->setFormatoDaEncomenda($formato);
        $this->assertEquals(1, $this->frete->getFormatoDaEncomenda());

        $formato = 'rolo';
        $this->frete->setFormatoDaEncomenda($formato);
        $this->assertEquals(2, $this->frete->getFormatoDaEncomenda());

        $formato = 'envelope';
        $this->frete->setFormatoDaEncomenda($formato);
        $this->assertEquals(3, $this->frete->getFormatoDaEncomenda());
    }

    /**
     * Verifica se uma exception é lançada quando se tenta passar um 
     * formato de encomenda inválido como parâmetro
     * 
     * @test
     * @expectedException InvalidArgumentException
     * 
     * @return void;
     */
    public function verificaExcecaoDeFormatoDeEncomendaInvalido()
    {
        $formato = 'anatomico';
        $this->frete->setFormatoDaEncomenda($formato);
    }

    /**
     * Verifica se o get e set do Serviço está funcionando
     * 
     * @test
     * 
     * @return void
     */
    public function verificaGetterAndSetterDoServico()
    {
        $servico = '40010'; // sedex
        $this->frete->setServico($servico);
        $this->assertEquals($servico, $this->frete->getServico());

        $servico = '40045'; // sedex a cobrar
        $this->frete->setServico($servico);
        $this->assertEquals($servico, $this->frete->getServico());

        $servico = '40215'; // sedex 10
        $this->frete->setServico($servico);
        $this->assertEquals($servico, $this->frete->getServico());

        $servico = '40290'; // sedex hoje
        $this->frete->setServico($servico);
        $this->assertEquals($servico, $this->frete->getServico());

        $servico = '41106'; // pac
        $this->frete->setServico($servico);
        $this->assertEquals($servico, $this->frete->getServico());
    }

    /**
     * Verifica se uma exception é lançada quando se tenta passar um 
     * código de serviço inválido como parâmetro
     * 
     * @test
     * @expectedException InvalidArgumentException
     * 
     * @return void;
     */
    public function verificaExcecaoDeCodigoDeServicoInvalido()
    {
        $servico = '12345';
        $this->frete->setServico($servico);
    }

    /**
     * lasjflksd
     * 
     * @test
     * 
     * @return void;
     */
    public function verificaSeAUrlDoWebServiceEstaCorretamenteFormatada()
    {
        $this->frete->setCepOrigem("88101000");
        $this->frete->setCepDestino("88134400");
        $this->frete->setPeso("1");
        $this->frete->setAltura("5");
        $this->frete->setComprimento("20");
        $this->frete->setLargura("15");
        $this->frete->setServico("41106");


        $url  = $this->webServiceUrl;
        $url .= "?nCdEmpresa=";
        $url .= "&sDsSenha=";
        $url .= "&sCepOrigem=" . $this->frete->getCepOrigem();
        $url .= "&setCepDestino=" . $this->frete->getCepDestino();
        $url .= "&nVlPeso=" . $this->frete->getPeso();
        $url .= "&nCdFormato=" . $this->frete->getFormatoDaEncomenda();
        $url .= "&nVlComprimento=" . $this->frete->getComprimento();
        $url .= "&nVlAltura=" . $this->frete->getAltura();
        $url .= "&nVlLargura=" . $this->frete->getLargura();
        $url .= "&sCdMaoPropria=" . $this->frete->getMaoPropria();
        $url .= "&nVlValorDeclarado=" . $this->frete->getValorDeclarado();
        $url .= "&sCdAvisoRecebimento=" . $this->frete->getAvisoDeRecebimento();
        $url .= "&nCdServico=" . $this->frete->getServico();
        $url .= "&nVlDiametro=0";
        $url .= "&StrRetorno=xml";

        $this->assertEquals($url, $this->frete->getWebServiceUrl());
    }

    /**
     * Verifica se a resposta do webservice dos correios foi recebida
     * 
     * @test
     * 
     * @return void;
     */
    public function verificaSeARespostaDoWebServiceFoiRecebidaCorretamente()
    {        
        $client = $this->getMock('RsCorreios', array('conecta'));
        $client->expects($this->once())
            ->method('conecta')
            ->will($this->returnValue($this->resposta));

        $this->assertEquals($this->resposta, $client->conecta());
    }

    /**
     * Verifica se todos os dados foram retornados após a consulta do frete
     * 
     * @test
     * 
     * @return void
     */
    public function verificaSeTodosOsDadosForamRetornadosAposAConsultaDoFrete()
    {

        $this->frete->setCepOrigem("88101000");
        $this->frete->setCepDestino("88134400");
        $this->frete->setPeso("1");
        $this->frete->setAltura("5");
        $this->frete->setComprimento("20");
        $this->frete->setLargura("15");
        $this->frete->setServico("41106");

        $respostaControle = simplexml_load_string($this->resposta);

        $arrayResposta                      = array();
        $arrayResposta['servico']           = '41106';
        $arrayResposta['valor']             = '10,50';
        $arrayResposta['prazoEntrega']      = '3';
        $arrayResposta['maoPropria']        = '0,00';
        $arrayResposta['avisoRecebimento']  = '0,00';
        $arrayResposta['valorDeclarado']    = '0,00';
        $arrayResposta['entregaDomiciliar'] = 'S';
        $arrayResposta['entregaSabado']     = 'N';
        $arrayResposta['erro']              = '0';
        $arrayResposta['msgErro']           = '';

        $client = $this->getMock('RsCorreios', array('getDadosFrete'));
        $client->expects($this->once())
            ->method('getDadosFrete')
            ->will($this->returnValue($arrayResposta));

        $resposta = $client->getDadosFrete();

        // O código do serviço foi retornado?
        $this->assertEquals($respostaControle->cServico->Codigo,
            $resposta['servico']);
        // Valor do serviço
        $this->assertEquals($respostaControle->cServico->Valor,
            $resposta['valor']);
        // Prazo de Entrega
        $this->assertEquals($respostaControle->cServico->PrazoEntrega,
            $resposta['prazoEntrega']);
        // Mão Própria
        $this->assertEquals($respostaControle->cServico->ValorMaoPropria,
            $resposta['maoPropria']);
        // Aviso de recebimento
        $this->assertEquals($respostaControle->cServico->ValorAvisoRecebimento,
            $resposta['avisoRecebimento']);
        // Valor declarado
        $this->assertEquals($respostaControle->cServico->ValorValorDeclarado,
            $resposta['valorDeclarado']);
        // Entrega domiciliar
        $this->assertEquals($respostaControle->cServico->EntregaDomiciliar,
            $resposta['entregaDomiciliar']);
        // Entrega sábado
        $this->assertEquals($respostaControle->cServico->EntregaSabado, 
            $resposta['entregaSabado']);
        // Erro ?
        $this->assertEquals($respostaControle->cServico->Erro, 
            $resposta['erro']);
        // Mensagem de Erro ?
        $this->assertEquals($respostaControle->cServico->MsgErro, 
            $resposta['msgErro']);
    }

}