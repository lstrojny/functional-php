<?php

namespace Functional;

use Functional\Exceptions\InvalidArgumentException;

/**
 * Return the subset of objects which exactly match the supplied properties
 *
 * @param iterable|Traversable|array $objects
 * @param array $propertyMap
 * @return array
 */
function where($objects, array $propertyMap)
{
    /**
     * Helper function
     *
     * Read a property of an object with fallbacks for arrays and methods
     *
     * @param $object
     * @param $property
     * @return mixed
     */
    $readProperty = function($object, $property) {
        if (is_object($object)) {
            if (property_exists($object, $property)) {
                return $object->$property;
            } elseif (method_exists($object, $property)) {
                return $object->{$property}();
            } else {
                // could return false here as well, to allow optional properties (stdClass) or a mixed-type collection
                throw new \InvalidArgumentException(sprintf('Expected property or method %s in %s', $property, get_class($object)));
            }
        } elseif (is_array($object)) {
            if (array_key_exists($property, $object)) {
                return $object[$property];
            } else {
                throw new \OutOfBoundsException;
            }
        } else {
            throw new \InvalidArgumentException('Expected object or array');
        }
    };

    /**
     * Helper function
     *
     * Check if an object or array (hashmap) contains the supplied values
     *
     * @param array|object $object
     * @param array $propertyMap
     * @return bool
     */
    $matches = function($object, array $propertyMap) use ($readProperty, &$matches) {
        foreach ($propertyMap as $property => $value) {
            if (is_array($value)) {
                if (!$matches($readProperty($object, $property), $value)) {
                    return false;
                } else {
                    continue;
                }
            }

            try {
                // weak equality check to compare different instances of objects
                if ($readProperty($object, $property) != $value) {
                    return false;
                }
            } catch (\OutOfBoundsException $outOfBoundsException) {
                return false;
            }
        }
        return true;
    };


    InvalidArgumentException::assertCollection($objects, __FUNCTION__, 1);

    $result = [];
    foreach ($objects as $obj)
    {
        if ($matches($obj, $propertyMap)) {
            $result[] = $obj;
        }
    }
    return $result;
}

