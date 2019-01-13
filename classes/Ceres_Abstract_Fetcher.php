<?php 

abstract class Ceres_Abstract_Fetcher {
  
  protected $endpoint;
  
  /**
   * refers to additional URL path options, generally for a RESTful API pattern
   * @var array
   */
  
  protected $queryOptions = array();

  /**
   * The ID of the remote resource (DRS pid, DPLA hash id, etc)
   * @var unknown
   */
  
  protected $resourceId;
  
  /**
   * GET params to tack on to the $endpoint + $queryOptions path
   * @var array
   */
  
  protected $queryParams = array();
  
  /**
   * The parsed response, including the handling of errors and output message (i.e., not the direct
   * curl response, though that's up for @TODO debate
   * @var array
   */
  
  protected $responseData = array();

  abstract public function buildQueryString();
  
  public function __construct(string $resourceId = '', array $queryOptions = array(), array $queryParams = array() ) {
    $this->setQueryParams($queryParams);
    $this->setQueryOptions($queryOptions);
    $this->resourceId = $resourceId;
  }
  
  public function fetchData() {
    $url = $this->buildQueryString();
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FAILONERROR, false);
    $rawResponse = curl_exec($ch);
    $responseStatus = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    
    //fallback for PHP < 5.5
    // @TODO remove this once our servers are upgraded, so we can keep using modern(ish) PHP practices
    if (! $responseStatus) {
      $responseStatusArray = curl_getinfo($ch);
      $responseStatus = $responseStatusArray['http_code'];
    }
    
    switch ($responseStatus) {
      case 200:
        $output = $rawResponse;
        $statusMessage = 'OK';
        break;
      case 404:
        $output = 'The resource was not found.';
        $statusMessage = 'Not Found';
        break;
      case 302:
        // check if there's json in it anyway
        $json = json_decode($raw_response);
        if (is_object($json)) {
          $output = $rawResponse;
        } else {
          $output = 'An unknown error occured -- ' . $response_status;
        }
        $statusMessageessage = 'The resource has moved or is no longer available';
        break;
      default:
        $output = 'An unknown error occured.' . $response_status;
        $statusMessage = 'An unkown error occured. Please try again';
        break;
        
    }
    $this->responseData = array(
        'status' => $responseStatus,
        'statusMessage' => $statusMessage,
        'output' => $output,
    );
    curl_close($ch);
  }
  
  public function getResponseData() {
    return $this->responseData;
  }
  
  public function hasError() {
    return $this->hasError;
  }
  
  public function setQueryParams(array $queryParams) {
    $this->queryParams = $queryParams;
  }
  
  public function getQueryParams() {
    return $this->queryParams;
  }
  
  public function setQueryParam(string $param, string $value = '') {
    if ($value == '') {
      unset($this->queryParams[$param]);
    } else {
      $this->queryParams[$param] = $value;
    }
  }
  
  public function getQueryParam($param) {
    return $this->queryParams[$param];
  }
  
  public function setQueryOptions(array $queryOptions) {
    $this->queryOptions = $queryOptions;
  }
  
  public function getQueryOptions() {
    return $this->queryOptions;
  }
  
  public function setQueryOption(string $option, string $value = '') {
    if ($value == '') {
      unset($this->queryOptions[$option]);
    } else {
      $this->queryOptions[$option] = $value;
    }
  }
  
  public function getQueryOption($option) {
    return $this->queryOptions[$option];
  }
  
  public function setResourceId(string $resourceId) {
    $this->resourceId = $resourceId;
  }
  
  public function getResourceId() {
    return $this->resourceId;
  }
}
