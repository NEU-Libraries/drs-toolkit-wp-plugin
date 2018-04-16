<?php
global $item_pid;
if (isset($item_pid)){
  header("Content-type: video/mp4");
  header("Cache-Control: no-store, no-cache");
  header("Content-disposition: attachment;filename=video.mp4");
  $av_pid = explode("/", $item_pid);
  $av_pid = end($av_pid);
  $url = "https://repository.library.northeastern.edu/wowza/".$av_pid."/plain";
  $handle = @fopen($url, "r");
  if ($handle) {
      while (($buffer = fgets($handle, 4096)) !== false) {
          echo $buffer;
      }
      fclose($handle);
  }
}
