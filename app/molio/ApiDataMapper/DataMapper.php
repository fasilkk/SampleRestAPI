<?php
namespace molio\ApiDataMapper;
abstract class DataMapper{

    public function mapCollection(array $collection)
    {


        return array_map([$this,'mapper'] , $collection);


    }

    public abstract function mapper($collection);

}