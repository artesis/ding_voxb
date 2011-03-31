<?php 

/**
 * @file
 *
 * Single tag class.
 */

class VoxbTagRecord extends VoxbBase {
  private $name;
  private $count;
  
  public function __construct($sXml = null) {
  	if ($sXml) {
	    $this->name = $sXml->tag;
	    $this->count = $sXml->tagCount;
	  }
   parent::getInstance();
  }
  
  /**
   * @return string
   */
  public function getName() {
    return $this->name;
  }
  
  /**
   * Returns amount of taggings to this tag.
   * @return integer
   */
  public function getCount() {
    return $this->count;
  }
  
 /**
   * Create a tag. 
   * 
   * @param string $faustNum
   * @param string $tag
   * @param integer $userId
   */
  public function create($faustNum, $tag, $userId) {
    $response = $this->call('createMyData', array(
      'userId' => $userId,
      'item' => array(
        'tags' => array(
          'tag' => $tag
        )
      ),
      'object' => array(
        'objectIdentifierValue' => $faustNum,
        'objectIdentifierType' => 'FAUST'
      )
    ));
    
    if (!$response || $response->error) {
      return false;
    }
    return true;
  }
  
  /**
   * convert object to array
   */
  public function toArray(){
  	return array(
      'name' => $this->name,
  	  'count' => $this->count
  	);
  }
}
