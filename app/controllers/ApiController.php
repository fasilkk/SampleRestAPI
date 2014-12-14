<?php
use Illuminate\Http\Response as IlluminateResponse;

Class ApiController extends \BaseController {


    /**
     * @var int
     */
    protected $stausCode = 200;

    /**
     * @var string
     */
    protected $message;

    /**
     * @param $message
     * @return json
     */
    public function responseNotFound( $message )
    {
        return $this->setStausCode(404)->responseWithError($message);
    }

    /**
     * @param string $message
     * @return json
     */
    public function responseWithError( $message = "" )
    {
        return $this->response(array(
            "error" => array("message"     => $message ,
                             "status_code" => $this->getStausCode())

        ));

    }

    /**
     * @param $data
     * @param array $headers
     * @return json
     */
    public function response( $data , $headers = [] )
    {
        return Response::json($data , $this->getStausCode() , $headers);
    }

    /**
     * @return int
     */
    public function getStausCode()
    {
        return $this->stausCode;
    }

    /**
     * @param int $stausCode
     *
     * @return $this
     */
    public function setStausCode( $stausCode )
    {
        $this->stausCode = $stausCode;

        return $this;
    }

    /**
     * @param $message
     * @return json
     */
    public function responseSuccess( $message )
    {
        return $this->setStausCode(IlluminateResponse::HTTP_OK)->response(array("message" => $message));

    }

    /**
     * @param $data
     * @return json
     */
    public function responseSuccessWithOnlyData( $data )
    {
        return $this->setStausCode(IlluminateResponse::HTTP_OK)->response(array("data" => $data));

    }

    /**
     * @param $message
     * @return json
     */
    public function responseForbidden( $message )
    {
        return $this->setStausCode(IlluminateResponse::HTTP_FORBIDDEN)->responseWithError(array($message));

    }

    /**
     * @param $message
     * @return json
     */
    public function responseInternalError( $message )
    {
        return $this->setStausCode(IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR)->responseWithError(array($message));
    }


}