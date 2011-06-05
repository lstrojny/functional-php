<?php
if (!extension_loaded('functional')) {
    die('Could not run benchmark: functional extension not loaded');
}

ini_set('memory_limit', '2G');

$sourceFiles = new RegexIterator(
    new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(__DIR__ . '/../../../src/Functional/')
    ),
    '/\.php$/'
);
foreach ($sourceFiles as $file) {
    $code = file_get_contents($file->getPathName());
    $code = str_replace(array('namespace Functional', '<?php'), array('namespace FunctionalUserland', ''), $code);
    eval($code);
}

$numberOfElements = 100000;
$array = range(0, $numberOfElements - 1);
$iterator = new ArrayIterator($array);

function benchmark($functionName, $array, $iterator)
{
    /** Reset values */
    $start = $end = $ret = null;

    $closure = function($value, $key, $collection) {
    };

    $recipes = array(
        array(
            'Userl.',
            'array',
            'FunctionalUserland\\' . $functionName,
            $array,
        ),
        array(
            'Native',
            'array',
            'Functional\\' . $functionName,
            $iterator,
        ),
        array(
            'Userl.',
            'iter.',
            'FunctionalUserland\\' . $functionName,
            $iterator,
        ),
        array(
            'Native',
            'iter.',
            'Functional\\' . $functionName,
            $iterator,
        ),
    );

    echo str_repeat('-', 100) . "\n";

    $result = null;
    foreach ($recipes as $recipe) {
        list($implementation, $collectionType, $function, $collection) = $recipe;
        $start = $end = $ret = $warn = null;

        $start = microtime(true);
        $ret = $function($collection, $closure);
        $end = microtime(true);

        if ($implementation == 'Native' && ($end - $start) > $result) {
            $warn = '!!!';
        }
        $result = $end - $start;

        printf(
            "%s %-25s %s of %d elements, %f seconds%10s\n",
            $implementation,
            str_replace('Userland', '', $function) . '():',
            $collectionType,
            count($collection),
            $result,
            $warn
        );
    }
    echo str_repeat('-', 100) . "\n";
}

benchmark('any', $array, $iterator);
benchmark('all', $array, $iterator);
benchmark('detect', $array, $iterator);
benchmark('map', $array, $iterator);
benchmark('none', $array, $iterator);
benchmark('each', $array, $iterator);
benchmark('select', $array, $iterator);
benchmark('reject', $array, $iterator);
