<?php

namespace App\Helpers;

use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Cookie;

class Response {
  // Status
  const STATUS_SUCCESS = true;
  const STATUS_FAILURE = false;

  const HTTP_OK                    = HttpResponse::HTTP_OK;
  const HTTP_BAD_REQUEST           = HttpResponse::HTTP_BAD_REQUEST;
  const HTTP_UNAUTHORIZED          = HttpResponse::HTTP_UNAUTHORIZED;
  const HTTP_LOCKED                = HttpResponse::HTTP_LOCKED;
  const HTTP_NOT_ACCEPTABLE        = HttpResponse::HTTP_NOT_ACCEPTABLE;
  const HTTP_INTERNAL_SERVER_ERROR = HttpResponse::HTTP_INTERNAL_SERVER_ERROR;
  const HTTP_DATA_REQUIRED         = HttpResponse::HTTP_EXPECTATION_FAILED;


  private $_response;

  // Properties
  private $_code;
  private $_status;

  // API response related properties
  private $_message;
  private $_data;

  private $_headers = [];

  public function __construct () {
    $this->_code = HttpResponse::HTTP_OK;
    $this->_status = self::STATUS_SUCCESS;

    $this->_message = [];
    $this->_data = [];
    $this->_response = response();
  }

  // Magic function to add every property to data
  public function __set ($name, $value) {
    $this->_data[$name] = isset($value) ? $value : null;
  }

  // Magic function to retrieve property from data
  public function __get ($name) {
    $output = null;

    if (isset($this->_data[$name])) {
      $output = $this->_data[$name];
    }

    return $output;
  }

  /**
   * This function is used to set all the ApiData at once or set a particular key value pair in
   * $_data
   *
   * @param      $key
   * @param null $value
   *
   * @return self
   */
  public function setApiData ($key, $value = null) {
    $num_args = func_num_args();
    if ($num_args == 1) {
      $this->_data = $key;
    } else {
      $this->_data[$key] = $value;
    }

    return $this;
  }

  /**
   * Setting response status code
   *
   * @param int  $code - Integer defining the status code,
   * Use constants defined in HttpResponse
   * @param bool $status - To Convey Success or failure
   *
   * @return $this
   */
  public function setStatusCode (int $code, bool $status) {
    $this->_code = $code;
    $this->_status = $status;
    return $this;
  }

  /**
   * This function is used to set message in api response
   *
   * @param $message
   *
   * @return self
   */
  public function setMessage ($message) {
    $this->_message = $message;
    return $this;
  }

  public function setCookie ($key, $value, $time, $path = '/', $domain = null, $secure = false, $httpOnly = false) {
    if (!isset($domain)) {
      $domain = config('session.domain');
    }
    $cookie = new Cookie($key, $value, $time, $path, $domain, $secure, $httpOnly);
    $this->_response->withCookie($cookie);

    return $this;
  }

  public function setHeader ($name, $value) {
    $this->_headers[$name] = $value;

    return $this;
  }

  public function setHeaders ($headers = []) {
    $this->_headers = array_merge($this->_headers, $headers);
    return $this;
  }

  public function sendApiResponse () {
    $payload = [
      'message' => empty($this->_message) ? (object)[] : $this->_message,
      'data' => empty($this->_data) ? (object)[] : $this->_data,
    ];

    $api_response = [
      'code' => $this->_code,
      'status' => $this->_status,
      'payload' => $payload,
    ];

    return $this->_response->json($api_response, $this->_code);
  }
}