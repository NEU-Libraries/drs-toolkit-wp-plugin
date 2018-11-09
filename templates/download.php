<?php
  $url = "https://repository.library.northeastern.edu/downloads/". $item_pid ."?datastream_id=content&token=" . drstk_drs_auth();
  echo file_get_contents($url);
