<?php

namespace GLPchp\Compile\Service;

/**
 * @package GLPchp\Compile
 */
final class CsCollector
{
    /**
     * @var string
     */
    private
    string $prefix = "";

    /**
     * @var string
     */
    private /**/
    string $namespace = "";
    /**
     * @var string
     */
    private /**/
    string $className = "";
    /**
     * @var array
     */
    private /**/
    array $classMethods = [];
    /**
     * @var string
     */
    private
    string $pathOutput = "";

    private
    array $args = [
        "_Process" => ["double", "delta"],
    ];

    /**
     * @param string $prefix
     */
    public function __construct(string $prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @param string $namespace
     * @return $this
     */
    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @param string $className
     * @return $this
     */
    public function setClassName(string $className)
    {
        $this->className = $className;
        return $this;
    }

    /**
     * @param array $classMethods
     * @return $this
     */
    public function setClassMethods(array $classMethods)
    {
        $this->classMethods = $classMethods;
        return $this;
    }

    /**
     * @param string $pathOutput
     * @return $this
     */
    public function setPathOutput(string $pathOutput)
    {
        $this->pathOutput = str_replace("\\", "/", $pathOutput . "/");
        return $this;
    }

    public function run()
    {
        $this->makeDirs();
        $this->makeFile();
    }

    private function makeDirs()
    {
        $this->pathOutput = $this->pathOutput . str_replace(".", "/", $this->namespace);
        @mkdir($this->pathOutput, 0777, true);
    }

    private function makeFile(){
        $csClass = str_replace($this->prefix, "", $this->className);
        $csMethods = "";
        foreach ($this->classMethods as $method){
            $csArg = "";
            $csArgN = "";
            if(array_key_exists($method, $this->args)){
                $csArg = implode(" ", $this->args[$method]);
                $csArgN = $this->args[$method][1];
            }
            $csMethods .= "\tpublic override void " . $method . "({$csArg}) {  base." . $method . "({$csArgN}); }\n";
        }
        $cs
= <<<EOF
namespace {$this->namespace};

public partial class {$csClass} : {$this->className} {

{$csMethods}
}
EOF;
        $file = $this->pathOutput . "/" . $csClass . ".cs";
        if(md5_file($file) == md5($cs) || empty($csClass)) return;
        file_put_contents($file , $cs);
    }
}