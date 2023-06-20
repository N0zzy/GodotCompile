<?php

namespace GLPchp\Compile\Service;

/**
 * @package GLPchp\Compile
 */
final class Parser extends ParserFactory
{
    public function __construct()
    {
        parent::__construct();
        $this->analyzer->execute();
    }
}