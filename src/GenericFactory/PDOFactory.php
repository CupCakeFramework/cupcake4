<?php

namespace Cupcake\GenericFactory;

use Cupcake\Managers\ConfigManager;
use Cupcake\Service\ServiceManager;
use PDO;


/**
 * @author Ricardo Fiorani
 */
class PDOFactory
{

    /**
     * @param ServiceManager $serviceManager
     * @return PDO
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        /* @var $configManager ConfigManager */
        $configManager = $serviceManager->get('ConfigManager');
        $databaseConfig = $configManager->getConfig('database');

        return new PDO("mysql:host=" . $databaseConfig['host'] . ";dbname=" . $databaseConfig['dbname'],
            $databaseConfig['user'], $databaseConfig['password']);
    }

}
