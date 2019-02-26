<?php 

abstract class Ceres_Abstract_Renderer {

  /**
   * Options to pass along for the particular child renderers
   * 
   * @var array
   */

  protected $options = array();

  /**
   * The running HTML to be returned by render()
   * @TODO: often unused, so a candidate for removal.
   * 
   * @var string
   */

  protected $html = '';
  
  /**
   * The ID of the remote resource (DRS pid, DPLA hash id, etc)
   * @var unknown
   */

  protected $resourceId;

  /**
   * The Ceres_Abstract_Fetcher that is handling the data retrieval. Its itemData property
   * holds the data to work with in most simple cases
   * 
   * Child classes' render() method will tell the Fetcher to, yaknow, fetch data
   * as needed for it to put together the page (or page component).
   * 
   * @var Ceres_Abstract_Fetcher
   */

  protected $fetcher;
  
  public function __construct($fetcher, $resourceId = null, $options = array()) {
    $this->fetcher = $fetcher;
    $this->setResourceId($resourceId);
    $this->setOptions($options);
  }

  abstract function render();

  public function setResourceId($resourceId) {
    $this->resourceId = $resourceId;
  }

  public function getResourceId() {
    return $this->resourceId;
  }

  public function setOptions(array $options) {
    $this->options = $options;
  }

  /**
   * Returns the entire options array
   * 
   * @return array
   */

  public function getOptions() {
    return $this->options;
  }

  /**
   * Set or unset an option value. Not passing a value unsets the option.
   * 
   * @param string $option
   * @param string $value
   */

  public function setOption($option, $value = '') {
    if ($value == '') {
      unset($this->options[$option]);
    } else {
      $this->options[$option] = $value;
    }
  }

  public function getOption($option, $default = false) {
    if (isset($this->options[$option])) {
      return $this->options[$option];
    }
    return $default;
  }
}
