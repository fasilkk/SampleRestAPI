<?php
namespace molio\Validation;

use Laracasts\Validation\FormValidator;

Class ContactForm extends FormValidator {


    protected $userId;

    protected $rules = [

        //'number' =>'required|min:10',

        'number' => ['required' , 'min:10' , 'regex:/^\+[1-9]{1}[0-9]{7,11}$/']


    ];

    public function setUserId($userId)
    {
        $this->userId =$userId;
        $this->rules =[

            //'number' =>'required|min:10',

            'number' => ['required' , 'min:10' , 'regex:/^\+[1-9]{1}[0-9]{7,11}$/',"unique:contacts,number,NULL,id,user_id,".$this->userId]


    ];
    }

    public function getUserId()
    {
       return $this->rules;
    }



    //


}
