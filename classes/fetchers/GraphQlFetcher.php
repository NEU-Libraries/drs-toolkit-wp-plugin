<?php 

namespace Ceres\Fetcher;
// TODO: are the requires already added elsewhere?
require_once ("AbstractFetcher.php");

class GraphQlFetcher extends AbstractFetcher {

  protected $endpoint;

  /**
   * refers to additional URL path options, generally for a RESTful API pattern
   * @var array
   */

  protected $queryOptions = array();

  /**
   * The ID of the remote resource (DRS pid, DPLA hash id, etc)
   * @var string
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
  
  /**
   * The items data, parsed out from the response
   * @TODO: figure out if/how to normalize this across APIs to decouple Fetchers from Renderers
   * 
   * @var array
   */

  protected $itemsData = array();

  /**
   * The number of pages from a large API request. Will depend on the requested items per page,
   * so that's better not changing between requests.
   * 
   * @var integer
   */
  
  protected $pageCount;
  
  /** 
   * If the API provides the option, the set number of items to return per page. Best not to change this 
   * between requests. Should be set from setQueryParams() or setQueryParam().
   * 
   * @var integer
   */
  
  protected $perPage;
  
  /**
   * For rolling through multiple requests to the API to gather data, the current page number.
   * Should be set by the fetch*Page() functions.
   * 
   * @var integer
   */
  
  protected $currentPage;
  
  private $pageParamName;
  
  public function buildQueryString($queryOptions = false, $queryParams = false)
  {
    
  }

  public function parseItemsData()
  {
    
  }
  
  public function fetchPage(int $pageNumber)
  {
    
  }
  
  public function getPageUrl(int $pageNumber)
  {
    
  }
  
  /**
   * Takes API-specific response to set currentPage, pageCount, and perPage
   * Usually this appears in the response data somewhere, but sometimes
   * needs to use get_headers() when the data is there
   * 
   * @param Array $responseData
   */
  public function setPaginationData()
  {
    
  }

  public function getItemDataById($itemId)
  {
    
  }

  public function __construct(array $queryOptions = array(), array $queryParams = array(), $resourceId = null ) {
    $this->setQueryParams($queryParams);
    $this->setQueryOptions($queryOptions);
    $this->setResourceId($resourceId);
  }
  
  /**
   * The params are to to bypass the usual class-based props, e.g. when needing to 
   * query just a snippet that diverges from the 'starting point' of the fetcher,
   * like DRS grabbing content_object data when looping through a search response
   * 
   * @param $url
   * @param boolean $returnWithoutSetting Just send back the data, but don't keep it in the prop
   */

  public function fetchData($url = null, $returnWithoutSetting = false) {
    if (is_null($url)) {
      $url = $this->buildQueryString();
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FAILONERROR, false);
    $rawResponse = curl_exec($ch);
    // @TODO:  when we're up to PHP > 5.5, CURLINFO_HTTP_CODE should be CURLINFO_RESPONSE_CODE
    //$responseStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $responseStatus = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    //fallback for PHP < 5.5
    // @TODO remove this once our servers are upgraded, so we can keep using modern(ish) PHP practices
    if (! $responseStatus) {
      $responseStatusArray = curl_getinfo($ch);
      $responseStatus = $responseStatusArray['http_code'];
    }
    
    
    // shenanigans from https://stackoverflow.com/questions/10384778/curl-request-with-headers-separate-body-a-from-a-header
    // for splitting out just the body from the response
    $header_len = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $responseBody = substr($rawResponse, $header_len);
    // end shenanigans 
    
    switch ($responseStatus) {
      case 200:
        $output = $responseBody;
        $statusMessage = 'OK';
        break;
      case 404:
        $output = 'The resource was not found.';
        $statusMessage = 'Not Found';
        break;
      case 302:
        // check if there's json in it anyway
        $json = json_decode($responseBody, true);
        if (is_array($json)) {
          $output = $responseBody;
        } else {
          $output = 'An unknown error occured -- ' . $responseStatus;
        }
        $statusMessage = 'The resource has moved or is no longer available';
        break;
      default:
        $output = 'An unknown error occured.' . $responseStatus;
        $statusMessage = 'An unkown error occured. Please try again';
        break;
    }
    $responseData = array(
        'status' => $responseStatus,
        'statusMessage' => $statusMessage,
        'output' => json_decode($output, true),
    );
    
    if($returnWithoutSetting) {
      return $responseData;
    }
    
    $this->responseData = $responseData;
    curl_close($ch);
    $this->setPaginationData();
  }
  
  public function hasNextPage() {
    $nextPage = $this->currentPage + 1;
    if ($nextPage > $this->pageCount) {
      return false;
    }
    return true;
  }
  
  public function fetchNextPage() {
    if ($this->hasNextPage()) {
      $nextPage = $this->currentPage + 1;
      $this->fetchPage($nextPage);
    }
  }
  
  public function fetchFirstPage() {
    $this->fetchPage(1);
  }
  
  public function fetchLastPage() {
    $lastPage = $this->pageCount - 1;
    $this->fetchPage($lastPage);
  }

  public function getResponseData() {
    return $this->responseData;
  }

  public function setQueryParams(array $queryParams) {
    $this->queryParams = $queryParams;
  }

  public function getQueryParams() {
    return $this->queryParams;
  }

  public function setQueryParam($param, $value = '' ) {
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

  public function setQueryOption($option, $value = '') {
    if ($value == '') {
      unset($this->queryOptions[$option]);
    } else {
      $this->queryOptions[$option] = $value;
    }
  }

  public function getQueryOption($option) {
    return $this->queryOptions[$option];
  }

  public function setResourceId($resourceId) {
    $this->resourceId = $resourceId;
  }

  public function getResourceId() {
    return $this->resourceId;
  }

  public function getItemsData() {
    return $this->itemsData;
  }
  
  public function getPageCount() {
    return $this->pageCount;
  }
}
