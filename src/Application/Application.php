<?php

/**
 * @author Ricardo Fiorani
 */

namespace Cupcake\Application;

use Cupcake\Config\ConfigManager;
use Cupcake\Service\ServiceManager;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Exception;


class Application
{

    /**
     * @var ServiceManager
     */
    private $serviceManager;

    /**
     * @param ConfigManager $config
     */
    function __construct(ConfigManager $config)
    {
        $this->setServiceManager(new ServiceManager());
        $this->getServiceManager()->injectService('ConfigManager', $config);
        foreach ($config->get('services') as $service => $factory) {
            $this->getServiceManager()->addFactory($service, $factory);
        }
        foreach ($config->get('controllers') as $controller => $factory) {
            $this->getServiceManager()->addFactory($controller, $factory);
        }


    }

    /**
     * @return mixed
     */
    function run()
    {
        $routes = new RouteCollection();
        /** @var ConfigManager $configManager */
        $configManager = $this->serviceManager->get('ConfigManager');
        foreach ($configManager->get('routes') as $rota => $values) {
            $routes->add($rota,
                new Route($values['route'],
                    array('controller' => $values['controller'], 'action' => $values['action'])));
        }

        /*@var $context RequestContext */
        $context = $this->serviceManager->get('RequestManager')->getContext();

        $matcher = new UrlMatcher($routes, $context);

        $errorController = $this->getServiceManager()->get('ErrorController');
        try {
            $parameters = $matcher->match($context->getPathInfo());
            $controller = $this->getServiceManager()->get($parameters['controller']);
            $action = $this->getNomeAction($parameters['action']);
            if (false == method_exists($controller, $action)) {
                throw new Exception(sprintf('O Controller %s não possui o método %s', get_class($controller), $action));
            }
            $actionParameters = $this->getActionParameters($parameters);
            if (true == method_exists($controller, 'beforeDispatch')) {
                call_user_func(array($controller, 'beforeDispatch'));
            }

            return call_user_func_array(array($controller, $action), $actionParameters);
        } catch (ResourceNotFoundException $ex) {
            return $errorController->actionError404();
        } catch (MethodNotAllowedException $ex) {
            return $errorController->actionError500($ex);
        } catch (Exception $ex) {
            return $errorController->actionError500($ex);
        }
    }

    /**
     * @param array $parameters
     * @return array
     */
    public function getActionParameters(array $parameters)
    {
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

    /**
     * @param $view
     * @return string
     */
    public function getNomeAction($view)
    {
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
    function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @param ServiceManager $serviceManager
     */
    function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }


}
