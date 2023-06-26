<?php

namespace GLPchp\Compile\Service;

/**
 * @package GLPchp\Compile
 */
final class Parser extends ParserFactory
{
    /**
     * @param string $dir
     */
    public function __construct()
    {
        parent::__construct();
        $this->analyzer->execute();
    }
}