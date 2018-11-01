<?php

namespace molio\ApiDataMapper;

abstract class DataMapper
{
    public function mapCollection(array $collection)
    {
        return array_map([$this, 'mapper'], $collection);
    }

    abstract public function mapper($collection);
}
