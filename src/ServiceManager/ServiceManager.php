<?php

use Interop\Container\ContainerInterface;

/**
 * @author Ricardo Fiorani
 */
class ServiceManager implements ContainerInterface {

    private $factories = Array();
    private $services = Array();

    public function addFactory($service, $factory) {
        if (false == is_string($service)) {
            throw new ContainerException(sprintf('Service name %s must be a string', $service));
        }
        $instantiatedFactory = new $factory;
        if (false == is_callable($instantiatedFactory)) {
            throw new ContainerException(sprintf('Factory %s must be a callable', $factory));
        }

        $this->factories[$service] = new $instantiatedFactory;
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
    public function get($id) {
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
    public function has($id) {
        return isset($this->factories[$id]);
    }

    public function injectService($serviceName, $service) {
        $this->services[$serviceName] = $service;
    }

}
