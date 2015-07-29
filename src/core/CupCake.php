<?php

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

/**
 * @author Ricardo Fiorani
 */
class CupCake {

    /**
     * @var ServiceManager 
     */
    private $serviceManager;

    function __construct(ConfigManager $config) {
        $this->setServiceManager(new ServiceManager());
        $this->getServiceManager()->injectService('ConfigManager', $config);
        foreach ($config->getConfig('services') as $service => $factory) {
            $this->getServiceManager()->addFactory($service, $factory);
        }
        foreach ($config->getConfig('controllers') as $controller => $factory) {
            $this->getServiceManager()->addFactory($controller, $factory);
        }

        /* Fix do Painel */
        $this->setDbForPainel();
    }

    function run() {
        $routes = new RouteCollection();
        foreach ($this->serviceManager->getService('ConfigManager')->getConfig('routes') as $rota => $values) {
            $routes->add($rota, new Route($values['route'], array('controller' => $values['controller'], 'action' => $values['action'])));
        }

        $routes->add('Generator', new Route('/generateSiteControllerFromViews', array('controller' => 'GeneratorController', 'action' => 'generateSiteControllerFromViews')));
        $routes->add('Generator', new Route('/generateRoutesFromViews', array('controller' => 'GeneratorController', 'action' => 'generateRoutesFromViews')));

        $context = $this->serviceManager->getService('RequestManager')->getContext();

        $matcher = new UrlMatcher($routes, $context);

        $errorController = $this->getServiceManager()->getService('ErrorController');
        try {
            $parameters = $matcher->match($context->getPathInfo());
            $controller = $this->getServiceManager()->getService($parameters['controller']);
            $action = $this->getNomeAction($parameters['action']);
            if (false == method_exists($controller, $action)) {
                throw new Exception(sprintf('O Controller %s não possui o método %s', get_class($controller), $action));
            }
            $actionParameters = $this->getActionParameters($parameters);
            return call_user_func_array(array($controller, $action), $actionParameters);
        } catch (ResourceNotFoundException $ex) {
            return $errorController->actionError404();
        } catch (MethodNotAllowedException $ex) {
            return $errorController->actionError500($ex);
        } catch (Exception $ex) {
            return $errorController->actionError500($ex);
        }
    }

    public function getActionParameters(array $parameters) {
        $removableParameters = array(
            'controller',
            'action',
            '_route',
        );
        foreach ($parameters as $key => $p) {
            if (in_array($key, $removableParameters)) {
                unset($parameters[$key]);
            }
        }
        return $parameters;
    }

    public function getNomeAction($view) {
        $listaNomes = explode('-', $view);
        $action = '';
        foreach ($listaNomes as $nome) {
            $action .= ucfirst($nome);
        }
        return 'action' . $action;
    }

    /**
     * @return ServiceManager
     */
    function getServiceManager() {
        return $this->serviceManager;
    }

    function setServiceManager(ServiceManager $serviceManager) {
        $this->serviceManager = $serviceManager;
    }

    /* Fix temporário para funcionar o painel */

    public $db;

    public function setDbForPainel() {
        $this->db = $this->getServiceManager()->getService('PDO');
    }

}
