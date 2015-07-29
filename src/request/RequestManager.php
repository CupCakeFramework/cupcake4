<?php

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Ricardo Fiorani
 */
class RequestManager {

    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    /**
     * @var RequestContext
     */
    private $context;

    function __construct() {
        $this->context = new RequestContext();
        $this->context->fromRequest(Request::createFromGlobals());
    }

    public function getContext() {
        return $this->context;
    }

    public function getBaseUrl() {
        return $this->getContext()->getBaseUrl();
    }

    public function redirect($url) {
        header('Location: ' . $url);
        exit;
    }

    public function isPostRequest() {
        $method = $this->getContext()->getMethod();
        return self::METHOD_POST == $method;
    }

}
