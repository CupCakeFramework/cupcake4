<?php
namespace Cupcake\Mailer\Factory;

use Cupcake\Config\ConfigManager;
use Cupcake\Mailer\MailerManager;
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
        $bypassEmail = $configManager->get('bypassEmailSend');

        return new MailerManager(
            $configManager->get('mailer'),
            $renderer,
            $configManager->get('dumpEmailOnScreen'),
            $bypassEmail
        );
    }

}
