<?php

class Ceres_Podcast_Renderer extends Ceres_Abstract_Renderer {

  
  public function render() {
    $html = "";
    foreach($this->responseData['output']['response']['response']['docs'] as $podcastJson) {
      $html .= $this->renderPodcastArticle($podcastJson);
      
    }
    
    $html .= "";
  }
  
  public function renderPodcastArticle($podcastJson) {
    $podcastArticleHtml = "<article>";
    $podcastArticleHtml .= "<h3>A Podcast Title</h3>";
    
    $podcastArticleHtml .= "</article>";
    return $podcastArticleHtml;
  }
  
  public function getThumbail($size) {
    
  }
}

