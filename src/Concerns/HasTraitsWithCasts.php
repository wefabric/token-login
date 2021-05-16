<?php


namespace Wefabric\TokenLogin\Concerns;


trait HasTraitsWithCasts
{
    /**
     * Get the casts array.
     *
     * @return array
     */
    public function getCasts()
    {
        $class = static::class;

        foreach (class_uses_recursive($class) as $trait) {
            $method = 'get'.class_basename($trait).'Casts';

            if (method_exists($class, $method)){
                $this->casts = array_unique(
                    array_merge($this->casts, $this->{$method}())
                );
            }
        }

        return parent::getCasts();
    }
}
