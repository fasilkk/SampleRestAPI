<?php


use Illuminate\Support\Facades\Response;

Class FavoriteItemTest extends ApiTester {

	/**
	 * Testing Creating a new favorite Item
	 *
	 * @return userobject
	 */
	public function testInitUser()
	{
        #creating user
        //arrange
        $user = $this->createUserFakerData();

        //action
        $return = $this->postJson('api/user/register',$user)->message;


        //assert
        $this->assertEquals('Successfully Registered !',$return);
        $this->assertResponseOk();



        return Auth::user();

	}


    /**
     * Testing Favorite item data create
     * @depends testInitUser
     * @return object
     */
    public function testFavoriteItemCreate($user)
    {

        //arrange
        $this->be($user);

        //action
        $return = $this->postJson('api/favorites',$this->statFavitems)->message;


        //assert
        $this->assertEquals('Successfully Created Favorite Item !',$return);
        $this->assertResponseOk();

        return Auth::user();

    }


    /**
     * Testing Favorite item data display
     * @depends testFavoriteItemCreate
     * @return object
     */
    public function testFavoriteItemDisplay($user)
    {

        //arrange
        $this->be($user);

        //action
        $return = $this->getJson('api/favorites',$this->statFavitems);


        //assert
        $this->assertEquals($this->statFavitems['name'],$return->data[0]->Name);
        $this->assertEquals($this->statFavitems['address'],$return->data[0]->Address);
        $this->assertEquals($this->statFavitems['lat'],$return->data[0]->Latitude);
        $this->assertEquals($this->statFavitems['lng'],$return->data[0]->Longitude);
        $this->assertResponseOk();

        return Auth::user();

    }


    /**
     * Testing Favorite item data update
     * @depends testFavoriteItemCreate
     * @return object
     */
    public function testFavoriteItemUpdate($user)
    {

        //arrange
        $this->be($user);
        $favid =Favorite::where('name','=',$this->statFavitems['name'])->first()->id;
        $this->statFavitems['name'] = "Updated Name";



        //action
        $return = $this->putJson('api/favorites/'.$favid,$this->statFavitems)->message;



        $updatedName =Favorite::where('name','=',$this->statFavitems['name'])->first()->name;



        //assert
        $this->assertEquals($this->statFavitems['name'],$updatedName);
        $this->assertEquals('Successfully Updated favorite item !',$return);



        $this->assertResponseOk();

        return Auth::user();

    }


    /**
     * Testing Favorite item data delete
     * @depends testFavoriteItemUpdate
     * @return object
     */
    public function testFavoriteItemDelete($user)
    {

        //arrange
        $this->be($user);
        $this->statFavitems['name'] = "Updated Name";
        $favid =Favorite::where('name','=',$this->statFavitems['name'])->first()->id;


        //action
        $return = $this->deleteJson('api/favorites/'.$favid)->message;


        //assert
        $this->assertEquals('Successfully Deleted favorite item !',$return);

        $this->assertResponseOk();

        return Auth::user();

    }



}
