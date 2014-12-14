<?php

Class ContactTest extends ApiTester {

    /**
     * Creatign a dummy user to test contact functions
     * @return userobject
     */
    public function testInitUser()
    {
        #creating user
        //arrange
        $user = $this->createUserFakerData();

        //action
        $return = $this->postJson('api/user/register' , $user)->message;


        //assert
        $this->assertEquals('Successfully Registered !' , $return);
        $this->assertResponseOk();


        return Auth::user();

    }


    /**
     * Testing Group create
     * @depends testInitUser
     * @return object
     */
    public function testGroupCreate( $user )
    {

        //arrange
        $this->be($user);


        //action
        $return = $this->postJson('api/contacts/groups' , $this->group)->message;


        //assert
        $this->assertEquals('Successfully Created New Group !' , $return);
        $this->assertResponseOk();

        return Auth::user();

    }


    /**
     * Testing contact group display
     * @depends testGroupCreate
     * @return object
     */
    public function testgetGroups( $user )
    {

        //arrange
        $this->be($user);

        //action
        $return = $this->getJson('api/contacts/groups');


        //assert
        $this->assertEquals($this->group['name'] , $return->data[0]->Name);
        $this->assertResponseOk();

        return Auth::user();

    }


    /**
     * Testing Group data update
     * @depends testGroupCreate
     * @return object
     */
    public function testGroupDataUpdate( $user )
    {

        //arrange
        $this->be($user);
        $grpid = Group::where('name' , '=' , $this->group['name'])->first()->id;
        $this->group['name'] = "Updated Name";


        //action
        $return = $this->putJson('api/contacts/groups/' . $grpid , $this->group)->message;


        $updatedName = Group::where('name' , '=' , $this->group['name'])->first()->name;


        //assert
        $this->assertEquals($this->group['name'] , $updatedName);
        $this->assertEquals('Successfully Updated Group !' , $return);


        $this->assertResponseOk();

        return Auth::user();

    }


    /**
     * Testing Create new Contact datas
     * @depends testGroupDataUpdate
     * @return object
     */
    public function testContactsCreate( $user )
    {

        $datas = $this->contacts;


        //arrange
        $this->be($user);
        $this->group['name'] = "Updated Name";
        $grpid = Group::where('name' , '=' , $this->group['name'])->first()->id;


        //action
        $return = $this->postJson('api/contacts/groups/' . $grpid . '/links' , $datas)->message;


        //assert
        $this->assertEquals('Successfully Added Contacts !' , $return);

        $this->assertResponseOk();

        return Auth::user();

    }



  /**
     * Testing Create new Contact datas
     * @depends testContactsCreate
     * @return object
     */
    public function testSearchContacts( $user )
    {

        $datas = $this->contacts;

        $datas[] = ['+49161234567'];
        //arrange
        $this->be($user);
        

        //action
        $return = (array) $this->postJson('api/contacts/', $datas)->data;

        $resultArray =array();
        foreach($return as $value)
        {

           $resultArray[] = $value->Number;
        }
        
        if(sizeof(array_diff($resultArray,$this->contacts['numbers']))==0)
           $return ='Fine';
        else 
            $return ='Faild';
        //assert
        $this->assertEquals('Fine' , $return);

        $this->assertResponseOk();

        return Auth::user();

    }




    /**
     * Testing display contact datas under a group
     * @depends testContactsCreate
     * @return object
     */
    public function testContactsDisplay( $user )
    {

        $datas = $this->contacts;


        //arrange
        $this->be($user);
        $this->group['name'] = "Updated Name";
        $grpid = Group::where('name' , '=' , $this->group['name'])->first()->id;


        //action
        $return = $this->getJson('api/contacts/groups/' . $grpid . '/links' , $datas);


        //assert
        $this->assertResponseOk();

        return Auth::user();

    }


    /**
     * Testing delteting multiple contacts
     * @depends testContactsCreate
     * @return object
     */
    public function testContactsDelete( $user )
    {

        $datas['numbers'] = array($this->contacts['numbers'][1] , $this->contacts['numbers'][3]);

        
        //arrange
        $this->be($user);

        $this->group['name'] = "Updated Name";
        $grpid = Group::where('name' , '=' , $this->group['name'])->first()->id;
        //dd($user->id.":::".$grpid);

        //action
        $return = $this->deleteJson('api/contacts/groups/' . $grpid . '/links' , $datas)->message;
        //dd($return);
        
        $this->assertEquals('Successfully Deleted Contacts !' , $return);


        //assert
        $this->assertResponseOk();

        return Auth::user();

    }


    /**
     * Testing Group item delete
     * @depends testContactsDelete
     * @return object
     */
    public function testGroupDelete( $user )
    {


        //arrange
        $this->be($user);
        $this->group['name'] = "Updated Name";
        $grpid = Group::where('name' , '=' , $this->group['name'])->first()->id;


        //action
        $return = $this->deleteJson('api/contacts/groups/' . $grpid)->message;


        //assert
        $this->assertEquals('Successfully deleted the group !' , $return);

        $this->assertResponseOk();

        return Auth::user();

    }


}
