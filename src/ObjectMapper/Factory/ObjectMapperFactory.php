<?php

namespace Cupcake\ObjectMapper\Factory;

use Cupcake\ObjectMapper\ObjectMapper;
use Cupcake\Service\ServiceManager;

/**
 * @author Ricardo Fiorani
 */
class ObjectMapperFactory
{

    /**
     * @param ServiceManager $serviceManager
     * @return ObjectMapper
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        $db = $serviceManager->get('PDO');
        $cpr = $serviceManager->get('RequestManager');
        $debug = $serviceManager->get('ConfigManager')->getConfig('debug');

        return new ObjectMapper($db, $cpr, $debug);
    }

}
