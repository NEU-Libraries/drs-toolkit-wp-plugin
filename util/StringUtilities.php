<?php

function titleize($string){
    $string = str_replace("_tesim","",$string);
    $string = str_replace("_sim","",$string);
    $string = str_replace("_ssim","",$string);
    $string = str_replace("_ssi","",$string);
    $string = str_replace("full_","",$string);
    $string = str_replace("drs_","",$string);
    $string = str_replace("niec_","",$string);
    $string = str_replace("_"," ",$string);
    $string = ucfirst($string);
    return $string;
}

/**
 * Wraps home_url() to include the drstk_home_url after the home_url.
 *
 * If no $path is provided, will return the url with a trailing '/'
 * which is different from how the normal home_url() would function.
 */
function drstk_home_url($path = '', $scheme = null) {
    $drstk_url = get_option('drstk_home_url') ? get_option('drstk_home_url') : '/';
    $url = home_url($drstk_url, $scheme);
    if ($path) {
      $url .= ltrim( $path, '/' );
    } else {
      $url = rtrim($url, '/') . '/';
    }
  
    return $url;
}

  
/*helper method for getting repo type from pid valiues*/
function drstk_get_repo_from_pid($pid){
    $arr = explode(":", $pid);
    if ($arr[0] == "neu"){
      $repo = "drs";
    } else if ($arr[0] == "wp"){
      $repo = "wp";
    } else if ($arr[0] == "dpla"){
      $repo = "dpla";
    } else {
      $repo = NULL;
    }
    return $repo;
}
  