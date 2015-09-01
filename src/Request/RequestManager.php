<?php
namespace Cupcake\Request;

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Ricardo Fiorani
 */
class RequestManager
{

    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    /**
     * @var RequestContext
     */
    private $context;

    function __construct()
    {
        $this->context = new RequestContext();
        $this->context->fromRequest(Request::createFromGlobals());
    }

    /**
     * @return RequestContext
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->getContext()->getBaseUrl();
    }

    /**
     * @param $url
     */
    public function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * @return bool
     */
    public function isPostRequest()
    {
        $method = $this->getContext()->getMethod();

        return self::METHOD_POST == $method;
    }

}
