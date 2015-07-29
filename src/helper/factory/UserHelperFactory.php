<?php

use Doctrine\ORM\EntityManager;

/**
 * @author Ricardo Fiorani
 */
class UserHelperFactory {

    public function __invoke(ServiceManager $serviceManager) {
        $em = $serviceManager->getService('EntityManager');
        return new UserHelper($em);
    }

}
