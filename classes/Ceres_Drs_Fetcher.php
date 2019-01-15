<?php

class Ceres_Drs_Fetcher extends Ceres_Abstract_Fetcher {
  
  protected $endpoint = "https://repository.library.northeastern.edu/api/v1";
  
  public function __construct(array $queryOptions = array(), array $queryParams = array(), $resourceId = null) {
    
    parent::__construct($queryOptions, $queryParams, $resourceId);
    
    if (is_null($resourceId)) {
      $this->resourceId = $this->getPidFromSettings();
    } else {
      $this->resourceId = $resourceId;
    }
  }
  
  public function buildQueryString() {
    $url = $this->endpoint;
    $url .= '/' . $this->queryOptions['action'];
    
    if (isset($this->queryOptions['sub_action'])) {
      
      //DRS subaction of content_objects has special needs for building the URL
      //PMJ assuming this only gets invoked when the action is 'files'
      switch ($this->queryOptions['sub_action']) {
        case 'content_objects':
          $url .= "{$this->resourceId}/$sub_action";
          break;
          
        case null:
          //do nothing since there's no subaction
          $url .= "{$this->resourceId}";
          break;
          
        default:
          //most common url construction
          $url .= "{$this->queryOptions['sub_action']}/{$this->resourceId}";
          break;
      }
    } else {
      $url .= '/' . $this->resourceId;
    }
    
    if (! empty($this->queryParams)) {
      $url .= '?';
      foreach ($this->queryParams as $param=>$value) {
        if ($param == 'q') {
          $value = sanitize_text_field($value);
        }
        $url .= "$param=$value&";
      }
    }
    
    $dau = constant("DRS_API_USER");
    $dap = constant("DRS_API_PASSWORD");
    
    if(!(empty($dau) || empty($dap))) {

      $token = $this->drsAuth();
      if ($token != false && is_string($token))
        $url .= "token=$token";
    }
    return $url;
  }
  
  /**
   * The DRS part of the nee DRS Toolkit sets basically a default collection or set to draw from in the plugin's
   * settings, so this maintains using that setting if a $resourceId is omitted
   * 
   * @return string
   */
  
  public function getPidFromSettings() {
    $collectionSetting = get_option('drstk_collection');
    $explodedCollectionSetting = explode("/", $collectionSetting);
    return end($explodedCollectionSetting);
  }
  
  public function parseItemsData() {
    $this->itemsData = $this->responseData['output']['response']['response']['docs'];
  }
  
  public function fetchNextPage() {
    $paginationData = $this->responseData['output']['pagination']['table'];
    $currentPage = $paginationData['current_page'];
    $lastPage = $paginationData['num_pages'];
    $start = $paginationData['start'];
    $perPage = $paginationData['per_page'];
    if ($currentPage <= $lastPage) {
      $nextStartPage = $currentPage++;
    } else {
      return false;
    }
    
    $this->setParam('page', $nextStartPage);
    $this->buildQueryString();
    $this->fetchData();
  }
  
  /*DRS API Authenticate helper method*/
  // @TODO consider if this can be rolled into the rest of the fetching system?
  // comes from a site-specific customization in DRSTK
  public function drsAuth() {
    
    if(drstk_api_auth_enabled() == true){
      // Token is only good for one hour
      
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "https://repository.library.northeastern.edu/api/v1/auth_user");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, "email=" . DRS_API_USER . "&password=" . DRS_API_PASSWORD);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $headers = array();
      $headers[] = "Content-Type: application/x-www-form-urlencoded";
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      $result = curl_exec($ch);
      
      // result should be json
      $data = json_decode($result, true);
      
      $token = $data["auth_token"];
      
      if (!empty($token)) {
        return $token;
      } else {
        return false;
      }
    }
    else {
      // No user and/or password set
      return false;
    }
  }
}
