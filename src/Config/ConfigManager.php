<?php

namespace Cupcake\Config;

use Exception;

/**
 * @author Ricardo Fiorani
 */
class ConfigManager
{

    private $configFiles = array();
    private $config = array();

    /**
     * @param array $configFiles
     * @throws Exception
     */
    public function __construct(array $configFiles)
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
    public function __invoke()
    {
        return $this->config;
    }

    /**
     * @param string $node
     * @return ConfigManager
     * @throws Exception
     */
    public function getNode($node)
    {
        if (false == $this->nodeExists($node)) {
            throw new Exception(sprintf("The node %s does not exists.", $node));
        }

        if (false == $this->isNode($node)) {
            throw new Exception(sprintf("%s is not a node.", $node));
        }

        return new ConfigManager($this->config[$node]);
    }

    /**
     * @param string $node
     * @return bool
     */
    public function nodeExists($node)
    {
        return isset($this->config[$node]);
    }

    /**
     * @param string $node
     * @return bool
     */
    public function isNode($node)
    {
        return is_array($this->config[$node]);
    }

    /**
     * @param $key
     * @return array
     */
    public function getValue($key)
    {
        return $this->config[$key];
    }

}
