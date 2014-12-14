<?php namespace molio\ApiDataMapper;


Class FavoriteDataMapper extends DataMapper {


    public function mapper( $favorite )
    {

        return [
            'Id'        => $favorite['id'] ,
            'Name'      => $favorite['name'] ,
            'Address'   => $favorite['address'] ,
            'Latitude'  => $favorite['lat'] ,
            'Longitude' => $favorite['lng'] ,
        ];

    }


}