<?php

namespace GLPchp\Compile\Service;

use System\Console;

final class Logger
{
    private static string $prefix = "[Debug] ";
    private static string $warn = "[Warning] ";

    /**
     * @var int|float
     */
    private static int|float $benchmark = 0;

    /**
     * @param string $str
     * @return void
     */
    public static function writeLn(string $str): void
    {
        Console::WriteLine(self::$prefix . $str . " [" . self::benchmarkAdd() . " sec.]");
    }

    public static function warnLn(string $str): void
    {
        Console::WriteLine(self::$warn . $str);
    }

    /**
     * @return void
     */
    public static function benchmarkStart(): void
    {
        self::$benchmark = microtime(true);
    }

    /**
     * @return float
     */
    public static function benchmarkAdd(): float
    {
        return round(microtime(true) - self::$benchmark, 2);
    }
}