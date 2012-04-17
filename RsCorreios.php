<?php
/**
 * Este arquivo contém a classe RsCorreios cujo objetivo é se 
 * comunicar com o WS dos Correios para obter cálculo de frete.
 * Esta classe pode ser usada e alterada livremente, desde que
 * citada a fonte.
 * 
 * PHP version 5.3.5
 * 
 * @category Pet_Projects
 * @package  RsCorreios
 * @author   Rodrigo dos Santos <falecom@rodrigodossantos.ws>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link     www.rodrigodossantos.ws
 */

/**
 * Esta classe faz a comunicação com o webservice dos correios
 * para cálculo de frete por SEDEX e PAC, etc.
 * 
 * @category Pet_Projects
 * @package  RsCorreios
 * @author   Rodrigo dos Santos <falecom@rodrigodossantos.ws>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version  Release: <1.0>
 * @link     www.rodrigodossantos.ws
 */
class RsCorreios
{

    protected $cepOrigem;
    protected $cepDestino;
    protected $peso;
    protected $altura;
    protected $comprimento;
    protected $largura;
    protected $maoPropria         = 'N';
    protected $avisoDeRecebimento = 'N';
    protected $formatoDaEncomenda = 1;
    protected $servico;
    protected $valorDeclarado    = 0;
    protected $webServiceUrl     = 'http://ws.correios.com.br';
    protected $webServiceUrlPath = '/calculador/CalcPrecoPrazo.aspx';
    public    $resposta;
    
    /**
     * Filtra a string e retorna somente os números
     * 
     * @param string $string String de entrada
     * 
     * @return string
     */
    private function _somenteNumeros($string)
    {
        return preg_replace("/[^0-9]/", "", $string);
    }

    /**
     * Retorna um valor formatado com duas casas decimais
     * Ex.: 10600
     * 
     * @param string $valor String de entrada
     * 
     * @return string
     */
    private function _formataValor($valor)
    {
        return sprintf("%01.2f", $valor);
    }

    /**
     * Retorna uma string formatada para as medidas de peso
     * para que fique com 3 casas decimais. Ex.: 1.000
     * 
     * @param string $string String de entrada
     * 
     * @return string
     */
    private function _formataPeso($string)
    {
        return sprintf("%01.3f", $string);
    }

    /**
     * Define o CEP de Origem
     * 
     * @param string $cepOrigem CEP de Origem
     * 
     * @return void
     */
    public function setCepOrigem($cepOrigem)
    {
        $this->cepOrigem = $this->_somenteNumeros($cepOrigem);
    }

    /**
     * Obtém o CEP de Origem
     * 
     * @return string
     */
    public function getCepOrigem()
    {
        return $this->cepOrigem;
    }

    /**
     * Define o CEP de Destino
     * 
     * @param string $cepDestino CEP de Destino
     * 
     * @return void
     */
    public function setCepDestino($cepDestino)
    {
        $this->cepDestino = $this->_somenteNumeros($cepDestino);
    }

    /**
     * Obtém o CEP de Destino
     * 
     * @return string
     */
    public function getCepDestino()
    {
        return $this->cepDestino;
    }

     /**
     * Define o Peso da encomenda
     * 
     * @param string $peso Peso da encomenda em Kg
     * 
     * @return void
     */
    public function setPeso($peso)
    {
        $this->peso = $this->_formataPeso($peso);
    }

    /**
     * Obtém o peso da encomenda
     * 
     * @return string
     */
    public function getPeso()
    {
        return $this->peso;
    }


     /**
     * Define a Altura da encomenda
     * 
     * @param string $altura Altura da encomenda em Cm
     * 
     * @return void
     */
    public function setAltura($altura)
    {
        $this->altura = $this->_somenteNumeros($altura);
    }

    /**
     * Obtém a altura da encomenda
     * 
     * @return string
     */
    public function getAltura()
    {
        return $this->altura;
    }

     /**
     * Define o Comprimento da encomenda
     * 
     * @param string $comprimento Comprimento da encomenda em Cm
     * 
     * @return void
     */
    public function setComprimento($comprimento)
    {
        $this->comprimento = $this->_somenteNumeros($comprimento);
    }

    /**
     * Obtém o comprimento da encomenda
     * 
     * @return string
     */
    public function getComprimento()
    {
        return $this->comprimento;
    }


     /**
     * Define a Largura da encomenda
     * 
     * @param string $largura Largura da encomenda em Cm
     * 
     * @return void
     */
    public function setLargura($largura)
    {
        $this->largura = $this->_somenteNumeros($largura);
    }

    /**
     * Obtém a largura da encomenda
     * 
     * @return string
     */
    public function getLargura()
    {
        return $this->largura;
    }

    /**
     * Informa se a encomenda deve ser entregue com a opção "Mão Própria"
     * 
     * @param string $flag S ou N
     * 
     * @return void
     */
    public function setMaoPropria($flag)
    {
        $this->maoPropria = $flag;
    }

    /**
     * Obtém a informação se a encomenda deve ser entregue
     * com a opção "Mão Própria"
     * true = sim; false = não
     * 
     * @return boolean
     */
    public function getMaoPropria()
    {
        return $this->maoPropria;
    }

    /**
     * Informa se o serviço "Aviso de Recebimento" será utilizado
     * S = sim; N = não
     * 
     * @param string $flag S ou N
     * 
     * @return void
     */
    public function setAvisoDeRecebimento($flag)
    {
        $this->avisoDeRecebimento = $flag;
    }

    /**
     * Obtém a informação se a encomenda deve ser entregue 
     * com a opção "Aviso de Recebimento"
     * S = sim; N = não
     * 
     * @return boolean
     */
    public function getAvisoDeRecebimento()
    {
        return $this->avisoDeRecebimento;
    }

     /**
     * Define o Formato da Encomenda (Caixa = 1, Rolo = 2, Envelope = 3)
     * Lança uma exceção caso um valor diferente seja passado como parâmetro
     * 
     * @param string $formato 'caixa', 'rolo' ou 'envelope'
     * 
     * @return void
     */
    public function setFormatoDaEncomenda($formato)
    {
        $whiteList = array(
            'caixa' => '1', 
            'rolo' => '2',
            'envelope' => '3'
            );

        if (array_key_exists($formato, $whiteList)) {
            $this->formatoDaEncomenda = $whiteList[$formato];
        } else {
            throw new InvalidArgumentException("Formato de Encomenda Inválido");
        }

    }

    /**
     * Obtém o Formato da Encomenda
     * 
     * @return int 1 = Caixa, 2 = Rolo, 3 = Envelope
     */
    public function getFormatoDaEncomenda()
    {
        return $this->formatoDaEncomenda;
    }

     /**
     * Define o Serviço de entrega a ser utilizado 
     * (somente as opções sem contrato):
     * 40010 SEDEX sem contrato
     * 40045 SEDEX a Cobrar, sem contrato
     * 40215 SEDEX 10, sem contrato
     * 40290 SEDEX Hoje, sem contrato
     * 41106 PAC sem contrato
     * 
     * Lança uma exceção caso um valor diferente seja passado como parâmetro
     * 
     * @param int $servico Número do serviço dos correios
     * 
     * @return void
     */
    public function setServico($servico)
    {
        $whiteList = array(
            '40010', 
            '40045',
            '40215',
            '40290',
            '41106',
            );

        if (in_array($servico, $whiteList)) {
            $this->servico = $servico;
        } else {
            throw new InvalidArgumentException("Número de Serviço Inválido");
        }

    } 

    /**
     * Obtém o Código do Serviço de Entrega
     * 
     * @return int
     */
    public function getServico()
    {
        return $this->servico;
    }

     /**
     * Define o Valor Declarado da encomenda
     * 
     * @param string $valor Peso da encomenda em Kg
     * 
     * @return void
     */
    public function setValorDeclarado($valor)
    {
        $this->valorDeclarado = $this->_formataValor($valor);
    }

    /**
     * Obtém o valor declarado da encomenda
     * 
     * @return string
     */
    public function getValorDeclarado()
    {
        return $this->valorDeclarado;
    }

    /**
     * Junta a URL de WebService dos Correios com as demais variáveis que 
     * precisam ser enviadas.
     * 
     * @return string URL do WebService + QueryString
     */
    public function getWebServiceUrl()
    {
        $url  = $this->webServiceUrl . $this->webServiceUrlPath;
        $url .= "?nCdEmpresa=";
        $url .= "&sDsSenha=";
        $url .= "&sCepOrigem=" . $this->getCepOrigem();
        $url .= "&sCepDestino=" . $this->getCepDestino();
        $url .= "&nVlPeso=" . $this->getPeso();
        $url .= "&nCdFormato=" . $this->getFormatoDaEncomenda();
        $url .= "&nVlComprimento=" . $this->getComprimento();
        $url .= "&nVlAltura=" . $this->getAltura();
        $url .= "&nVlLargura=" . $this->getLargura();
        $url .= "&sCdMaoPropria=" . $this->getMaoPropria();
        $url .= "&nVlValorDeclarado=" . $this->getValorDeclarado();
        $url .= "&sCdAvisoRecebimento=" . $this->getAvisoDeRecebimento();
        $url .= "&nCdServico=" . $this->getServico();
        $url .= "&nVlDiametro=0";
        $url .= "&StrRetorno=xml";

        return $url;
    }

    /**
     * Conecta-se via cURL a um endereço e retorna a resposta
     * 
     * @param string $url URL que será chamada
     * 
     * @return mix 
     */
    private function _getDataFromUrl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        ob_start();
        curl_exec($ch);
        $response = ob_get_contents();
        ob_end_clean();
        return $response;
    }

    /**
     * Conecta-se aos correios e retorna o XML 
     * com o resultado da consulta do frete
     * 
     * @return string
     */
    public function conecta()
    {
        $url      = $this->getWebServiceUrl();
        $resposta = $this->_getDataFromUrl($url);
        return $resposta;
    }

    /**
     * Trata os dados recebidos pelo WS dos correios
     * 
     * @return object
     */
    public function getDadosFrete()
    {

        $response = $this->conecta();

        $xml = simplexml_load_string($response);

        $resposta                      = array();
        $resposta['servico']           = $xml->cServico->Codigo;
        $resposta['valor']             =  $xml->cServico->Valor;
        $resposta['prazoEntrega']      = $xml->cServico->PrazoEntrega;
        $resposta['maoPropria']        = $xml->cServico->ValorMaoPropria;
        $resposta['avisoRecebimento']  = $xml->cServico->ValorAvisoRecebimento;
        $resposta['valorDeclarado']    = $xml->cServico->ValorValorDeclarado;
        $resposta['entregaDomiciliar'] = $xml->cServico->EntregaDomiciliar;
        $resposta['entregaSabado']     = $xml->cServico->EntregaSabado;
        $resposta['erro']              = $xml->cServico->Erro;
        $resposta['msgErro']           = $xml->cServico->MsgErro;

        return $resposta;
    }

}