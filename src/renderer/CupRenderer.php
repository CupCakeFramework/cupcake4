<?php

/**
 * Motor Renderizador do CupCake
 *
 * @author Ricardo Fiorani
 */
class CupRenderer {

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

    function __construct($pastaTemplates, $pastaViews, $tituloSite, UrlGenerator $urlGenerator) {
        $this->pastaTemplates = $pastaTemplates;
        $this->pastaViews = $pastaViews;
        $this->pastaSysViews = $this->pastaViews . 'sys/';
        $this->tituloSite = $tituloSite;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * 
     * @return UrlGenerator
     */
    function getUrlGenerator() {
        return $this->urlGenerator;
    }

    function getPastaTemplates() {
        return $this->pastaTemplates;
    }

    function getPastaViews() {
        return $this->pastaViews;
    }

    function getTemplate() {
        return $this->template;
    }

    function getTituloSite() {
        return $this->tituloSite;
    }

    function setPastaTemplates($pastaTemplates) {
        $this->pastaTemplates = $pastaTemplates;
    }

    function setPastaViews($pastaViews) {
        $this->pastaViews = $pastaViews;
    }

    function setTemplate($template) {
        $this->template = $template;
    }

    function setTituloSite($tituloSite) {
        $this->tituloSite = $tituloSite;
    }

    public function renderizar($nomeView, array $variaveis = array(), $retornar = false) {
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

    public function renderizarParcial($nomeView, array $variaveis = array(), $retornar = false) {
        $view = $this->pastaViews . $nomeView . '.php';
        if (!file_exists($view)) {
            $view = $this->pastaSysViews . $nomeView . '.php';
            if (!file_exists($view)) {
                throw new Exception(sprintf('A view %s não foi encontrada', $view));
            }
        }
        return $this->renderView($view, $variaveis, $retornar);
    }

    public function renderView($arquivoParaRenderizar, $variaveis = array(), $retornar = false) {
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
    public function url($caminho = '', $urlBase = '') {
        return $this->getUrlGenerator()->generateUrl($caminho, $urlBase);
    }

    public function getPublicAssetsUrl() {
        return $this->url(array('public_assets'));
    }

    public function dbg($var, $tipo = 2) {
        return Utils::debug($var, $tipo);
    }

    public function traduzirMes(DateTime $data) {
        switch ($data->format('m')) {
            case 1: return "Janeiro";
            case 2: return "Fevereiro";
            case 3: return "Março";
            case 4: return "Abril";
            case 5: return "Maio";
            case 6: return "Junho";
            case 7: return "Julho";
            case 8: return "Agosto";
            case 9: return "Setembro";
            case 10: return "Outubro";
            case 11: return "Novembro";
            case 12: return "Dezembro";
        }
    }

    public function hideEmailFromString($string) {
        if (empty($string)) {
            return;
        }
        $pedacos = explode('@', $string);
        $domain = '@' . end($pedacos);
        $stringOcultada = substr_replace(reset($pedacos), '*********', 5);
        return $stringOcultada . $domain;
    }

    public function hideNameFromString($string) {
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

    public function hideTelephoneNumberFromString($string) {
        if (empty($string)) {
            return;
        }
        return substr_replace($string, str_repeat('*', strlen($string) - 5), 3) . substr($string, -2);
    }

    public function hideString($string, $size = 5) {
        if (empty($string)) {
            return;
        }
        if ($size <= 0) {
            $size = 5;
        }
        return substr_replace($string, str_repeat('*', $size), $size);
    }

}
