<?php

/**
 * HTTP errors
 */
class PayPlug_HttpException extends PayPlug_PayPlugException
{
    private $_httpResponse;

    /**
     * @param string $message the exception message
     * @param string $httpResponse the http response content
     * @param int $code the exception code
     * @param Exception $previous previous exception
     */
    public function __construct($message, $httpResponse = null, $code = 0, Exception $previous = null)
    {
        $this->_httpResponse = $httpResponse;
        parent::__construct($message, $code, $previous);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}; HTTP Response: {$this->_httpResponse}\n";
    }

    /**
     * @return string the HTTP response
     */
    public function getHttpResponse()
    {
        return $this->_httpResponse;
    }

    /**
     * @return array|null the error array if it was a valid JSON, null otherwise.
     */
    public function getErrorObject()
    {
        return json_decode($this->_httpResponse, true);
    }
}

/**
 * 400 Bad Request
 */
class PayPlug_BadRequest extends PayPlug_HttpException
{
}

/**
 * 401 Unauthorized
 */
class PayPlug_Unauthorized extends PayPlug_HttpException
{
}

/**
 * 403 Forbidden
 */
class PayPlug_Forbidden extends PayPlug_HttpException
{
}

/**
 * 404 Not Found
 */
class PayPlug_NotFound extends PayPlug_HttpException
{
}

/**
 * 405 Not Allowed
 */
class PayPlug_NotAllowed extends PayPlug_HttpException
{
}

/**
 * 5XX server errors
 */
class PayPlug_PayPlugServerException extends PayPlug_HttpException
{
}