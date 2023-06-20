<?php
namespace GLPchp\Compile\Service;


class App
{
    public function __construct()
    {
        Logger::benchmarkStart();
        Logger::writeLn("start...");
        new Parser();
    }
}