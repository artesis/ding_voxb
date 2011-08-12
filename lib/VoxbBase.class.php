<?php
/**
 * @file
 *
 * Base VoxB-client class.
 * Singleton class, supports connection to VoxB server.
 */

class VoxbBase {

  /**
   * Singleton template attribure.
   *
   * @var object
   */
  public static $instance = NULL;

  /**
   * SOAP client attribute.
   *
   * @var object
   */
  public static $soapClient = NULL;

  /**
   * Constructor initialize $this->soapClient attribute.
   */
  private function __construct() {
    $options = array(
      'soap_version' => SOAP_1_2,
      'exceptions' => TRUE,
      'trace' => 1,
      'cache_wsdl' => WSDL_CACHE_NONE,
      'namespaces' => array('voxb' => 'http://oss.dbc.dk/ns/voxb'),
    );

    try {
      VoxbBase::$soapClient = new NanoSOAPClient(variable_get('voxb_service_url', ''), $options);
    } catch (Exception $e) {
      VoxbBase::$soapClient = NULL;
    }
  }

  /**
   * Singleton feature.
   */
  public static function getInstance() {
    if (VoxbBase::$instance == NULL) {
      VoxbBase::$instance = new VoxbBase();
    }
    return VoxbBase::$instance;
  }

  /**
   * Use this method to call VoxB server methods.
   *
   * @param string $method
   * @param array $data
   */
  public function call($method, $data) {
    if (VoxbBase::$soapClient == NULL) {
      return FALSE;
    }
    try {
      $data = $this->replaceKeys($data, 'voxb');
      timer_start('voxb');
      $response = VoxbBase::$soapClient->call('voxb:' . $method . 'Request', $data);
      timer_stop('voxb');

      $replace_what = array('SOAP-ENV:', 'voxb:');
      $replace_to = array('', '');
      $response = str_replace($replace_what, $replace_to, $response);
      $response = simplexml_load_string($response);
    } catch (Exception $e) {
      return FALSE;
    }

    return $response;
  }

  /**
   * Check if the service is available
   */
  public function isServiceAvailable() {
    return (VoxbBase::$soapClient == NULL ? FALSE : TRUE);
  }

  /**
   * Set the request array keys according to namespace
   *
   * @param $ar
   *   Array which keys should be modified
   * @param $namespace
   *   Namespace value to be used with
   * @return type
   *   Array with modified keys
   */
  private function replaceKeys($ar, $namespace) {
    $return = array();

    foreach ($ar as $key => $value) {

      if (!is_numeric($key)) {
        $key = $namespace . ':' . $key;
      }

      if (is_array($value)) {
        $value = $this->replaceKeys($value, $namespace);
      }

      $return[$key] = $value;
    }

    return $return;
  }
}
