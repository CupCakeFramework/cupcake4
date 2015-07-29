<?php

/**
 * @author Ricardo Fiorani
 */
class PDOFactory {

    public function __invoke(ServiceManager $serviceManager) {
        /* @var $configManager ConfigManager */
        $configManager = $serviceManager->getService('ConfigManager');
        $databaseConfig = $configManager->getConfig('database');
        return new PDO("mysql:host=" . $databaseConfig['host'] . ";dbname=" . $databaseConfig['dbname'], $databaseConfig['user'], $databaseConfig['password']);
    }

}
