<?php

namespace GLPchp\Compile\Service;

/**
 * @package GLPchp\Compile
 */
class Analyzer extends AnalyzerHelper
{
    /**
     * @param ConfigurationProperties $configurationImpl
     * @param \GLPchp\Compile\PhpParser\Parser $providerPhpParser
     * @param \GLPchp\Compile\PhpParser\NodeTraverser $providerPhpNodeTraverser
     */
    public function __construct(
        ConfigurationProperties $configurationImpl,
        \GLPchp\Compile\PhpParser\Parser $providerPhpParser,
        \GLPchp\Compile\PhpParser\NodeTraverser $providerPhpNodeTraverser)
    {
        $this->configuration = $configurationImpl;
        $this->phpParser = $providerPhpParser;
        $this->phpNodeTraverser = $providerPhpNodeTraverser;
        $this->csCollector = new CsCollector($this->configuration->prefix);
        Logger::writeLn("php analyzer initialized...");
    }
}