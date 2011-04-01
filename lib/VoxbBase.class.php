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
  public static $instance = null;
  
  /**
   * SOAP client attribute.
   *
   * @var object
   */
  public static $soapClient = null;
  
  /**
   * Constructor initialize $this->soapClient attribute.
   */
  private function __construct() {
    $options = array(
      'soap_version'=>SOAP_1_2,
      'exceptions'=>true,
      'trace'=>1,
      'cache_wsdl'=>WSDL_CACHE_NONE
    ); 

    try {
      VoxbBase::$soapClient = new SoapClient(variable_get('voxb_service_url', ''), $options);
    } catch(Exception $e) {
      VoxbBase::$soapClient = null;
    }
  }
  
  /**
   * Singleton feature.
   */
  public static function getInstance() {
    if (VoxbBase::$instance == null) {
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
    if (VoxbBase::$soapClient == null) {
      return false;
    }

    try {     
      $response = VoxbBase::$soapClient->$method($data);
    } catch(Exception $e) {
      return false;
    }
    return $response;
  }

  /**
   * Check if the service is available
   */
  public function isServiceAvailable() {
    return (VoxbBase::$soapClient == null ? false : true);
  }
}
