<?php

use Laracasts\Validation\FormValidationException;
use molio\ApiDataMapper\ContactDataMapper;
use molio\ApiDataMapper\GroupDataMapper;
use molio\Validation\ContactForm;
use molio\Validation\ContactGroupForm;

class ContactController extends ApiController
{
    //contact form validator
    protected $contactGroupFormValidation;
    protected $contactFormValidation;

    //data mapper
    protected $groupDataMapper;
    protected $contactDataMapper;

    public function __construct(ContactGroupForm $contactGroupFormValidation, ContactForm $contactFormValidation, GroupDataMapper $groupDataMapper, ContactDataMapper $contactDataMapper)
    {
        $this->beforeFilter('auth.basic');

        //validations
        $this->contactGroupFormValidation = $contactGroupFormValidation;
        $this->contactFormValidation = $contactFormValidation;

        //data mapper
        $this->groupDataMapper = $groupDataMapper;
        $this->contactDataMapper = $contactDataMapper;

        // $this->contactFormValidation->setUserId(Auth::user()->id);
    }

    /**
     * Display a listing of the resource.
     * GET /contacts.
     *
     * @return Response
     */
    public function index()
    {
        //current user object
        $user = Auth::user();

        if ($user->groups != null) {
            return $this->responseSuccessWithOnlyData($this->groupDataMapper->mapCollection($user->groups->toArray()));
        } else {
            return $this->responseSuccessWithOnlyData([]);
        }
    }

    /**
     * Display a listing of contacts
     * GET /contacts.
     *
     * @return Response
     */
    public function contactIndex()
    {
        //input the nummbers array "numbers[]"
        $inputs = Input::only('numbers');

        //user object
        $user = Auth::user();

        try {
            if (is_array($inputs['numbers'])) {
                foreach ($inputs['numbers'] as $value) {
                    $data = ['number' => $value];

                    $this->contactFormValidation->validate($data);
                }

                $contacts = Contact::whereIn('number', $inputs['numbers'])->where('user_id', '=', $user->id)->get();

                return $this->responseSuccessWithOnlyData($this->contactDataMapper->mapCollection($contacts->toArray()));
            }

            return $this->responseWithError(['numbers[]' => 'Enter Valid Numbers']);
        } catch (FormValidationException $e) {
            //returning errors from Exception
            //Format = error { message : {"field" :['error'], "field2" :['error2'],.... } }
            return $this->responseWithError($e->getErrors()->toArray());
        }
    }

    /**
     * Create a new Contact Group
     * POST /contacts.
     *
     * @return Response
     */
    public function store()
    {
        //current user object
        $user = Auth::user();

        $inputs = Input::only('name');

        //validating Inputs
        try {
            $this->contactGroupFormValidation->validate($inputs);

            //now store data to group table
            $grpTable = new \Group();
            $grpTable->name = $inputs['name'];
            $grpTable->user()->associate($user)->save();

            return $this->responseSuccess('Successfully Created New Group !');
        } catch (FormValidationException $e) {
            //returning errors from Exception
            //Format = error : { message : {"field" :['error'], "field2" :['error2'],.... } }
            return $this->responseWithError($e->getErrors()->toArray());
        }
    }

    /**
     * Update the group data
     * PUT /contacts/groups/{id}.
     *
     * @param Model $group
     *
     * @return Response
     */
    public function update($group)
    {

        //user Object
        $user = Auth::user();

        //checking the requested id belongs to the logged in user
        if ($group->user->id == $user->id) {
            try {
                $inputs = Input::only('name');
                //validating Inputs
                $this->contactGroupFormValidation->validate($inputs);
                $group->name = $inputs['name'];
                $group->save();

                return $this->responseSuccess('Successfully Updated Group !');
            } catch (FormValidationException $e) {
                //returning errors from Exception
                //Format = error { message : {"field" :['error'], "field2" :['error2'],.... } }
                return $this->responseWithError($e->getErrors()->toArray());
            }
        } else {
            return $this->responseForbidden('You are not authorized to access this data !');
        }
    }

    /**
     * Delete a group by its id
     * DELETE /contacts/groups/{group}.
     *
     * @param Model $group
     *
     * @return Response
     */
    public function destroy($group)
    {
        //user Object
        $user = Auth::user();

        //checking the requested id belongs to the logged in user
        if ($group->user->id == $user->id) {
            $group->delete();

            return $this->responseSuccess('Successfully deleted the group !');
        }

        return $this->responseForbidden('You are not authorized to access this data !');
    }

    /**
     * get all the contacts of a group
     * GET /contacts/groups/{groupId}/links.
     *
     * @param Model $group
     *
     * @return Response
     */
    public function getContact($group)
    {

        //current user object
        $user = Auth::user();

        if ($user->groups != null) {
            return $this->responseSuccessWithOnlyData($this->contactDataMapper->mapCollection($group->contact->toArray()));
        } else {
            return $this->responseSuccessWithOnlyData([]);
        }
    }

    /**
     * Create new contacts in a group
     * POST /contacts/groups/{groupId}/links.
     *
     * @param Model $group
     *
     * @return Response
     */
    public function postContact($group)
    {

        //input the nummbers array "numbers[]"
        $inputs = Input::only('numbers');

        //user object
        $user = Auth::user();

        if ($group->user->id == $user->id) {
            try {
                if (is_array($inputs['numbers'])) {
                    foreach ($inputs['numbers'] as $value) {
                        $data = ['number' => $value];

                        $this->contactFormValidation->validate($data);
                    }

                    foreach ($inputs['numbers'] as $value) {
                        $data = ['number' => $value];

                        $contact = new Contact($data);
                        $contact->user()->associate($user);
                        $contact->group()->associate($group);
                        $contact->save();
                    }

                    return $this->responseSuccess('Successfully Added Contacts !');
                }

                return $this->responseWithError(['numbers[]' => 'Enter Valid Numbers']);
            } catch (FormValidationException $e) {

                //returning errors from Exception
                //Format = error { message : {"field" :['error'], "field2" :['error2'],.... } }
                return $this->responseWithError($e->getErrors()->toArray());
            }
        }

        return $this->responseForbidden('You are not authorized to access this data !');
    }

    /**
     * Delete contacts from a group
     * DELETE /contacts/groups/{groupId}/links.
     *
     * @param Model $group
     *
     * @return Response
     */
    public function deleteContact($group)
    {

        //input the nummbers array "numbers[]"
        $inputs = Input::only('numbers');

        //user object
        $user = Auth::user();

        if ($group->user->id == $user->id) {
            try {
                if (is_array($inputs['numbers'])) {
                    foreach ($inputs['numbers'] as $value) {
                        $data = ['number' => $value];

                        $this->contactFormValidation->validate($data);
                    }

                    $deleteFlag = 0;

                    foreach ($inputs['numbers'] as $value) {
                        $contact = Contact::whereNumber($value)->first();

                        if (isset($contact->id) && $group->id == $contact->group_id) {
                            $contact->delete();
                            $deleteFlag++;
                        }
                    }

                    if ($deleteFlag == 0) {
                        return $this->responseWithError('No contacts Deleted !');
                    } elseif ($deleteFlag < count($inputs['numbers'])) {
                        return $this->responseWithError('some contacts Deleted !');
                    } else {
                        return $this->responseSuccess('Successfully Deleted Contacts !');
                    }
                }

                return $this->responseWithError(['numbers[]' => 'Enter Valid Numbers']);
            } catch (FormValidationException $e) {
                //returning errors from Exception
                //Format = error { message : {"field" :['error'], "field2" :['error2'],.... } }
                return $this->responseWithError($e->getErrors()->toArray());
            }
        } else {
            return $this->responseForbidden('You are not authorized to access this data !');
        }
    }
}
