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
  }
  
  public function renderPodcastArticle($itemData) {
    
    $podcastArticleHtml = 
    "<div class='row'>
         <article>
            <h3>" . $itemData['title_info_title_ssi'] ."</h3>
  									<p>" . implode('; ', $itemData['personal_creators_tesim']) . "</p>
  									<p>" . $itemData['date_ssi'] . "</p>
  									<p>" . $itemData['abstract_tesim'][0] ."</p>
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
  
  public function renderJwplayer($av_pid, $canonical_object_type, $itemData, $drs_item_img) {
  //eventually the Fetcher injected into the render should know how to parse out
  // the data needed by jwPlayer, so the Renderer can get it and pass it along
  // without the Renderer knowing what the data source is
  // that's to keep the principle of Renderers not caring where the data comes from (MV-ish)
  // there would then by Ceres_Jwplayer_Renderer that could be instantiated by other Renderers
  // (damn, this is getting ZF3-ish!)
    
    /*
    $val = current($data->canonical_object);
    $key = key($data->canonical_object);
    if (isset($data->thumbnails)){
      $img = $data->thumbnails[count($data->thumbnails)-2];
    }
    print(insert_jwplayer($key, $val, $data, $img));
    */
    
    // @TODO obv a better way to figure out which image to use!!
    $img = $itemData['thumbnails'][count($itemData['thumbnails']) -2];
    
    // @TODO is there a better way to dig up this id for a file?
    $av_pid = key($itemData['canonical_object']);
    
    $av_type = '';
    switch ($itemData['canonical_object']) {
      case 'Video File':
        $av_provider = 'video';
        $av_type = "MP4";
      break;
        
      case 'Audo File':
        $av_provider = 'sound';
        $av_type = "MP3";
      break;
        
    }
    
    $av_type = "";
    if ($canonical_object_type == 'Video File'){
      $av_provider = 'video';
      $av_type = "MP4";
    }
    if ($canonical_object_type == 'Audio File'){
      $av_provider = 'sound';
      $av_type = "MP3";
    }
  
    $av_pid = explode("/", $av_pid);
    $av_pid = end($av_pid);
    $av_pid = str_replace("?datastream_id=content","",$av_pid);
    if (isset($itemData['thumbnails'])){
      $av_poster = $itemData['thumbnails'][3];
    }
    $numeric_pid = str_replace(":", "-", $av_pid);
    $id_img = 'drs-item-img-'.$numeric_pid;
    $id_video = 'drs-item-video-'.$numeric_pid;
    
    if (!isset($av_poster)){
      $av_poster = $drs_item_img;
    }
    $html = '<img id="'.$id_img.'" src="'.$drs_item_img.'" class="replace_thumbs"/>';
    $html .= '<div id="'.$id_video.'"></div>';
    
    $scriptHtml = "
      <script type='text/javascript'>
      
      const jwPlayerKey = {JWPLAYER_KEY};
      let avProvider = $av_provider;
      let avPoster = $av_poster;
      let avType = strtolower($av_type);
      let avPid = $av_pid;
      let idImg = $id_img;
      let idVideo = $id_video;
      
      let jwPlayerSetup = {
              width: '100%',
              height: 400,
              rtmp: {
                  bufferlength: 5
              },
              key: jwPlayerKey,
              image: avPoster,
              provider: avProvider,
              androidhls: true,
              primary: 'html5',
              hlshtml: true,
              aspectratio: '16:9',
              sources:[ {
                  file: 'https://repository.library.northeastern.edu/wowza/' + avPid + '/plain', type: avType
              },
              {
                  file: 'https://repository.library.northeastern.edu/wowza/' + avPid + '/playlist.m3u8'
              }]
          };
      
      
      jQuery(document).ready(function ($) {
          $('#' + idImg).hide();
          jwplayer(idVideo).setup(jwPlayerSetup);
          
          jwplayer(idVideo).on('ready', function () {
              if (is_safari) {
                  // Set poster image for video element to avoid black background for audio-only programs.
                  $(idVideo + ' video').attr('poster', avPoster);
              }
          });
          function errorMessage() {
              $('#' + idImg).before('<div class='alert alert-warning'>error<br /><strong>Error Message:</strong></div>);
              $('#' + idImg).show();
              $('#' + idVideo).hide();
          }
          jwplayer(idVideo).on('error', function () {
              errorMessage();
          });
          jwplayer(idVideo).on('setupError', function () {
              errorMessage();
          });
          jwplayer(idVideo).on('buffer', function () {
              theTimeout = setTimeout(function (e) {
                  errorMessage(e);
              },
              5000);
          });
          jwplayer(idVideo).on('play', function () {
              clearTimeout(theTimeout);
          });
          $('.replace_thumbs').click(function () {
              jwplayer('idVideo').play()
          })
      });
      </script>
    ";
    $html .= $scriptHtml;
    
    return $html;
    /*    
    $html .= '
<script type="text/javascript">
  jwplayer.key="' . JWPLAYER_KEY . '";
  var primary = "html5";
  var provider = "'.$av_provider.'";
  jQuery(document).ready(function($){
  $("#'.$id_img.'").hide();
  jwplayer("'.$id_video.'").setup({';
    $html .= 'width: "100%",
        height: 400,
        rtmp: {bufferlength: 5},';
    if ($av_poster != null){$html .= 'image: "'.$av_poster.'",';}
    $html .= 'provider: "'.$av_provider.'",
    androidhls: "true",
    primary: primary,
    hlshtml: "true",
    aspectratio: "16:9",
    sources:
    [{ file: "https://repository.library.northeastern.edu/wowza/'.$av_pid.'/plain", type:"'.strtolower($av_type).'"},
       file: "https://repository.library.northeastern.edu/wowza/'.$av_pid.'/playlist.m3u8"}
    ],
  });

  jwplayer("'.$id_video.'").on("ready", function() {
   if (is_safari){
    // Set poster image for video element to avoid black background for audio-only programs.
    $("'.$id_video.' video").attr("poster", "'.$av_poster.'");
   }
  });
  function errorMessage() {
    $("#'.$id_img.'").before("<div class=\'alert alert-warning\'>'.$errors['item']['jwplayer_fail'].'<br /><strong>Error Message:</strong> "+e.message+"</div>");
    $("#'.$id_img.'").show();
    $("#'.$id_video.'").hide();
  }
  jwplayer("'.$id_video.'").on(\'error\', function(){
    errorMessage();
  });
  jwplayer("'.$id_video.'").on(\'setupError\', function(){
    errorMessage();
  });
  jwplayer("'.$id_video.'").on(\'buffer\', function() {
    theTimeout = setTimeout(function(e) {
      errorMessage(e);
    }, 5000);
  });
  jwplayer("'.$id_video.'").on("play", function(){
     clearTimeout(theTimeout);
   });
   $(".replace_thumbs").click(function() {
     jwplayer("'.$id_video.'").play()
   })
  });
</script>';
  */
    
    
  }
  
}

