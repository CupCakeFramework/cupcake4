<?php

class BaseEntity {

    const ATIVO_TRUE = 'Sim';
    const ATIVO_FALSE = 'Não';

    public function __construct(array $data = array()) {
        $this->setValues($data);
    }

    public function setValues(array $dados) {
        foreach ($dados as $campo => $valor) {
            $method = 'set' . ucfirst($campo);
            if (method_exists($this, $method)) {
                $this->$method($valor);
            }
        }
    }

}
