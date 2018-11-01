<?php

namespace molio\ApiDataMapper;

class GroupDataMapper extends DataMapper
{
    public function mapper($group)
    {
        return [
            'Id'        => $group['id'],
            'Name'      => $group['name'],

        ];
    }
}
