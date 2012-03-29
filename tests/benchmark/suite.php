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

$numberOfElements = 10000;
$array = array_reverse(range(0, $numberOfElements - 1));
$hash = array_keys($array);
$hash = array_map(function($v){return 'k_' . $v;}, $hash);
$hash = array_flip($hash);
$iterator = new ArrayIterator($array);
$hashIterator = new ArrayIterator($hash);

function benchmark($functionName, $array, $iterator, $hash, $hashIterator, $secondParam = null)
{
    /** Reset values */
    $start = $end = $ret = null;

    if ($secondParam === null) {
        $secondParam = function($value, $key, $collection) {};
    }

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
        if ($secondParam) {
            $ret = $function($collection, $secondParam);
        } else {
            $ret = $function($collection);
        }
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



benchmark('difference', $array, $iterator, $hash, $hashIterator, false);
benchmark('drop_first', $array, $iterator, $hash, $hashIterator, function($v, $k) {return $v > 1000;});
benchmark('drop_last', $array, $iterator, $hash, $hashIterator, function($v, $k) {return $v > 1000;});
benchmark('each', $array, $iterator, $hash, $hashIterator);
benchmark('first', $array, $iterator, $hash, $hashIterator, function($v, $k) {return $v > 1000;});
benchmark('flatten', $array, $iterator, $hash, $hashIterator, false);
benchmark('every', $array, $iterator, $hash, $hashIterator);
benchmark('group', $array, $iterator, $hash, $hashIterator, function($v) {return $v % 2 == 0;});
benchmark('invoke', $array, $iterator, $hash, $hashIterator, 'method');
benchmark('last', $array, $iterator, $hash, $hashIterator, function($v, $k) {return $v > 1000;});
benchmark('map', $array, $iterator, $hash, $hashIterator);
benchmark('none', $array, $iterator, $hash, $hashIterator);
benchmark('partition', $array, $iterator, $hash, $hashIterator, function($v, $k) {return $v / 2;});
benchmark('pluck', $array, $iterator, $hash, $hashIterator, 'property');
benchmark('product', $array, $iterator, $hash, $hashIterator, false);
benchmark('ratio', $array, $iterator, $hash, $hashIterator, false);
benchmark('reduce_left', $array, $iterator, $hash, $hashIterator, function($v){return $v;});
benchmark('reduce_right', $array, $iterator, $hash, $hashIterator, function($v){return $v;});
benchmark('reject', $array, $iterator, $hash, $hashIterator);
benchmark('select', $array, $iterator, $hash, $hashIterator);
benchmark('some', $array, $iterator, $hash, $hashIterator);
benchmark('sum', $array, $iterator, $hash, $hashIterator, false);
benchmark('difference', $array, $iterator, $hash, $hashIterator, false);
benchmark('ratio', $array, $iterator, $hash, $hashIterator, false);
benchmark('product', $array, $iterator, $hash, $hashIterator, false);
benchmark('average', $array, $iterator, $hash, $hashIterator, false);
benchmark('first_index_of', $array, $iterator, $hash, $hashIterator, 2000);
benchmark('last_index_of', $array, $iterator, $hash, $hashIterator, 2000);
benchmark('head', $array, $iterator, $hash, $hashIterator);
benchmark('tail', $array, $iterator, $hash, $hashIterator);
