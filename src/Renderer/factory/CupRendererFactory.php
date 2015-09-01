<?php

namespace Cupcake\Renderer\Factory;

use Cupcake\Config\ConfigManager;
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
        $rendererConfig = $configManager->get('renderer');
        $siteConfig = $configManager->get('site');

        return new CupRenderer($rendererConfig['pastaTemplates'], $rendererConfig['pastaViews'], $siteConfig['titulo'],
            $urlGenerator);
    }

}
