<?php
namespace GLPchp\Compile\Service;

final class ConfigurationProperties
{
    /**
     * @var string
     */
    public string $pathInput = "";
    /**
     * @var string
     */
    public string $pathOutput = "";
    /**
     * @var string
     */
    public string $prefix = "";

    /**
     * @param object $json
     */
    public function __construct(object $json)
    {
        $this->pathInput = $json->path->input;
        $this->pathOutput = $json->path->output;
        $this->prefix = $json->prefix;
    }
}