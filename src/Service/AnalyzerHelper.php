<?php
namespace GLPchp\Compile\Service;

use GLPchp\Compile\PhpParser\Node\Stmt;

/**
 * @package GLPchp\Compile
 */
abstract class AnalyzerHelper
{
    /**
     * @var string
     */
    protected string $namespace = "";
    /**
     * @var string
     */
    protected string $className = "";
    /**
     * @var array
     */
    protected array $classMethods = [];
    /**
     * @var array
     */
    protected array $baseClassMethods = [
        "_Ready" => [],
        "_Process" => ["delta"]
    ];

    protected CsCollector|null $csCollector = null;

    /**
     * @var ConfigurationProperties|null
     */
    protected ?ConfigurationProperties $configuration = null;
    /**
     * @var \GLPchp\Compile\PhpParser\Parser|null
     */
    protected ?\GLPchp\Compile\PhpParser\Parser $phpParser = null;
    /**
     * @var \GLPchp\Compile\PhpParser\NodeTraverser|null
     */
    protected ?\GLPchp\Compile\PhpParser\NodeTraverser $phpNodeTraverser = null;

    public function execute(): void
    {
        Logger::writeLn("php analyzer started...");
        $this->iterates();
    }

    /**
     * @return void
     */
    private function iterates(): void
    {
        Logger::writeLn("path input " . $this->configuration->pathInput);
        Logger::writeLn("compile started...");
        /**
         * @var \RecursiveDirectoryIterator
         */
        $dir = new \RecursiveDirectoryIterator($this->configuration->pathInput);
        /**
         * @var \RecursiveIteratorIterator
         */
        $files = new \RecursiveIteratorIterator($dir);
        /**
         * @var \SplFileInfo $file
         */
        foreach ($files as $file) {
            if($file->isDir()) continue;
            if(str_contains($file->getRealPath(), ".sdk")) continue;
            if($file->getExtension() != "php") continue;
            Logger::writeLn("open: " . $file->getRealPath());
            $code = file_get_contents($file->getRealPath());
            $this->statments(iconv("UTF-8", "ASCII//IGNORE", $code));
        }

        Logger::writeLn("compile finished...");
    }

    /**
     * @param string $code
     * @return void
     */
    private function statments(string $code){
        $stmts = $this->phpParser->parse($code);
        $stmts = $this->phpNodeTraverser->traverse($stmts);
        $this->initHandle($stmts);
        $this->csCollector
            ->setPathOutput($this->configuration->pathOutput)
            ->setNamespace($this->namespace)
            ->setClassName($this->className)
            ->setClassMethods($this->classMethods)
            ->run();
        $this->clean();
    }

    /**
     * @param mixed $stmts
     * @return void
     */
    private function initHandle($stmts)
    {
        foreach ($stmts as $stmt) {
            $this->handle($stmt);
        }
    }

    /**
     * @param mixed $stmt
     * @return void
     */
    private function handle($stmt): void
    {
        if ($stmt instanceof Stmt\Namespace_) {
            $this->setNamespace($stmt);
        }
        else if ($stmt instanceof Stmt\Class_) {
            /**
             * @var \GLPchp\Compile\PhpParser\Comment $doc
             */
            $doc = $stmt->getDocComment();
            if(!method_exists($doc, "getReformattedText") || !$this->isCompile($doc)){
                $this->clean();
                return;
            }
            $this->setClassName($stmt);
        }
        else if ($stmt instanceof Stmt\ClassMethod){
            $this->setClassMethods($stmt);
        }
    }


    /**
     * @param Stmt\Namespace_ $stmt
     * @return void
     */
    private function setNamespace(Stmt\Namespace_ $stmt): void
    {
        $this->namespace = implode(".", $stmt->name->getParts());
        if(property_exists($stmt, 'stmts')) {
            $this->initHandle($stmt->stmts);
        }
    }

    /**
     * @param Stmt\Class_ $stmt
     * @return void
     */
    public function setClassName($stmt): void
    {
        $this->className = $stmt->name->name;
        if(property_exists($stmt, 'stmts')) {
            $this->initHandle($stmt->stmts);
        }
    }

    /**
     * @param Stmt\ClassMethod $stmt
     */
    public function setClassMethods($stmt): void
    {
        if(array_key_exists($stmt->name->name, $this->baseClassMethods)) {
            $this->classMethods[] = $stmt->name->name;
        }
    }


    /**
     * @param \GLPchp\Compile\PhpParser\Comment $comment
     * @return bool
     */
    private function isCompile(\GLPchp\Compile\PhpParser\Comment $comment): bool {
        $pattern = "/@compile-cs/i";
        return preg_match($pattern, $comment->getReformattedText());
    }

    private function clean(): void
    {
        $this->namespace = "";
        $this->className = "";
        $this->classMethods = [];
    }
}

