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

    const TIPO_SEDEX          = 40010;
    const TIPO_SEDEX_A_COBRAR = 40045;
    const TIPO_SEDEX_10       = 40215;
    const TIPO_SEDEX_HOJE     = 40290;
    const TIPO_PAC            = 41106;
    const FORMATO_CAIXA       = '1';
    const FORMATO_ROLO        = '2';
    const FORMATO_ENVELOPE    = '3';

    protected $cepOrigem;
    protected $cepDestino;
    protected $peso;
    protected $altura;
    protected $comprimento;
    protected $largura;
    protected $maoPropria         = false;
    protected $avisoDeRecebimento = false;
    protected $formatoDaEncomenda = 1;
    protected $servico;
    protected $valorDeclarado     = 0;
    protected $webServiceUrl      = 'http://ws.correios.com.br';
    protected $webServiceUrlPath  = '/calculador/CalcPrecoPrazo.asmx/CalcPrecoPrazo';
    public $resposta;

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
     * @return RsCorreios
     */
    public function setCepOrigem($cepOrigem)
    {
        $this->cepOrigem = $this->_somenteNumeros($cepOrigem);

        return $this;
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
     * @return RsCorreios
     */
    public function setCepDestino($cepDestino)
    {
        $this->cepDestino = $this->_somenteNumeros($cepDestino);

        return $this;
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
     * @return RsCorreios
     */
    public function setPeso($peso)
    {
        $this->peso = $this->_formataPeso($peso);

        return $this;
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
     * @return RsCorreios
     */
    public function setAltura($altura)
    {
        $this->altura = $this->_somenteNumeros($altura);

        return $this;
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
     * @return RsCorreios
     */
    public function setComprimento($comprimento)
    {
        $this->comprimento = $this->_somenteNumeros($comprimento);

        return $this;
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
     * @return RsCorreios
     */
    public function setLargura($largura)
    {
        $this->largura = $this->_somenteNumeros($largura);

        return $this;
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
     * @param boolean $flag true = sim | false = não
     *
     * @return RsCorreios
     */
    public function setMaoPropria($flag)
    {
        $this->maoPropria = $flag;

        return $this;
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
        return $this->maoPropria ? 'S' : 'N';
    }

    /**
     * Informa se o serviço "Aviso de Recebimento" será utilizado
     * S = sim; N = não
     *
     * @param boolean $flag S ou N
     *
     * @return RsCorreios
     */
    public function setAvisoDeRecebimento($flag)
    {
        $this->avisoDeRecebimento = (bool) $flag;

        return $this;
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
        return $this->avisoDeRecebimento ? 'S' : 'N';
    }

    /**
     * Define o Formato da Encomenda (Caixa = 1, Rolo = 2, Envelope = 3)
     * Lança uma exceção caso um valor diferente seja passado como parâmetro
     *
     * @param int $formato Usar as constantes RsCorreios::FORMATO_*
     *
     * @throws InvalidArgumentException
     * @return RsCorreios
     */
    public function setFormatoDaEncomenda($formato)
    {
        $whiteList = array(
            self::FORMATO_CAIXA => true,
            self::FORMATO_ROLO => true,
            self::FORMATO_ENVELOPE => true
        );

        if (isset($whiteList[$formato])) {
            $this->formatoDaEncomenda = $formato;
        } else {
            throw new InvalidArgumentException("Formato de Encomenda Inválido");
        }

        return $this;
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
     * @param int $servico Usar as constantes RsCorreios::TIPO_*
     *
     * @throws InvalidArgumentException
     * @return RsCorreios
     */
    public function setServico($servico)
    {
        $whiteList = array(
            self::TIPO_PAC => true,
            self::TIPO_SEDEX => true,
            self::TIPO_SEDEX_10 => true,
            self::TIPO_SEDEX_A_COBRAR => true,
            self::TIPO_SEDEX_HOJE => true,
        );

        if (isset($whiteList[$servico])) {
            $this->servico = $servico;
        } else {
            throw new InvalidArgumentException("Número de Serviço Inválido");
        }

        return $this;
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
     * @return RsCorreios
     */
    public function setValorDeclarado($valor)
    {
        $this->valorDeclarado = $this->_formataValor($valor);

        return $this;
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
        $url = $this->webServiceUrl . $this->webServiceUrlPath . '?';

        $params = array(
            "nCdEmpresa" => '',
            "sDsSenha" => '',
            "nCdServico" => $this->getServico(),
            "sCepOrigem" => $this->getCepOrigem(),
            "sCepDestino" => $this->getCepDestino(),
            "nVlPeso" => $this->getPeso(),
            "nCdFormato" => $this->getFormatoDaEncomenda(),
            "nVlComprimento" => $this->getComprimento(),
            "nVlAltura" => $this->getAltura(),
            "nVlLargura" => $this->getLargura(),
            "nVlDiametro" => '0',
            "sCdMaoPropria" => $this->getMaoPropria(),
            "nVlValorDeclarado" => $this->getValorDeclarado(),
            "sCdAvisoRecebimento" => $this->getAvisoDeRecebimento(),
            "StrRetorno" => 'XML'
        );

        return $url . http_build_query($params, '', '&');
    }

    /**
     * Conecta-se via cURL a um endereço e retorna a resposta
     *
     * @param string $url URL que será chamada
     *
     * @return array 0 => Status Code 1 => Response
     */
    private function _getDataFromUrl($url)
    {
        $ch       = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        ob_start();
        curl_exec($ch);
        $response = ob_get_contents();
        ob_end_clean();

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return array($httpCode, $response);
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
     * @throws Exception
     * @return array
     */
    public function dados()
    {

        $response = $this->conecta();

        // return $response;

        $resposta = array();

        if ($response[0] === 200) {

            $xml = simplexml_load_string($response[1]);

            if ($xml !== false) {
                $servico = $xml->Servicos->cServico[0];

                $resposta['servico']           = (string) $servico->Codigo;
                $resposta['valor']             = str_replace(',', '.',
                    (string) $servico->Valor);
                $resposta['prazoEntrega']      = (string) $servico->PrazoEntrega;
                $resposta['maoPropria']        = (string) $servico->ValorMaoPropria;
                $resposta['avisoRecebimento']  = (string) $servico->ValorAvisoRecebimento;
                $resposta['valorDeclarado']    = (string) $servico->ValorValorDeclarado;
                $resposta['entregaDomiciliar'] = (string) $servico->EntregaDomiciliar;
                $resposta['entregaSabado']     = (string) $servico->EntregaSabado;
                $resposta['erro']              = (string) $servico->Erro;
                $resposta['msgErro']           = (string) $servico->MsgErro;
            } else {
                throw new Exception('Resposta XML malformada');
            }
        } else {
            $match    = preg_match('/(Missing parameter: [^\.]+)\./', $response[1],
                $matches);
            $resposta = array(
                'erro' => $response[0],
                'msgErro' => $match ? $matches[1] : $response[1]
            );
        }
        return $resposta;
    }

}