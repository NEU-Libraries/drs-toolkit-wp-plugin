<?php 

abstract class Ceres_Abstract_Renderer {
  
  protected $options = array();
  
  protected $html = '';
  
  protected $dataToRender;
  
  protected $resourceId;
  
  private $fetcher;
  
  private $template;
  
  public function __construct(Ceres_Abstract_Fetcher $fetcher, $resourceId = '') {
    $this->fetcher = $fetcher;
    $this->resourceId = $this->setResourceId($resourceId);
  
  }
  
  public function setResourceId($resourceId) {
    $this->resourceId = $resoureId;
  }
  
  public function getResourceId() {
    return $this->resourceId;
  }
  
  public function setDataToRender($data) {
    $this->dataToRender = $data;
  }
  
  public function getDataToRender() {
    return $this->dataToRender;
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


