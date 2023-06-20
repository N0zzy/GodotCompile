<?php

namespace GLPchp\Compile\Service;
/**
 * Class ParserFactory
 * @package GLPchp\Compile\Service
 */
abstract class ParserFactory
{
    /**
     * @var Analyzer|null
     */
    protected ?Analyzer $analyzer = null;
    /**
     * @var ConfigurationProperties|null
     */
    protected ?ConfigurationProperties $config = null;
    /**
     * @var \GLPchp\Compile\PhpParser\Parser|null
     */
    protected ?\GLPchp\Compile\PhpParser\Parser $providerPhpParser = null;
    /**
     * @var \GLPchp\Compile\PhpParser\NodeTraverser|null
     */
    protected ?\GLPchp\Compile\PhpParser\NodeTraverser $providerPhpNodeTraverser = null;

    /**
     * @throws \Exception
     */
    protected function __construct()
    {
        $this->config = (new Configuration())->get();
        Logger::writeLn("configuration initialized...");
        $this->providerPhpParser =
            (new \GLPchp\Compile\PhpParser\ParserFactory)
                ->create(\GLPchp\Compile\PhpParser\ParserFactory::PREFER_PHP7);
        Logger::writeLn("php parser initialized...");
        $this->providerPhpNodeTraverser = new \GLPchp\Compile\PhpParser\NodeTraverser();
        Logger::writeLn("php node traverser initialized...");
        $this->analyzer = new Analyzer(
            $this->config,
            $this->providerPhpParser,
            $this->providerPhpNodeTraverser
        );
    }
}