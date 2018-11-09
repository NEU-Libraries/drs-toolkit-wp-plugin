<?php
// global $item_pid;
// if (isset($item_pid)){
//   header("Content-type: video/mp4");
//   header("Cache-Control: no-store, no-cache");
//   header("Content-disposition: attachment;filename=video.mp4");
//   $av_pid = explode("/", $item_pid);
//   $av_pid = end($av_pid);
//   $av_pid = str_replace("?datastream_id=content","",$av_pid);
//   $url = "https://repository.library.northeastern.edu/wowza/".$av_pid."/plain";
//   $handle = @fopen($url, "r");
//   if ($handle) {
//       while (($buffer = fgets($handle, 4096)) !== false) {
//           echo $buffer;
//       }
//       fclose($handle);
//   }
// }

global $item_pid;

error_log("DGC DEBUG - download.php template");
error_log("item_pid: ".$item_pid);

  $av_pid = explode("/", $item_pid);
  $av_pid = end($av_pid);
  $av_pid = str_replace("?datastream_id=content","",$av_pid);

error_log("av_pid: ".$av_pid);
