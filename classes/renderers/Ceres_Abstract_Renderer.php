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
     * @var array or string
     */

    protected $resourceId;

    /**
     * 
     * The class used for the containing element for CSS customization purposes
     * 
     * @var string
     */
    protected $containerClass;



    /**
     * The Ceres_Abstract_Fetcher(s) that are handling the data retrieval. Its itemData property
     * holds the data to work with in most simple cases
     * 
     * Child classes' render() method will tell the Fetchers to, yaknow, fetch data
     * as needed for it to put together the page (or page component).
     * 
     * @var array Ceres_Abstract_Fetcher
     */

    protected $fetchers = array();
    
    /**
     * The Extractor(s) that will be used to wrangle raw responses from the Fetchers
     * 
     * extract() method on extractors will return just the needed data
     * 
     * @var array Ceres_Abstract_Extractor
     * 
     */

    protected $extractors = array();

    public function __construct(array $fetchers, array $extractors, $resourceId = null, $options = array()) {
      foreach ($fetchers as $name => $class) {
        $this->injectFetcher($name, $class);
      }

      foreach ($extractors as $name => $class) {
        $this->injectExtractor($name, $class);
      }


      $this->setResourceId($resourceId);
      $this->setOptions($options);
    }

    /* enqueing will have to figure out how to stuff styles and scripts in early in WP rendering.
    Might have to go elsewhere */

    function enqueStyles() {

    }

    function enqueScripts() {
      
    }
    
    abstract function render();
    
    // @TODO this might get moved into a separate Pagination Renderer, likely different for each Fetcher
    //   First thought is that this'd just instantiate a new Renderer and tell it to do its thing
    //   though that'd also mean injecting the relevant Fetcher into _that_ which might be 
    //   getting crazy
    
    // abstract function buildPagination();

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
