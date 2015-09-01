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
    public function __construct(array $configFiles = array())
    {
        $this->configFiles = $configFiles;
        foreach ($configFiles as $file) {
            if (false == file_exists($file)) {
                throw new Exception(sprintf('The config file "%s" does not exists.', $file));
            }
            $array = require $file;
            $this->config = array_merge($array, $this->config);
        }
    }

    public function setConfigFromArray(array $config)
    {
        $this->config = array_merge($config, $this->config);
    }

    /**
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
            throw new Exception(sprintf("The node %s does not exists in the config file.", $node));
        }

        if (false == $this->isNode($node)) {
            throw new Exception(sprintf("%s is not a node.", $node));
        }

        $configManager = new ConfigManager();
        $configManager->setConfigFromArray($this->config[$node]);

        return $configManager;
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
     * @return mixed
     */
    public function get($key)
    {
        return $this->config[$key];
    }

}
