<?php

namespace Soroosh\FinnotechClient\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException;

class InvalidBillTypeException extends HttpResponseException
{
    /**
     * @var mixed
     */
    protected $response;

    /**
     * @param string $message
     * @param int $code
     */
    public function __construct($code)
    {
        parent::__construct(response()->json(["message" => "invalid.bill-type"], $code));
    }

    /**
     * Returns the exception's response body.
     *
     */
    public function getResponseBody()
    {
        return $this->response;
    }
}
