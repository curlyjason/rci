<?php

namespace App\Utilities;

use Cake\ORM\Entity;
use Cake\Utility\Hash;

class EntityAccessDecorator
{
    protected Entity $_entity;
    protected array $_array;
    protected array $_paths;
    public function __construct(Entity $entity)
    {
        $this->_entity = $entity;
        $this->_array = $entity->toArray();
        $this->_paths = array_keys(Hash::flatten($this->_array));
    }

    public function get(string $path): string
    {
        return Hash::get($this->_array, $path);
    }

    public function extract(string $path): array
    {
        return Hash::extract($this->_array, $path);
    }

    /**
     * Convenience for calling Entity functions
     *
     * <pre>
     *      $instance = new EntityAccessDecorator($entity);
     *      $instance()->hasErrors();
     *      $instance()->set($field, $value);
     * </pre>
     *
     * @return Entity
     */
    public function __invoke()
    {
        return $this->_entity;
    }

    /**
     * Enumerate all dot-paths to the data
     *
     * @return array|string[]
     */
    public function paths()
    {
        return $this->_paths;
    }

}
