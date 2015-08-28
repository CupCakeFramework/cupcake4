<?php
namespace Cupcake\Messenger\Factory;

use Cupcake\Messenger\FlashMessenger;
use Cupcake\Service\ServiceManager;
use Cupcake\Managers\ConfigManager;

/**
 * @author Ricardo Fiorani
 */
class FlashMessengerFactory
{

    /**
     * @param ServiceManager $serviceManager
     * @return FlashMessenger
     */
    public function __invoke(ServiceManager $serviceManager)
    {
        /** @var ConfigManager $config */
        $config = $serviceManager->get('ConfigManager');
        $sessionId = $config->getConfig('FlashMessenger')->getConfig('session-id');

        return new FlashMessenger($sessionId);
    }

}
