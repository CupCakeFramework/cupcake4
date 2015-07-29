<?php

/**
 * @author Ricardo Fiorani
 */
class ServiceManager {

    private $factories = Array();
    private $services = Array();

    public function addFactory($service, $factory) {
        if (false == is_string($service)) {
            throw new Exception(sprintf('O Nome do Service %s deve ser uma string', $service));
        }
        $instantiatedFactory = new $factory;
        if (false == is_callable($instantiatedFactory)) {
            throw new Exception(sprintf('A Factory %s deve ser um callable', $factory));
        }

        $this->factories[$service] = new $instantiatedFactory;
    }

    public function getService($service) {
        if (false == is_string($service)) {
            throw new Exception('Service deve ser uma string');
        }

        if (true == isset($this->services[$service])) {
            return $this->services[$service];
        }

        if (false == isset($this->factories[$service])) {
            throw new Exception(sprintf('Servico "%s" nao encontrado', $service));
        }


        return $this->services[$service] = $this->factories[$service]($this);
    }

    public function injectService($serviceName, $service) {
        $this->services[$serviceName] = $service;
    }

}
