<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Laracasts\Validation\FormValidationException;
use molio\ApiDataMapper\UserDataMapper;
use molio\Validation\ImageUploadForm;
use molio\Validation\LoginForm;
use molio\Validation\PasswordForm;
use molio\Validation\RegistrationForm;
use molio\Validation\UpdateForm;
use molio\Validation\ContactForm;

class UserController extends ApiController {

    private $registrationform;
    private $loginform;
    private $updateform;
    protected $userdatamapper;
    protected $passwordform;
    protected $imageuploadform;
    protected $contactForm;

    /**
     * init user filter and form validation
     *
     */


    function __construct( RegistrationForm $registrationForm , LoginForm $loginform , UpdateForm $updateform , UserDataMapper $userdatamapper , PasswordForm $passwordform , ImageUploadForm $imageuploadform ,ContactForm $contactForm )
    {

        $this->beforeFilter('auth.basic' , array('except' => array('login' , 'register')));
        $id = Route::current()->getParameter('user');
        $this->beforeFilter('userstatus:' . $id , array('only' => 'show'));

        $this->registrationform = $registrationForm;
        $this->loginform = $loginform;
        $this->updateform = $updateform;
        $this->userdatamapper = $userdatamapper;
        $this->passwordform = $passwordform;
        $this->imageuploadform = $imageuploadform;
        $this->contactForm = $contactForm;

    }

    /**
     * Display user data
     *
     * @return json
     */
    public function index()
    {
        return $this->response($this->userdatamapper->mapper(Auth::user()->toArray()));
    }

    /**
     * Register new user
     *
     * @return jsom
     */
    public function register()
    {

        try
        {
            //get all input
            $inputs = Input::only('username' , 'password' , 'email' , 'fname' , 'lname' , 'address');

            // validating inputs  required fields(username,password,email,fname,lname,address) and username and email fields are unique
            $this->registrationform->validate($inputs);

            $user = User::create(Input::only('username' , 'password' , 'email' , 'fname' , 'lname' , 'address'));
            Auth::login($user);

            return $this->responseSuccess('Successfully Registered !');
        } catch (FormValidationException $e)
        {
            //returning errors from Exception
            //Format = error : { message : {"field" :['error'], "field2" :['error2'],.... } }
            return $this->responseWithError($e->getErrors()->toArray());

        }

    }

    /**
     *  user login function
     * @return json
     * @throws \Laracasts\Validation\FormValidationException
     */
    public function login()
    {

        try
        {
            //get all input
            $inputs = Input::only('username' , 'password');
            $inputs['status'] = true;

            //validating username and password fields
            $this->loginform->validate($inputs);

            if ( Auth::attempt($inputs) )
            {
                return $this->responseSuccess('Successfully Logged In !');

            } else
            {
                return $this->responseForbidden('Enter valid credentials !');

            }
        } catch (FormValidationException $e)
        {
            //returning errors from ValidationException
            //Format = error : { message : {"field" :['error'], "field2" :['error2'],.... } }
            return $this->responseWithError($e->getErrors()->toArray());

        }


    }

    /**
     * user logout function
     * @return json
     */
    public function logout()
    {
        //loggin out session
        Auth::logout();
        Session::flush();

        return $this->responseSuccess('Successfully Logged Out !');
    }

    /**
     * user status
     * @return json
     *
     */
    public function status()
    {
        //return user status if enabled then says Active otherwise Deactivated
        return $this->response($this->userdatamapper->userStatus(Auth::user()->toArray()));
    }


    /**
     * Display the user information
     *
     * @param  int $id
     * @return json
     */
    public function show( $id )
    {
        //showing current logged in user data
        return $this->response($this->userdatamapper->mapper(Auth::user()->toArray()));
    }


    /**
     * Update the user storage
     *
     * @param  int $id
     * @return json
     */
    public function update( $id )
    {
        try
        {

            $inputs = Input::all('fname' , 'lname' , 'address','email');

            //validation for input fields fname lname and address if exsist
            $this->updateform->validate($inputs);

            //updating user
            $user = Auth::user();
            $user->fname = (Input::has('fname')) ? Input::get('fname') : $user->fname;
            $user->lname = (Input::has('lname')) ? Input::get('lname') : $user->lname;
            $user->address = (Input::has('address')) ? Input::get('address') : $user->address;
            $user->email = (Input::has('email')) ? Input::get('email') : $user->email;
            $user->save();

            return $this->responseSuccess('Profile Updated successfully !');
        } catch (FormValidationException $e)
        {
            //Format = error : { message : {"field" :['error'], "field2" :['error2'],.... } }
            return $this->responseWithError($e->getErrors()->toArray());

        }
    }


    /**
     * Dissabling Current user account.
     *
     * @param  int $id
     * @return json
     */
    public function destroy( $id )
    {
        //gettin logged user
        $user = Auth::user();

        //saving user status to false so that next time usercant login
        $user->status = false;
        $user->save();

        //login out the current user
        Auth::logout();

        return $this->responseSuccess('Successfully Disabled Account !');

    }


    /**
     * Updating current user Image
     *
     * @return json
     */
    public function postUserImage()
    {
        try
        {
            if ( !Input::hasFile('image') )
            {
                return $this->responseWithError("Please upload an image !");
            }

            $user = Auth::user();
            //validating image field
            $this->imageuploadform->validate(Input::all());

            //saving user image
            $InputImageField = Input::file('image');
            $destinationPath = 'public/img/';
            $extension = $InputImageField->getClientOriginalExtension();
            $filename = 'usr_' . Auth::user()->username . '.' . $extension;
            $InputImageField->move($destinationPath , $filename);
            $user->image = $filename;
            $user->save();

            return $this->responseSuccess('Profile Picture Updated successfully !');

        } catch (FormValidationException $e)
        {
            //validation fails
            //Format = error : { message : {"field" :['error'], "field2" :['error2'],.... } }
            return $this->responseForbidden($e->getErrors()->toArray());

        }

    }

    /**
     * Updating current user password
     *
     * @return json
     */
    public function postUserPasswordUpdate()
    {

        try
        {
            //validating password field
            $this->passwordform->validate(Input::all());

            //saving password
            $user = Auth::user();
            $user->password = Input::get('password');
            $user->save();

            return $this->responseSuccess('Password Updated successfully !');


        } catch (FormValidationException $e)
        {
            //validating fails
            //Format = error : { message : {"field" :['error'], "field2" :['error2'],.... } }
            return $this->responseForbidden($e->getErrors()->toArray());

        }

    }

    /**
     * Updating/Create current user mobile number
     * @param $userId
     * @return json
     */
    public function mobileNumber( $userId )
    {

        $user = Auth::user();

        if ( $userId == $user->id )
        {

            $input = Input::only('phone_number');

            try
            {

            $this->contactForm->validate(array('number'=>$input['phone_number']));
         
           
            $profile = ($user->profile != null) ? Profile::find($user->profile->id) : new Profile();

            $profile->phone_number = $input['phone_number'];

            $profile->user()->associate($user)->save();

            return $this->responseSuccess("Successfully updated phone number !");

              } catch (FormValidationException $e)
            {
            //validating fails
            //Format = error : { message : {"field" :['error'], "field2" :['error2'],.... } }
            return $this->responseForbidden($e->getErrors()->toArray());

            }
   





        } else
            return $this->responseForbidden("You are not authorized to access this page !");

    }


}