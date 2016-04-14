<?php
global $item_pid;
if (isset($item_pid)){
  header("Content-type: video/mp4");
  header("Cache-Control: no-store, no-cache");
  header("Content-disposition: attachment;filename=video.mp4");
  $av_pid = explode("/", $item_pid);
  $av_pid = end($av_pid);
  $encoded_av_pid = str_replace(':','%3A', $av_pid);
  $av_dir = substr(md5("info:fedora/".$av_pid."/content/content.0"), 0, 2);
  $full_pid = "info%3Afedora%2F".$encoded_av_pid."%2Fcontent%2Fcontent.0";
  $url = "http://libwowza.neu.edu/datastreamStore/cerberusData/newfedoradata/datastreamStore/".$av_dir."/".urlencode($full_pid);
  $handle = @fopen($url, "r");
  if ($handle) {
      while (($buffer = fgets($handle, 4096)) !== false) {
          echo $buffer;
      }
      fclose($handle);
  }
}
