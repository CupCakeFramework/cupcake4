<?php

/**
 * @author Ricardo Fiorani
 */
class UrlGeneratorFactory {

    function __invoke(ServiceManager $serviceManager) {
        return new UrlGenerator($serviceManager->getService('RequestManager'));
    }

}
