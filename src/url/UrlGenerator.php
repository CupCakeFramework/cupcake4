<?php

/**
 * @author Ricardo Fiorani
 */
class UrlGenerator {

    /**
     *
     * @var RequestManager 
     */
    private $request;

    function __construct(RequestManager $cpr) {
        $this->request = $cpr;
    }

    /**
     * Gera uma URL para o site.
     * @param array $caminho Caminho cada item corresponde a um diretório. Ex: array('caminho','parametro') = http://seuprojeto.com/caminho/parametro/
     * @param mixed $urlBase A BaseUrl para gerar a url. Por padrão é utilizado o baseUrl do contexto atual.
     * @return string A Url Gerada
     */
    public function generateUrl($caminho = '', $urlBase = '') { //Caminho em branco para retornar por padrão a "home"
        $url = (empty($urlBase) ? $this->request->getBaseUrl() : $urlBase) . '/';
        if (is_array($caminho)) {
            return $this->hydrateArrayToUrl($caminho, $url);
        }
        return $url . $caminho;
    }

    public function hydrateArrayToUrl(array $caminho = array(), $url = '') {
        foreach ($caminho as $value) {
            $url .= $value;
            if ($value != end($caminho)) {
                $url .= '/';
            }
        }
        return $url;
    }

}
