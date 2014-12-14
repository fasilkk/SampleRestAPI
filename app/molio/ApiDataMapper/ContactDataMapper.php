<?php namespace molio\ApiDataMapper;


Class ContactDataMapper extends DataMapper {


    public function mapper( $contact )
    {

        return [
            'Id'        => $contact['id'] ,
            'Number'      => $contact['number'] ,
            
        ];

    }


}