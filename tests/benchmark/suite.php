<?php
if (!extension_loaded('functional')) {
    die('Could not run benchmark: functional extension not loaded');
}

ini_set('memory_limit', '2G');

$sourceFiles = new RegexIterator(
    new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(__DIR__ . '/../../src/Functional/')
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
$hash = array_keys($array);
$hash = array_map('strval', $hash);
$iterator = new ArrayIterator($array);
$hashIterator = new ArrayIterator($hash);

function benchmark($functionName, $array, $iterator, $hash, $hashIterator)
{
    /** Reset values */
    $start = $end = $ret = null;

    $closure = function($value, $key, $collection) {
    };

    $recipes = array(
        array(
            'Userl.',
            'num array',
            'FunctionalUserland\\' . $functionName,
            $array,
        ),
        array(
            'Native',
            'num array',
            'Functional\\' . $functionName,
            $array,
        ),array(
            'Userl.',
            'hsh array',
            'FunctionalUserland\\' . $functionName,
            $hash,
        ),
        array(
            'Native',
            'hsh array',
            'Functional\\' . $functionName,
            $hash,
        ),
        array(
            'Userl.',
            'num iter.',
            'FunctionalUserland\\' . $functionName,
            $iterator,
        ),
        array(
            'Native',
            'num iter.',
            'Functional\\' . $functionName,
            $iterator,
        ),
        array(
            'Userl.',
            'hsh iter.',
            'FunctionalUserland\\' . $functionName,
            $hashIterator,
        ),
        array(
            'Native',
            'hsh iter.',
            'Functional\\' . $functionName,
            $hashIterator,
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

benchmark('some', $array, $iterator, $hash, $hashIterator);
benchmark('every', $array, $iterator, $hash, $hashIterator);
benchmark('first', $array, $iterator, $hash, $hashIterator);
benchmark('last', $array, $iterator, $hash, $hashIterator);
benchmark('map', $array, $iterator, $hash, $hashIterator);
benchmark('none', $array, $iterator, $hash, $hashIterator);
benchmark('each', $array, $iterator, $hash, $hashIterator);
benchmark('select', $array, $iterator, $hash, $hashIterator);
benchmark('reject', $array, $iterator, $hash, $hashIterator);
