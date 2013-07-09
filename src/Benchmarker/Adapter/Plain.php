<?php
namespace Benchmarker\Adapter;

use \Benchmarker\Factory;

/**
 * Benchmarker for array()
 *
 * @author Aleksey Korzun <al.ko@webfoundation.net>
 * @link http://www.alekseykorzun.com
 */
class Plain extends Factory
{
    /**
     * Instance test
     */
    protected function test_instance()
    {
        $iterations = 0;

        while ($iterations < $this->iterations) {
            array();
            ++$iterations;
        }
    }

    /**
     * Append test
     */
    protected function test_append()
    {
        $iterations = 0;

        while ($iterations < $this->iterations) {
            self::$array[$iterations] = $iterations;
            ++$iterations;
        }
    }

    /**
     * Test iteration
     */
    protected function test_iteration()
    {
        $this->test_append();

        $this->record();

        $iterations = 0;

        while ($iterations < $this->iterations) {
            self::$array[$iterations];
            ++$iterations;
        }
    }

    /**
     * Test removal
     */
    protected function test_removal()
    {
        $this->test_append();

        $this->record();

        $iterations = 0;

        while ($iterations < $this->iterations) {
            unset(self::$array[$iterations]);
            ++$iterations;
        }
    }
}
