<?php
/**
 * @file
 * BEServicesContext.php
 *
 * Adds a back-end services context for the services module rest_server
 */

namespace Drupal\rest_services_be_request;
use \ServicesContextInterface;

class BEServicesContext implements ServicesContextInterface {
  protected $globals = array();
  protected $data = array();

  function __construct($globals, $endpoint_path, $method = 'GET') {
    $this->globals = $globals;
    $this->data['endpoint_path'] = $endpoint_path;
    $this->data['request_method'] = $method;
    $this->buildFromGlobals();
  }

  public function buildFromGlobals() {
    if ( isset($this->globals['get']) ) {
      $this->data['get'] = $this->globals['get'];
    }
    if ( isset($this->globals['server']) ) {
      $this->data['server'] = $this->globals['server'];
    }
    if ( isset($this->globals['post']) ) {
      $this->data['post'] = $this->globals['post'];
    }
    if ( isset($this->globals['request_body']) ) {
      $this->data['request_body'] = $this->globals['request_body'];
    }
  }

  /**
   * Missing from the interface!
   */
  public function getRequestMethod() {
    return $this->data['request_method'];
  }

  /**
   * Retrieve endpoint path. It is saved in constructor.
   *
   * @return string
   */
  public function getEndpointPath() {
    return $this->data['endpoint_path'];
  }


  /**
   * Retrieve canonical path.
   *
   * @return string
   */
  public function getCanonicalPath() {
    if (!isset($this->data['canonical_path'])) {
      $endpoint_path = $this->getEndpointPath();
      $endpoint_path_len = drupal_strlen($endpoint_path . '/');
      $this->data['canonical_path'] = drupal_substr($this->data['get']['q'], $endpoint_path_len);
    }

    return $this->data['canonical_path'];
  }

  /**
   * Return value of global $_POST.
   *
   * @return string
   */
  public function getPostData() {
    return $this->data['post'];
  }

  /**
   * Return value of the request body.
   *
   * @return string
   */
  public function getRequestBody() {
    return $this->data['request_body'];
  }

  /**
   * Access to $_SERVER variables.
   *
   * @param string $variable_name
   *   Key of the server variable.
   *
   * @return string
   *   Value of the server variable.
   */
  public function getServerVariable($variable_name) {
    if (isset($this->data['server'][$variable_name])) {
      return $this->data['server'][$variable_name];
    }
    else {
      if ($variable_name == 'CONTENT_TYPE' && isset($this->data['server']['HTTP_CONTENT_TYPE'])) {
        return $this->data['server']['HTTP_CONTENT_TYPE'];
      }
    }
  }

  /**
   * Access to $_GET variables.
   *
   * @param string $variable_name
   *   Name of the variable or NULL if all content of $_GET to be returned.
   *
   * @return mixed
   *   Value of variable or array of all variables.
   */
  public function getGetVariable($variable_name = NULL) {
    if (empty($variable_name)) {
      return $this->data['get'];
    }
    if (isset($this->data['get'][$variable_name])) {
      return $this->data['get'][$variable_name];
    }
  }
}
