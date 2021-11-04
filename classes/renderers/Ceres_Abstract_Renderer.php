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
   * The Ceres_Abstract_Fetcher that are handling the data retrieval. Its itemData property
   * holds the data to work with in most simple cases
   * 
   * Child classes' render() method will tell the Fetchers to, yaknow, fetch data
   * as needed for it to put together the page (or page component).
   * 
   * @var array Ceres_Abstract_Fetcher
   */

  protected $fetchers;
  
  public function __construct(array $fetchers, $resourceId = null, $options = array()) {
    foreach ($fetchers as $name => $class) {
      $this->injectFetcher($name, $class);
    }
    $this->setResourceId($resourceId);
    $this->setOptions($options);
  }

  abstract function render();
  
  // @TODO this might get moved into a separate Pagination Renderer, likely different for each Fetcher
  //   First thought is that this'd just instantiate a new Renderer and tell it to do its thing
  //   though that'd also mean injecting the relevant Fetcher into _that_ which might be 
  //   getting crazy
  abstract function buildPagination();

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
  
  
  private function injectFetcher($name, $class) {
    $this->fetchers[$name] = $class;
  }
  
  
}
