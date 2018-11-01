<?php

use Faker\Factory as Faker;

class ApiTester extends TestCase
{
    /**
     * @var \Faker\Generator
     */
    protected $fake;

    /**
     * @var int
     */
    protected $statFavitems;

    protected $group;

    protected $contacts = [];

    public function __construct()
    {
        $this->fake = Faker::create();
        $this->statFavitems = [

            'name'    => 'this is a test data',
            'address' => 'this is a test data',
            'lat'     => '18.920882',
            'lng'     => '72.897720',

        ];

        $this->contacts['numbers'] = ['+49162921322', '+49162921325', '+49162926397', '+49162928673', '+49162926548'];

        $this->group = ['name' => 'test Group'];
    }

    /**
     * setting up testing enviorment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->app['artisan']->call('migrate');

        Route::enableFilters();
    }

    public static function tearDownAfterClass()
    {
        Artisan::call('migrate:reset');
    }

    public function CreateUserFakerData($userFields = [])
    {
        $user = array_merge([

            'username' => $this->fake->username,
            'password' => $this->fake->name,
            'email'    => $this->fake->email,
            'fname'    => $this->fake->firstName,
            'lname'    => $this->fake->lastName,
            'address'  => $this->fake->address,

        ], $userFields);

        return $user;
    }

    public function getUserInvalidLoginData($userFields = [])
    {
        $user = array_merge([

            'username' => $this->fake->username,
            'password' => $this->fake->name,

        ], $userFields);

        return $user;
    }

    public function getUserValidLoginData($user)
    {
        return ['username' =>$user['username'], 'password'=>$user['password']];
    }

    public function sendRestPasswordRequest()
    {
    }

    public function getJson($uri, $user = [])
    {
        if (empty($user)) {
            return json_decode($this->call('GET', $uri)->getContent());
        } else {
            return json_decode($this->call('GET', $uri, $user)->getContent());
        }
    }

    public function postJson($uri, $user = [])
    {
        return json_decode($this->call('POST', $uri, $user)->getContent());
    }

    public function fileJson($uri, $user = [])
    {
        return json_decode($this->call('FILE', $uri, $user)->getContent());
    }

    public function putJson($uri, $user = [])
    {
        return json_decode($this->call('PUT', $uri, $user)->getContent());
    }

    public function deleteJson($uri, $user = [])
    {
        return json_decode($this->call('DELETE', $uri, $user)->getContent());
    }
}
