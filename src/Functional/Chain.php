<?php

namespace Functional;

function chain($collection)
{
    Exceptions\InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);
    
    return new Chaining($collection);
}

class Chaining
{
    protected $collection;
    
    public function __construct($collection)
    {
        Exceptions\InvalidArgumentException::assertCollection($collection, __METHOD__, 1);
        
        $this->collection = $collection;
    }
    
    public function __call($name, $arguments) 
    {
        $collection = call_user_func_array('Functional\\' . $name, array_merge(array($this->collection), $arguments));
        return new static($collection);
    }
    
    public function _()
    {
        return $this->collection;
    }
}