<?php 

abstract class Ceres_Abstract_Renderer {
  
  protected $options = array();
  
  protected $html = '';
  
  protected $responseData;
  
  protected $resourceId;
  
  private $fetcher;
  
  private $template;
  
  public function __construct(array $responseData, $resourceId = '') {
    $this->responseData = $responseData;
    $this->resourceId = $this->setResourceId($resourceId);
  }
  
  abstract function render();
  
  public function setResourceId($resourceId) {
    $this->resourceId = $resoureId;
  }
  
  public function getResourceId() {
    return $this->resourceId;
  }
  
  public function setResponse($response) {
    $this->response = $response;
  }
  
  public function getResponseData() {
    return $this->responseData;
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


