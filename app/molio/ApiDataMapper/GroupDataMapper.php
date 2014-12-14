<?php namespace molio\ApiDataMapper;


Class GroupDataMapper extends DataMapper {


    public function mapper( $group )
    {

        return [
            'Id'        => $group['id'] ,
            'Name'      => $group['name'] ,
          
        ];

    }


}