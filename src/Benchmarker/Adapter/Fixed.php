<?php
namespace Benchmarker\Adapter;

use \Benchmarker\Factory;

/**
 * Benchmarker for SplFixedArray
 *
 * @author Aleksey Korzun <al.ko@webfoundation.net>
 * @link http://www.alekseykorzun.com
 */
class Fixed extends Factory
{
    /**
     * Append test
     */
    protected function test_append()
    {
        self::$array->setSize($this->iterations);

        $iterations = 0;

        while ($iterations < $this->iterations) {
            self::$array->offsetSet($iterations, $iterations);
            ++$iterations;
        }
    }
}
