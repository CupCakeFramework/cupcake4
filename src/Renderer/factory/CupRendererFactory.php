<?php

namespace Cupcake\Renderer\Factory;

use Cupcake\Managers\ConfigManager;
use Cupcake\Renderer\CupRenderer;
use Cupcake\Service\ServiceManager;

/**
 * @author Ricardo Fiorani
 */
class CupRendererFactory
{

    public function __invoke(ServiceManager $serviceManager)
    {
        /* @var $configManager ConfigManager */
        $configManager = $serviceManager->get('ConfigManager');
        $urlGenerator = $serviceManager->get('UrlGenerator');
        $rendererConfig = $configManager->getConfig('renderer');
        $siteConfig = $configManager->getConfig('site');

        return new CupRenderer($rendererConfig['pastaTemplates'], $rendererConfig['pastaViews'], $siteConfig['titulo'],
            $urlGenerator);
    }

}
