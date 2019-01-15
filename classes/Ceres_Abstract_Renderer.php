<?php 

abstract class Ceres_Abstract_Renderer {
  
  protected $options = array();
  
  protected $html = '';
  
  protected $fetcher;
  
  protected $resourceId;
  
  private $template;
  
  public function __construct($fetcher, $resourceId = null) {
    $this->fetcher = $fetcher;
    $this->setResourceId($resourceId);
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
  
  public function getOptions() {
    return $this->options;
  }
  
  public function setOption(string $option, string $value = '') {
    if ($value == '') {
      unset($this->options[$option]);
    } else {
      $this->pptions[$option] = $value;
    }
  }
  
  public function getOption(string $option) {
    return $this->options[$option];
  }
}


