<?php

namespace molio\ApiDataMapper;

class ContactDataMapper extends DataMapper
{
    public function mapper($contact)
    {
        return [
            'Id'          => $contact['id'],
            'Number'      => $contact['number'],

        ];
    }
}
