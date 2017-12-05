<?php

namespace App\Traits;

use royallib\type\Str;

trait ModelAttributesTrait
{
    public function __get($name)
    {
        $getter = "get{$name}";
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }

        $name = (new Str($name))->fromCamelCase()->value;
        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        $setter = "set{$name}";
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        }

        $name = (new Str($name))->fromCamelCase()->value;
        parent::__set($name, $value);
    }
}
