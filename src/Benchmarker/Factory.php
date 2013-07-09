<?php
namespace Benchmarker;

use \Judy;
use \ArrayObject;
use \SplFixedArray;
use \Exception;
use \Benchmarker\Adapter\Plain as Adapter_Plain;
use \Benchmarker\Adapter\Object as Adapter_Object;
use \Benchmarker\Adapter\Fixed as Adapter_Fixed;
use \Benchmarker\Adapter\Judy as Adapter_Judy;

/**
 * Benchmarker for PHP based array implementations
 *
 * @author Aleksey Korzun <al.ko@webfoundation.net>
 * @link http://www.alekseykorzun.com
 */
abstract class Factory
{
    /**
     * Plain array identifier
     *
     * @var string
     */
    const ARRAY_PLAIN = 'plain';

    /**
     * ArrayObject identifier
     *
     * @var string
     */
    const ARRAY_OBJECT = 'object';

    /**
     * SplFixedArray identifier
     *
     * @var string
     */
    const ARRAY_FIXED = 'fixed';

    /**
     * Judy identifier
     *
     * @var string
     */
    const ARRAY_JUDY = 'judy';

    /**
     * Stored test time
     *
     * @var float
     */
    protected $time;

    /**
     * Stores memory utilization
     *
     * @var float
     */
    protected $memory;

    /**
     * Number of iterations to perform for current test
     *
     * @var int
     */
    protected $iterations = 0;

    /**
     * Instance of a array type
     *
     * @var mixed[]|ArrayObject|SplFixedArray|Judy
     */
    protected static $array;

    /**
     * Get instance of array benchmarker
     *
     * @throws Exception
     * @param string $array
     * @return Adapter_Plain|Adapter_Object|Adapter_Fixed|Adapter_Judy
     */
    public static function instance($array)
    {
        switch ($array) {
            case self::ARRAY_PLAIN:
                self::$array = array();
                return new Adapter_Plain();
            case self::ARRAY_OBJECT:
                self::$array = new ArrayObject();
                return new Adapter_Object();
            case self::ARRAY_FIXED:
                self::$array = new SplFixedArray();
                return new Adapter_Fixed();
            case self::ARRAY_JUDY:
                self::$array = new Judy(Judy::INT_TO_MIXED);
                return new Adapter_Judy();
        }

        throw new Exception(
            'Array type you passed is currently not supported.'
        );
    }

    /**
     * Record benchmark results
     */
    protected function record()
    {
        $this->time = microtime(true);
        $this->memory = memory_get_usage();
    }

    /**
     * Output logged benchmark results
     */
    protected function play()
    {
        print "\tPeak memory usage in kilobytes:\n\t";
        print round((memory_get_peak_usage()) / 1024, 2) . "\n";

        print "\tMemory consumed in kilobytes:\n\t";
        print round((memory_get_usage() - $this->memory) / 1024, 2) . "\n";

        print "\tExecution time in seconds:\n\t";
        print round(microtime(true) - $this->time, 2) . "\n";
    }

    /**
     * Instance test
     */
    protected function test_instance()
    {
        $iterations = 0;

        while ($iterations < $this->iterations) {
            $class = get_class(self::$array);
            new $class();
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
            self::$array->offsetSet($iterations, $iterations);
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
            self::$array->offsetGet($iterations);
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
            self::$array->offsetUnset($iterations);
            ++$iterations;
        }
    }

    /**
     * Set number of iterations to perform during the test
     *
     * @param int $iterations
     */
    public function setIterations($iterations)
    {
        $this->iterations = (int)$iterations;
    }

    /**
     * Retrieve currently set iteration limit
     *
     * @return int
     */
    public function getIterations()
    {
        return $this->iterations;
    }

    /**
     * Run a specific number of tests
     *
     * @param string[]|string $name
     */
    public function test($names)
    {
        $names = (array)$names;

        if ($names) {
            foreach ($names as $name) {
                $method = 'test_' . $name;
                if (method_exists($this, $method)) {

                    $type = is_object(self::$array) ? get_class(self::$array) : 'array';

                    print "Running " . $this->getIterations() . " iterations on: [" . $type . "] for [" . $name . "]\n";

                    $this->record();
                    $this->$method();
                    $this->play();

                    continue;
                }

                // Fail all of the tests if we are attempting to test something
                // that does not exist
                throw new Exception(
                    'One or more of requested tests does not exists'
                );
            }
        }
    }
}
