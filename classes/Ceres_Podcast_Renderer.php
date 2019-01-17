<?php

class Ceres_Podcast_Renderer extends Ceres_Abstract_Renderer {
  
  public function render() {
    $this->fetcher->parseItemsData();
    $itemsData = $this->fetcher->getItemsData();
    
    $html = "";
    foreach($itemsData as $itemData) {
      $html .= $this->renderPodcastArticle($itemData);
    }
    
    $html .= "";
    
    return $html;
  }
  
  public function renderPodcastArticle($itemData) {
    // @TODO: this ties the Renderer to the DRS_Fetcher explicitly to the DRS. I want to avoid that
    $jwplayerData = $this->fetcher->parseJwPlayerData($itemData);
    list($mediaUrl, $type, $imageUrl) = $jwplayerData;
    $podcastArticleHtml = 
    "<div class='row'>
         <article>
            <h3>" . $itemData['title_info_title_ssi'] ."</h3>
  									<p>" . implode('; ', $itemData['personal_creators_tesim']) . "</p>
  									<p>" . $itemData['date_ssi'] . "</p>
  									<p>" . $itemData['abstract_tesim'][0] ."</p>
                    <div>" . $this->renderJwplayer($mediaUrl, $itemData['id'], $type, $imageUrl) . "</div>


  										<a href='https://repository.library.northeastern.edu/files/" . $itemData['id'] . "/audio.mp3'>
  											<strong>Download Episode</strong>
  										</a>
					</article>
    </div>";
    
    return $podcastArticleHtml;
  }
  
  /* below here probably needs to be generalized into a higher (abstract?) class, or its own Renderer (e.g. jwPlayer) */
  
  public function getThumbail($size) {
    
  }
  
  public function renderJwplayer($mediaUrl, $resourceId, $type, $imageUrl, $itemData = array(), $options = array()) {
    $av_pid = $resourceId;
//$resourceId = 'neu:m043nx64v';
    switch ($type) {
      case 'mp3':
        $av_provider = 'sound';
        break;
        
      case 'mp4':
        $av_provider = 'video';
        break;
    }
    
    $numeric_pid = str_replace(":", "-", $av_pid);
    $id_img = 'drs-item-img-'.$numeric_pid;
    $id_video = 'drs-item-video-'.$numeric_pid;
    
    $html = "<img id='$id_img' src='$imageUrl' class='replace_thumbs'/>";
    $html .= "<div id='$id_video'></div>";
    
    // @TODO check if is_safari is still needed (possibly fixed in current version of jwplayer
    $scriptHtml = "
      <script type='text/javascript'>

      jwplayer.key = '" . JWPLAYER_KEY . "',

      jQuery(document).ready(function ($) {
          $('#$id_img').hide();
          jwplayer('$id_video').setup(
            {
              width: '50%',
              height: 30,
              rtmp: {
                  bufferlength: 5
              },
              

              image: '$imageUrl',
              provider: '$av_provider',
              androidhls: true,
              primary: 'html5',
              hlshtml: true,
              aspectratio: '16:9',

              sources:[ {
                  file: 'https://repository.library.northeastern.edu/wowza/$resourceId/plain', type: '$type'
              },
              {
                  file: 'https://repository.library.northeastern.edu/wowza/$resourceId/playlist.m3u8'
              }]


            }

          );

          jwplayer('$id_video').on('ready', function () {
              if (is_safari) {
                  // Set poster image for video element to avoid black background for audio-only programs.
                  $('$id_video video').attr('poster', '$imageUrl');
              }
          });
          function errorMessage() {
              $('#$id_img').before('<div>error<br /><strong>Error Message:</strong></div>');
              $('#$id_img').show();
              $('#$id_video').hide();
          }
          jwplayer('$id_video').on('error', function () {
              errorMessage();
          });
          jwplayer('$id_video').on('setupError', function () {
              errorMessage();
          });
          jwplayer('$id_video').on('buffer', function () {
              theTimeout = setTimeout(function (e) {
                  errorMessage(e);
              },
              5000);
          });
          jwplayer('$id_video').on('play', function () {
              clearTimeout(theTimeout);
          });
          $('.replace_thumbs').click(function () {
              jwplayer('$id_video').play()
          })
      });
      </script>
    ";
    $html .= $scriptHtml;
    
    return $html;
  }
}
