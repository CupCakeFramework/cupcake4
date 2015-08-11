<?php

/**
 * Description of MailerManagerFactory
 *
 * @author Ricardo Fiorani
 */
class MailerManagerFactory {

    public function __invoke(ServiceManager $serviceManager) {
        /* @var $configManager ConfigManager */
        $configManager = $serviceManager->getService('ConfigManager');
        $renderer = $serviceManager->getService('CupRenderer');
        return new MailerManager($configManager->getConfig('mailer'), $renderer, $configManager->getConfig('dumpEmailOnScreen'));
    }

}
