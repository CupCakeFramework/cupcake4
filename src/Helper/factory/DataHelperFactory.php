<?php

/**
 * @author Ricardo Fiorani
 */
class DataHelperFactory {

    public function __invoke(ServiceManager $serviceManager) {
        $db = $serviceManager->get('PDO');
        $cpr = $serviceManager->get('RequestManager');
        $debug = $serviceManager->get('ConfigManager')->getConfig('debug');
        return new DataHelper($db, $cpr, $debug);
    }

}
