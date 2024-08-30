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

    /**
     * Decorator pass-through for calling Entity functions
     *
     * <pre>
     *      $instance = new EntityAccessDecorator($entity);
     *      $instance()->hasErrors();
     *      $instance()->isDirty();
     *
     *      return $instance;   //EntityAccessDecorator
     *      return $instance(); //Entity
     * </pre>
     *
     * @return Entity
     */
    public function __invoke(): Entity
    {
        return $this->_entity;
    }

    public function get(string $path): string
    {
        return Hash::get($this->_array, $path) ?? '';
    }

    public function extract(string $path): array
    {
        return Hash::extract($this->_array, $path);
    }

    /**
     * Name the paths, get an array of path values
     *
     * @param ...$paths
     * @return array
     */
    public function collate(...$paths): array
    {
        return \Cake\Collection\collection($paths)
            ->map(function ($path) {
                return $this->get($path);
            })
            ->toArray();
    }

    /**
     * List all valid dot-paths for this entity
     *
     * @return string[]
     */
    public function paths(): array
    {
        return $this->_paths;
    }

}
