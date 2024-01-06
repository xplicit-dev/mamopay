<?php

namespace MamoPay\Api\Traits;

/**
 * Trait PropertySetterTrait
 */
trait PropertySetterTrait
{
    /**
     * Set properties for the class
     *
     * @param array $properties
     *
     * @return mixed
     */
    public function set(array $properties)
    {
        foreach ($properties as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }

        return $this;
    }
}
