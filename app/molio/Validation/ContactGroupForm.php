<?php
namespace molio\Validation;
use Laracasts\Validation\FormValidator;
Class ContactGroupForm extends FormValidator{

    protected  $rules =[

        'name' =>'required|min:2',
        

    ];



}