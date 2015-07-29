<?php

/**
 * @author Ricardo Fiorani
 */
class RequestManagerFactory {

    public function __invoke(ServiceManager $serviceManager) {
        return new RequestManager();
    }

}
