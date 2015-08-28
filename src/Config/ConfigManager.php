<?php

namespace Cupcake\Managers;

use Exception;

/**
 * @author Ricardo Fiorani
 */
class ConfigManager
{

    private $configFiles = [];
    private $config = [];

    /**
     * @param array $configFiles
     * @throws Exception
     */
    function __construct(array $configFiles)
    {
        $this->configFiles = $configFiles;
        foreach ($configFiles as $file) {
            if (false == file_exists($file)) {
                throw new Exception(sprintf('O arquivo de configuracao %s nao existe ', $file));
            }
            $array = require $file;
            $this->config = array_merge($array, $this->config);
        }
    }

    /**
     * As configurações dadas merged
     * @return Array
     */
    function __invoke()
    {
        return $this->config;
    }

    /**
     * @param string $node
     * @return mixed|ConfigManager
     */
    function getConfig($node = '')
    {
        if (false == empty($node)) {
            if (is_array($this->config[$node])) {
                return new ConfigManager($this->config[$node]);
            }

            return $this->config[$node];
        }


        return $this->config;
    }

}
