<?php
namespace Cupcake\Url\Factory;

use Cupcake\Service\ServiceManager;
use Cupcake\Url\UrlGenerator;

/**
 * @author Ricardo Fiorani
 */
class UrlGeneratorFactory
{

    /**
     * @param ServiceManager $serviceManager
     * @return UrlGenerator
     */
    function __invoke(ServiceManager $serviceManager)
    {
        return new UrlGenerator($serviceManager->get('RequestManager'));
    }

}
