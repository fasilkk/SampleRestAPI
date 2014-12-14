<?php namespace molio\ApiDataMapper;


Class UserDataMapper extends DataMapper{


    public function mapper($user){

        return [
                'username'  => $user['username'],
                'first name'=> $user['fname'],
                'last name' => $user['lname'],
                'address'   =>$user['address'],
        ];

    }

    public function userStatus($user)
    {
        return [

          'status' => ($user['status']) ? "Active" : "Deactivated",

        ];

    }

}