## Laravel PHP Framework

## URL

        | URI                                                                          | Action                           |
        |  ---------------------------------------------------------------------------:|---------------------------------:|
        | POST api/user/register                                                       | UserController@register          |
        | POST api/user/login                                                          | UserController@login             |
        | GET api/user/logout                                                          | UserController@logout            |
        | POST api/user/password/remind                                                | RemindersController@postRemind   |
        | GET|HEAD api/user/password/reset                                             | RemindersController@getReset     |
        | POST api/user/password/reset                                                 | RemindersController@postReset    |
        | GET|HEAD api/user/password/staus-code                                        | RemindersController@getStausCode |
        | GET api/user/status                                                          | UserController@status            |
        | GET api/user/{user}                                                          | UserController@show              |
        | PUT api/user/{user}                                                          | UserController@update            |
        | PATCH api/user/{user}                                                        | UserController@update            |
        | DELETE api/user/{user}                                                       | UserController@destroy           |
        |                                                                                                                 |
        | POST api/user/password/update                                                | UserController@postUserPasswordUpdate |                                |
        | POST api/user/profile/image                                                  | UserController@postUserImage     |
        | POST api/favorite                                                            | FavoriteController@store         |
        | GET api/favorite/count                                                       | FavoriteController@count         |
        | GET|HEAD api/favorites                                                        | FavoriteController@index         |
        | PUT api/favorites/{favorite}                                                  | FavoriteController@update        |
        | PATCH api/favorites/{favorite}                                                | FavoriteController@update        |
        | DELETE api/favorites/{favorite}                                               | FavoriteController@destroy       |



## Register fields
 
 > 'username'    :    'required|unique:users',
 
 > 'email'       :    'required|unique:users|email',
 
 > 'password'    :    'required',
 
 > 'fname'       :    'required|min:3|max:30',
 
 > 'lname'       :    'required|max:20',
 
 > 'address'     :    'required|min:10',



## Login

> 'username' :  'required',

> 'password' :  'required'



#Post image

    'image': 'required'


#password

    >'password':      'required|min:5'
    
    >'passwordmatch': 'required|same:password|min:5'


#Post favorites:

    'name':    'required|min:2'

    'address': 'required|min:5'

    'lat' :    'required'

    'lng' :    'required'


#Update favorites


    'name' :  'required|min:2'

    Note: Laravel's PATCH and PUT request are just cheating, by adding a hidden field. Ex: <input type="hidden" name="_method" value="PATCH">. Laravel will create such a field automatically in all laravel's HTML forms. In postman you should point that the data you send is 'x-www-url-formurlencoded' for PATCH. Alternatively your can sent a POST request with an hidden field named _method and value PATCH.
