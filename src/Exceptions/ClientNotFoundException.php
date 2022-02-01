<?php

namespace Soroosh\FinnotechClient\Exceptions;

use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClientNotFoundException extends HttpResponseException
{
    /**
     * @var mixed
     */
    protected $response;

    /**
     * @param string $message
     * @param int $code
     */
    public function __construct($message, $code)
    {
        parent::__construct(response()->json(["message" => $message], $code));
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
