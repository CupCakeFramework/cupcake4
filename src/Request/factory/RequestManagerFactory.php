<?php

namespace Cupcake\Request\Factory;

use Cupcake\Request\RequestManager;
use Cupcake\Service\ServiceManager;

/**
 * @author Ricardo Fiorani
 */
class RequestManagerFactory
{

    /**
     * @param ServiceManager $serviceManager
     * @return RequestManager
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        return new RequestManager();
    }

}
