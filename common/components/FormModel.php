<?php

namespace common\components;

use Yii;

/**
 * FormModel form
 */
trait FormModel
{
    /**
     * @var
     */
    private $_response_code = null;

    /**
     * @var array
     */
    private $_responses = [

        //User
        100 => "Incorrect phone or password",
        101 => "User status is inactive.",
        102 => "Confirmation code was sent to your phone.",
        103 => "User was deleted.",
        104 => "This user was already confirmed.",
        105 => "Confirmation code is not valid.",
        106 => "Forgot password code sent to your phone",

        //Transport
        200 => "Transport not found.",

        //Document
        300 => "Document not found.",

        //Route
        400 => "Route not found.",

        //Route
        500 => "Package not found.",

        //Page
        600 => "Page not found.",

        //Page
        700 => "Chat not found.",

    ];

    /**
     * @var
     */
    private $_response_body = false;

    /**
     * @param $code
     */
    public function setResponseCode($code)
    {
        $this->_response_code = $code;
    }

    /**
     * @param $body
     */
    public function setResponseBody($body = false)
    {
        $this->_response_body = $body;
    }

    /**
     * @return string|null
     */
    public function getResponseCode()
    {
        if (!$this->_response_code) {
            return null;
        }

        return $this->_response_code;
    }

    /**
     * @return string|null
     */
    public function getResponseMessage()
    {
        if (!$this->_response_code) {
            return null;
        }

        return $this->_responses[$this->_response_code];
    }

    /**
     * @return bool|null
     */
    public function getResponseBody()
    {
        if (!$this->_response_body) {
            return null;
        }

        return (bool)$this->_response_body;
    }
}
