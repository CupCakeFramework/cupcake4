<?php
namespace Cupcake\Mailer\Factory;

use Cupcake\Mailer\MailerManager;
use Cupcake\Managers\ConfigManager;
use Cupcake\Service\ServiceManager;

/**
 * Description of MailerManagerFactory
 *
 * @author Ricardo Fiorani
 */
class MailerManagerFactory
{

    /**
     * @param ServiceManager $serviceManager
     * @return MailerManager
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        /* @var $configManager ConfigManager */
        $configManager = $serviceManager->get('ConfigManager');
        $renderer = $serviceManager->get('CupRenderer');

        return new MailerManager($configManager->getValue('mailer'), $renderer,
            $configManager->getValue('dumpEmailOnScreen'));
    }

}
