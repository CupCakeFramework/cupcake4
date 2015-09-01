<?php

namespace Cupcake\Renderer;

use Cupcake\Url\UrlGenerator;
use DateTime;
use Exception;

/**
 * Cupcake's rendering engine
 *
 * @author Ricardo Fiorani
 */
class CupRenderer implements CupRendererInterface
{

    private $pastaTemplates;
    private $pastaViews;
    private $template;
    private $tituloSite;
    private $pastaSysViews;

    /**
     *
     * @var UrlGenerator
     */
    public $urlGenerator;

    /**
     * @param $pastaTemplates
     * @param $pastaViews
     * @param $tituloSite
     * @param UrlGenerator $urlGenerator
     */
    function __construct($pastaTemplates, $pastaViews, $tituloSite, UrlGenerator $urlGenerator)
    {
        $this->pastaTemplates = $pastaTemplates;
        $this->pastaViews = $pastaViews;
        $this->pastaSysViews = $this->pastaViews . 'sys/';
        $this->tituloSite = $tituloSite;
        $this->urlGenerator = $urlGenerator;
    }


    /**
     * @return UrlGenerator
     */
    function getUrlGenerator()
    {
        return $this->urlGenerator;
    }

    /**
     * @return mixed
     */
    function getPastaTemplates()
    {
        return $this->pastaTemplates;
    }

    /**
     * @return mixed
     */
    function getPastaViews()
    {
        return $this->pastaViews;
    }

    /**
     * @return mixed
     */
    function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return mixed
     */
    function getTituloSite()
    {
        return $this->tituloSite;
    }

    /**
     * @param $pastaTemplates
     */
    function setPastaTemplates($pastaTemplates)
    {
        $this->pastaTemplates = $pastaTemplates;
    }

    /**
     * @param $pastaViews
     */
    function setPastaViews($pastaViews)
    {
        $this->pastaViews = $pastaViews;
    }

    /**
     * @param $template
     */
    function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @param $tituloSite
     */
    function setTituloSite($tituloSite)
    {
        $this->tituloSite = $tituloSite;
    }

    /**
     * @param $nomeView
     * @param array $variaveis
     * @param bool|false $retornar
     * @return string
     * @throws Exception
     */
    public function renderizar($nomeView, array $variaveis = [], $retornar = false)
    {
        $view = $this->pastaViews . $nomeView . '.php';
        if (!file_exists($view)) {
            $view = $this->pastaSysViews . $nomeView . '.php';
            if (!file_exists($view)) {
                throw new Exception(sprintf('A view %s não foi encontrada', $view));
            }
        }
        $template = $this->pastaTemplates . $this->template . '.php';
        if (!file_exists($template)) {
            throw new Exception(sprintf('O template %s não foi encontrado', $template));
        }
        $variaveis['conteudo'] = $this->renderView($view, $variaveis, true);

        return $this->renderView($template, $variaveis, $retornar);
    }

    /**
     * @param $nomeView
     * @param array $variaveis
     * @param bool|false $retornar
     * @return string
     * @throws Exception
     */
    public function renderizarParcial($nomeView, array $variaveis = [], $retornar = false)
    {
        $view = $this->pastaViews . $nomeView . '.php';
        if (!file_exists($view)) {
            $view = $this->pastaSysViews . $nomeView . '.php';
            if (!file_exists($view)) {
                throw new Exception(sprintf('A view %s não foi encontrada', $view));
            }
        }

        return $this->renderView($view, $variaveis, $retornar);
    }

    /**
     * @param $arquivoParaRenderizar
     * @param array $variaveis
     * @param bool|false $retornar
     * @return string
     */
    public function renderView($arquivoParaRenderizar, $variaveis = [], $retornar = false)
    {
        ob_start();
        if (!empty($variaveis) && is_array($variaveis)) {
            extract($variaveis);
        }
        include($arquivoParaRenderizar);
        $retorno = ob_get_contents();
        ob_end_clean();
        if ($retornar) {
            return $retorno;
        } else {
            print $retorno;
        }
    }

    /**
     * Gera uma URL para o site.
     * @param array $caminho Caminho cada item corresponde a um diretório. Ex: array('caminho','parametro') = http://seuprojeto.com/caminho/parametro/
     * @param mixed $urlBase A BaseUrl para gerar a url. Por padrão é utilizado a constante $this->baseUrl.
     * @return string A Url Gerada
     */
    public function url($caminho = '', $urlBase = '')
    {
        return $this->getUrlGenerator()->generateUrl($caminho, $urlBase);
    }


    /**
     * @return string
     */
    public function getPublicAssetsUrl()
    {
        return $this->url(['public_assets']);
    }


    /**
     * @param DateTime $data
     * @return string
     */
    public function traduzirMes(DateTime $data)
    {
        switch ($data->format('m')) {
            case 1:
                return "Janeiro";
            case 2:
                return "Fevereiro";
            case 3:
                return "Março";
            case 4:
                return "Abril";
            case 5:
                return "Maio";
            case 6:
                return "Junho";
            case 7:
                return "Julho";
            case 8:
                return "Agosto";
            case 9:
                return "Setembro";
            case 10:
                return "Outubro";
            case 11:
                return "Novembro";
            case 12:
                return "Dezembro";
        }
    }

    /**
     * @param $string
     * @return string|void
     */
    public function hideEmailFromString($string)
    {
        if (empty($string)) {
            return;
        }
        $pedacos = explode('@', $string);
        $domain = '@' . end($pedacos);
        $stringOcultada = substr_replace(reset($pedacos), '*********', 5);

        return $stringOcultada . $domain;
    }

    /**
     * @param $string
     * @return mixed|string|void
     */
    public function hideNameFromString($string)
    {
        if (empty($string)) {
            return;
        }
        $pedacos = explode(' ', $string);
        $nome = reset($pedacos);
        unset($pedacos[0]);
        foreach ($pedacos as $p) {
            $nome .= ' ' . substr($p, 0, 1) . str_repeat('*', strlen($p) - 3) . substr($p, -2);
        }

        return $nome;
    }

    /**
     * @param $string
     * @return string|void
     */
    public function hideTelephoneNumberFromString($string)
    {
        if (empty($string)) {
            return;
        }

        return substr_replace($string, str_repeat('*', strlen($string) - 5), 3) . substr($string, -2);
    }

    /**
     * @param $string
     * @param int $size
     * @return mixed|void
     */
    public function hideString($string, $size = 5)
    {
        if (empty($string)) {
            return;
        }
        if ($size <= 0) {
            $size = 5;
        }

        return substr_replace($string, str_repeat('*', $size), $size);
    }

}
