Benchmarker Array (v1.0.0)
==========================

Benchmarking 'framework' used to analyze performance of PHP array implementations.

Written to support the following blog post:

http://www.alekseykorzun.com/post/55787102212/php-judy-array-introduction-and-comparison

Installation
-----

If you have your own autoloader, simply update namespaces and drop the files
into your frameworks library.

For people that do not have that setup, you can visit http://getcomposer.org to install
composer on your system. After installation simply run `composer install` in parent
directory of this distribution to generate vendor/ directory with a cross system autoloader.

CLI parameters
----

-t type of array to benchmark (plain, object, fixed or judy)
-m type of benchmark to perform (instance, append, iteration, removal)
-i number of iterations to perform during the benchmark
