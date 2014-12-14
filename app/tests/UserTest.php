<?php


Class UserTest extends ApiTester {

    /**
     * Testing User create
     *
     * @return void
     */
    public function testUserCreate()
    {
        //arrange
        $user = $this->createUserFakerData();

        //action
        $return = $this->postJson('api/user/register' , $user)->message;


        //assert
        $this->assertEquals('Successfully Registered !' , $return);
        $this->assertResponseOk();

        return $user;

    }


    /**
     * Testing User Login with Valid Credentials
     * @depends testUserCreate
     * @return void
     */
    public function testUserLoginWithValidCredential( $user )
    {

        //arrange
        $userLogin = $this->getUserValidLoginData($user);


        //action
        $return = $this->postJson('api/user/login' , $userLogin)->message;


        //assert
        $this->assertEquals('Successfully Logged In !' , $return);
        $this->assertResponseOk();

        return Auth::user();

    }


    /**
     * Testing User Login with Invalid Credentials
     *
     * @return void
     */
    public function testUserLoginWithInValidCredential()
    {
        //arrange
        $user = $this->getUserInvalidLoginData();

        //action
        $return = $this->postJson('api/user/login' , $user)->error;


        //assert
        $this->assertEquals('Enter valid credentials !' , $return->message[0]);
        $this->assertResponseStatus(403);

    }

    /**
     * Testing User Page with Invalid Credentials
     *
     * @return void
     */
    public function testNonPrivilagedPages()
    {
        //action
        $return = $this->getJson('api/user/status')->error;

        //assert
        $this->assertEquals('Invalid Credentials' , $return->message);
        $this->assertResponseStatus(401);
    }

    /**
     * Testing User status
     * @depends testUserLoginWithValidCredential
     *
     */
    public function testgetStatus( $user )
    {

        //arrange
        $this->be($user);

        //action
        $return = $this->getJson('api/user/status');


        //assert
        $this->assertEquals('Active' , $return->status);
        $this->assertResponseOk();
    }

    /**
     * Test user update
     * @depends testUserLoginWithValidCredential
     *
     */
    public function testUpadteUser( $user )
    {

        //arrange
        $this->be($user);

        $newdata = $this->CreateUserFakerData();


        //action
        $return = $this->putJson('api/user/update' , $newdata)->message;


        //assert
        $this->assertEquals('Profile Updated successfully !' , $return);
        $this->assertResponseOk();
    }


    /**
     * Test user information
     * @depends testUserLoginWithValidCredential
     *
     */
    public function testGetUserData( $user )
    {
        //arrange
        $this->be($user);


        //action
        $return = (array) $this->getJson('api/user/data');
        $datamaper = new molio\ApiDataMapper\UserDataMapper();
        $result = json_encode($datamaper->mapper($user->toArray()));

        //assert
        $this->assertEquals($result , json_encode($return));
        $this->assertResponseOk();


    }


    /**
     * Test create userphone number
     * @depends testUserLoginWithValidCredential
     *
     */
    public function testCreateUserPhone( $user )
    {
        $this->be($user);

        $data['phone_number'] = $this->contacts['numbers'][0];


        //action
        $return = $this->putJson('api/user/mobilenumber/' . $user->id , $data)->message;

        
        //assert
        $this->assertEquals('Successfully updated phone number !' , $return);
        $this->assertResponseOk();


    }


    /**
     * Test user dissable account
     * @depends testUserLoginWithValidCredential
     *
     */
    public function testDissable( $user )
    {

        //arrange
        $this->be($user);

        //action
        $return = $this->deleteJson('api/user/' . $user->id)->message;


        //assert
        $this->assertEquals('Successfully Disabled Account !' , $return);
        $this->assertResponseOk();

    }


     /**
     * Test user logout account
     * @depends testUserLoginWithValidCredential
     *
     */
    public function testUserLogout( $user )
    {

        //arrange
        $this->be($user);

        //action
        $return = $this->getJson('api/user/logout')->message;


        //assert
        $this->assertEquals('Successfully Logged Out !' , $return);
        $this->assertResponseOk();

    }




    /**
     * Test user to reset password
     *
     * @return object
     */
     public function testSendResetPasswordTocken()
    {

        //arrange
        $user = User::create(array('username' => 'fasil' , 'fname' => 'teste l name' , 'lname' => 'brown' , 'email' => 'kk.fasil@yahoo.com' , 'address' => 'test address' , 'password' => 'mysecretpass'));
        $this->be($user);

        $data = array('email' => $user->email);

        //action
        $return = $this->postJson('api/user/password/remind' , $data)->message;


        //assert
        $this->assertEquals('password token sent successfully to your email !' , $return);
        $this->assertResponseOk();

        return $user;

    }

    /**
     * Test user to reset password
     * @depends testSendResetPasswordTocken
     *
     */
    public function testVerifyPasswordResetToken( $user )
    {
        //arrange
        $this->be($user);


        $token = DB::table(Config::get('auth.reminder.table'))->where('email' , Auth::user()->email)->pluck('token');

        $data = array('email' => Auth::user()->email , 'password' => 'resetpass' , 'password_confirmation' => 'resetpass' , 'token' => $token);


        //action
        $return = $this->postJson('api/user/password/reset' , $data)->message;


        //assert
        $this->assertEquals('Password Reset Succesfully !' , $return);

        $this->assertResponseOk();


    }


    /**
     * Test for user update password after logged in
     * @depends testUserLoginWithValidCredential
     *
     */
    public function testUserPasswordUpdate( $user )
    {

        //arrange
        $this->be($user);

        $input = array('password' => 'newpass##' , 'passwordmatch' => 'newpass##');

        //action
        $return = $this->postJson('api/user/password/update' , $input)->message;


        //assert
        $this->assertEquals('Password Updated successfully !' , $return);
        $this->assertResponseOk();

    }


}
