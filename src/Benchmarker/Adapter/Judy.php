<?php
namespace Benchmarker\Adapter;

use \Benchmarker\Factory;

/**
 * Benchmarker for Judy
 *
 * @author Aleksey Korzun <al.ko@webfoundation.net>
 * @link http://www.alekseykorzun.com
 */
class Judy extends Factory
{
    /**
     * Instance test
     */
    protected function test_instance()
    {
        $iterations = 0;

        while ($iterations < $this->iterations) {
            $class = get_class(self::$array);
            new $class($class::INT_TO_MIXED);
            ++$iterations;
        }
    }

}
