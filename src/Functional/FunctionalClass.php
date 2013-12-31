<?php

namespace Functional;

require_once __DIR__ . '/_import.php';

class FunctionalClass {

    public static function __callStatic($name, $args) {
        $lexicallyScopedFunction = __NAMESPACE__ . "\\$name";
        return call_user_func_array($lexicallyScopedFunction, $args);
    }

}