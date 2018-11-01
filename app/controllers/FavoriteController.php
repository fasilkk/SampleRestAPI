<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Laracasts\Validation\FormValidationException;
use molio\ApiDataMapper\FavoriteDataMapper;
use molio\Validation\FavoriteForm;
use molio\Validation\FavoriteUpdateForm;

class FavoriteController extends ApiController
{
    //favorite form validator
    protected $favoriteFormValidation;
    protected $favoriteUpdateFormValidation;

    //data mapper
    protected $favoriteDataMapper;

    public function __construct(FavoriteForm $favoriteFormValidation, FavoriteUpdateForm $favoriteFormUpdateValidation, FavoriteDataMapper $favoriteDataMapper)
    {
        $this->beforeFilter('auth.basic');
        $param = Route::current()->getParameter('favorite');
        $this->beforeFilter('favFilter:'.$param, ['only' => 'show']);

        //validations
        $this->favoriteFormValidation = $favoriteFormValidation;
        $this->favoriteFormUpdateValidation = $favoriteFormUpdateValidation;

        //data mapper
        $this->favoriteDataMapper = $favoriteDataMapper;
    }

    /**
     * Display a listing of favorites of the user
     * GET api/favorite.
     *
     * @return json
     */
    public function index()
    {
        //get logged user
        $user = Auth::user();

        //return favorite datas
        return $this->responseSuccessWithOnlyData($this->favoriteDataMapper->mapCollection($user->favorite->toArray()));
    }

    /**
     * create new favorite for the user
     * GET /favorite/create.
     *
     * @return Response
     */
    public function store()
    {
        $inputs = Input::only('name', 'address', 'lat', 'lng');

        //validating Inputs
        try {
            $this->favoriteFormValidation->validate($inputs);

            //now store data to favorite table
            $user = Auth::user();
            $favTable = new \Favorite();
            $favTable->name = $inputs['name'];
            $favTable->address = $inputs['address'];
            $favTable->lat = $inputs['lat'];
            $favTable->lng = $inputs['lng'];
            $favTable->user()->associate($user)->save();

            return $this->responseSuccess('Successfully Created Favorite Item !');
        } catch (FormValidationException $e) {
            //returning errors from Exception
            //Format = error : { message : {"field" :['error'], "field2" :['error2'],.... } }
            return $this->responseWithError($e->getErrors()->toArray());
        }
    }

    /**
     * Display the count of the favourate item
     * GET /favorite/{id}.
     *
     * @return json
     */
    public function count()
    {
        $user = Auth::user();

        return $this->responseSuccessWithOnlyData(
            [
                'count' => $user->favorite()->count(),
            ]
        );
    }

    /**
     * Update the favorite iten.
     * PUT /favorite/{id}.
     *
     * @param int $favorite
     *
     * @return json
     */
    public function update($favorite)
    {
        $user = Auth::user();

        //checking the requested id belongs to the logged in user
        if ($favorite->user->id == $user->id) {
            try {
                $inputs = Input::only('name');
                //validating Inputs
                $this->favoriteFormUpdateValidation->validate($inputs);
                $favorite->name = $inputs['name'];
                $favorite->save();

                return $this->responseSuccess('Successfully Updated favorite item !');
            } catch (FormValidationException $e) {
                //returning errors from Exception
                //Format = error { message : {"field" :['error'], "field2" :['error2'],.... } }
                return $this->responseWithError($e->getErrors()->toArray());
            }
        } else {
            return $this->responseForbidden('This service is Unavailable !');
        }
    }

    /**
     * Remove the favorite item
     * DELETE /favorite/{id}.
     *
     * @param int $favorite
     *
     * @return json
     */
    public function destroy($favorite)
    {
        $user = Auth::user();

        //checking the requested id belongs to the logged in user
        if ($favorite->user->id == $user->id) {
            $favorite->delete();

            return $this->responseSuccess('Successfully Deleted favorite item !');
        } else {
            return $this->responseForbidden('This service is Unavailable !');
        }
    }
}
