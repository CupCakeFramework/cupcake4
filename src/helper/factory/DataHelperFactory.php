<?php

/**
 * @author Ricardo Fiorani
 */
class DataHelperFactory {

    public function __invoke(ServiceManager $serviceManager) {
        $db = $serviceManager->getService('PDO');
        $cpr = $serviceManager->getService('RequestManager');
        $debug = $serviceManager->getService('ConfigManager')->getConfig('debug');
        return new DataHelper($db, $cpr, $debug);
    }

}
