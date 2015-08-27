<?php

/**
 * @author Ricardo Fiorani
 */
class CupMessengerFactory {

    public function __invoke(ServiceManager $serviceManager) {
        return new CupMessenger();
    }

}
