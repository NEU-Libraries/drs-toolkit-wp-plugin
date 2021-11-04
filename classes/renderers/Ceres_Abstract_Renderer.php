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
  
  // @TODO this might get moved into a separate Pagination Renderer
  public function buildPagination() {
    // @TODO needs update to reflect possibility of multiple fetchers being injected
    $pageCount = $this->fetcher->getPageCount();
    
    // this likely has to parse it out from url, maybe something in the response header
    // which means it'll be API-dependent
    // unless it's hanging out in the fetcher, depending on how well I'm paying attention to state
    //$currentPageNumber = $this->getCurrentPageNumber();
    $currentPageNumber = 1;
    
    $firstPageUrl = $this->fetcher->getPageUrl(1);
    $lastPageUrl = $this->fetcher->getPageUrl($pageCount);
    $firstButton = "<a class='pagination-button' data-url='$firstPageUrl'>FIRST</a>";
    $lastButton = "<a class='pagination-button' data-url='$lastPageUrl'>LAST</a>";
    $html = "<div class='ceres-pagination'>$firstButton";
    for($pageNumber = 1; $pageNumber < $pageCount + 1; $pageNumber++) {
      $url = $this->fetcher->getPageUrl($pageNumber);
      $classes = 'pagination-button ';
      
      if ($pageNumber == $currentPageNumber) {
        $classes .= 'current';
      }
      $pageButton = "<a class='$classes' data-url='$url'>$pageNumber</a>";
      
      $html .= $pageButton;
    }
    
    $html .= $lastButton .= "</div>";
    return $html;
    
  }

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
