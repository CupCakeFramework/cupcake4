<?php

namespace Cupcake\Service;

use Cupcake\Service\Exception\ContainerException;
use Cupcake\Service\Exception\NotFoundException;
use Interop\Container\ContainerInterface;

/**
 * @author Fábio Carneiro
 */
class ServiceManager implements ContainerInterface
{

    private $factories = array();
    private $services = array();

    /**
     * Adds a Service Factory to be invoked when their
     * related service be requested.
     *
     * @param string $serviceId The Service ID
     * @param Callable $factory The Service Factory (Must be a Callable)
     * @throws ContainerException
     */
    public function addFactory($serviceId, $factory)
    {
        if (false == is_string($serviceId)) {
            throw new ContainerException(sprintf('Service name %s must be a string', $serviceId));
        }

        if (is_callable($factory)) {
            $this->factories[$serviceId] = $factory;

            return;
        }

        if (false == class_exists($factory)) {
            throw new ContainerException(sprintf('Factory %s must be a class or callable', $factory));
        }

        $instantiatedFactory = new $factory;

        if (false == is_callable($instantiatedFactory)) {
            throw new ContainerException(sprintf('Factory %s must be a callable', $factory));
        }

        $this->factories[$serviceId] = new $instantiatedFactory;

    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundException  No entry was found for this identifier.
     * @throws ContainerException Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        if (false == is_string($id)) {
            throw new ContainerException('Service ID must be a string');
        }

        if (true == isset($this->services[$id])) {
            return $this->services[$id];
        }

        if (false == $this->has($id)) {
            throw new NotFoundException(sprintf('Service "%s" not found', $id));
        }


        return $this->services[$id] = $this->factories[$id]($this);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return boolean
     */
    public function has($id)
    {
        return isset($this->factories[$id]);
    }


    /**
     * Injects an already instantiated service into the service container
     * @param $serviceName
     * @param $service
     */
    public function injectService($serviceName, $service)
    {
        $this->services[$serviceName] = $service;
    }

}
