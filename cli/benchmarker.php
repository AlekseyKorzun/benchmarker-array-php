<?php
/**
 * You must run `composer install` order to generate autoloader
 */
require dirname(__DIR__) . '/vendor/autoload.php';

use \Exception;
use \Benchmarker\Factory;

$blueprint = array(
    't:' => 'target array to benchmark',
    'm:' => 'type of benchmark to perform against the target',
    'i:' => 'number of total iterations for the test',
    'h' => 'outputs this helpful message'
);

$options = getopt(
    implode(
        null,
        array_keys($blueprint)
    )
);

if ($blueprint) {
    if (isset($options['h'])) {
        foreach ($blueprint as $option => $description) {
            $option = str_replace(':', null, $option);
            if (!isset($options[$option])) {
                print "Option -" . $option . ": " . $description . "\n";
            }
        }
        exit(0);
    }

    foreach ($blueprint as $option => $description) {
        if (strpos($option, ':') == false) {
            continue;
        }

        $option = str_replace(':', null, $option);
        if (!isset($options[$option])) {
            print "Option -" . $option . " is required, see -h for more information.\n";
            exit(0);
        }
    }
}

try {
    $benchmarker = Factory::instance($options['t']);
    $benchmarker->setIterations($options['i']);
    $benchmarker->test($options['m']);
} catch (Exception $exception) {
    print $exception->getMessage();
    exit(1);
}

