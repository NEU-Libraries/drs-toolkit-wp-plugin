<?php

class Ceres_Podcast_Renderer extends Ceres_Abstract_Renderer {

  public function render() {
    $this->fetcher->fetchData();
    $html = "";
    do {
      $this->fetcher->parseItemsData();
      $itemsData = $this->fetcher->getItemsData();
      foreach($itemsData as $itemData) {
        $html .= $this->renderPodcastArticle($itemData);
      }
      // I (PMJ) not entirely happy with this pagination technique,
      // but we'll see if something better reveals itself
      if ($hasNextPage = $this->fetcher->hasNextPage()) {
        $this->fetcher->fetchNextPage();
      }
    } while ($hasNextPage);
    return $html;
  }

  public function renderPodcastArticle($itemData) {
    $itemFilesData = $this->fetcher->fetchFilesData($itemData['id']);

    //a bit roundabout way to dig up the transcript, assumed to be in 'associated'
    if (isset($itemFilesData['output']['associated'])) {
      $associatedItemArray = $itemFilesData['output']['associated'];
      reset($associatedItemArray);
      $associatedItemId = key($associatedItemArray);
      $transcriptId = $this->fetcher->parseContentObjectId($associatedItemId);
      $transcriptDownloadHtml = "
        <a class='drstk-podcast-download' href='https://repository.library.northeastern.edu/downloads/$transcriptId'>
          <strong>Download Transcript</strong>
        </a>
      ";
    } else {
      $transcriptDownloadHtml = "";
    }
    // @TODO: This ties the Renderer to the DRS_Fetcher explicitly to the DRS. I want to avoid that.
    // that's a more general problem of not having a normalized metadata structure
    // the class='row' business also ties it to the theme or SiteBuilder plugin crap
    $podcastArticleHtml = 
    "<div class='row'>
         <article>
            <h3>" . $itemData['title_info_title_ssi'] . "</h3>
  									<p>" . implode('; ', $itemData['personal_creators_tesim']) . "</p>
  									<p>" . $itemData['date_ssi'] . "</p>
  									<p>" . $itemData['abstract_tesim'][0] . "</p>
                    <div>" . $this->renderJwplayer($itemData['id']) . "</div>
										<a class='drstk-podcast-download' href='https://repository.library.northeastern.edu/files/" . $itemData['id'] . "/audio.mp3'>
											<strong>Download Episode</strong>
										</a>
                    $transcriptDownloadHtml
					</article>
    </div>";

    return $podcastArticleHtml;
  }

  public function renderJwplayer($resourceId, $options = array()) {
    $jwplayerRenderer = new Ceres_Jwplayer_Renderer($this->fetcher, $resourceId);
    return $jwplayerRenderer->render();    
  }
}
