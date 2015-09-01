<?php
namespace Cupcake\Messenger\Factory;

use Cupcake\Config\ConfigManager;
use Cupcake\Messenger\FlashMessenger;
use Cupcake\Service\ServiceManager;

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
        $sessionId = $config->getNode('flash-messenger')->get('session-id');

        return new FlashMessenger($sessionId);
    }

}
