<?php

/**
 * @author Ricardo Fiorani
 */
class CupRendererFactory {

    public function __invoke(ServiceManager $serviceManager) {
        /* @var $configManager ConfigManager */
        $configManager = $serviceManager->getService('ConfigManager');
        $urlGenerator = $serviceManager->getService('UrlGenerator');
        $rendererConfig = $configManager->getConfig('renderer');
        $siteConfig = $configManager->getConfig('site');
        return new CupRenderer($rendererConfig['pastaTemplates'], $rendererConfig['pastaViews'], $siteConfig['titulo'], $urlGenerator);
    }

}
