<?php

namespace GLPchp\Compile\Service;

/**
 * @package GLPchp\Compile
 */
final class Configuration
{
    /**
     * @return ConfigurationProperties
     * @throws \Exception
     */
    public function get(): ConfigurationProperties
    {
        return $this->getConfigurationProperties();
    }

    /**
     * @return ConfigurationProperties
     * @throws \Exception
     */
    private function getConfigurationProperties(): ConfigurationProperties
    {
        $path = str_replace("\\", "/", dirname(__DIR__, 2) . '/compile.json');
        if (!file_exists($path)) {
            $path = str_replace("\\", "/", dirname(__DIR__, 5) . '/compile.json');
            if (!file_exists($path)) {
                throw new \Exception("path not exists {$path}");
            }          
        }
        $json = json_decode(file_get_contents($path));
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception(json_last_error_msg());
        }
        return new ConfigurationProperties($json);
    }
}