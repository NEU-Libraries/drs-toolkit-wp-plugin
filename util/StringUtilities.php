<?php

//namespace Drstk\Util;


/*API URL Builder helper method*/

function drstk_api_url($source, $pid, $action, $sub_action = NULL, $url_arguments = NULL) {
    $url = "";
    $dak = constant("DPLA_API_KEY");
    $dau = constant("DRS_API_USER");
    $dap = constant("DRS_API_PASSWORD");

    if ($source == "drs") {
        $url .= "https://repository.library.northeastern.edu/api/v1";
    } else if ($source == "dpla") {
        $url .= "https://api.dp.la/v2";
    }
    //when searching dpla on admin side, there's no pid, and the API barfs with a 404 if there's /? instead of just ?
    if ($source == 'dpla') {
        if (empty($pid)) {
            $url .= '/' . $action;
        } else {
            // grabbing a dpla item by ?q=pid no longer works, so build the url direct to the item's data
            $url .= '/' . $action . '/';
        }
    } else {
        //assuming the only else is DRS
        $url .= "/" . $action . "/";
    }

    //DRS subaction of content_objects has special needs for building the URL
    //PMJ assuming this only gets invoked when the action is 'files'
    switch ($sub_action) {
        case 'content_objects':
            $url .= "$pid/$sub_action";
            break;

        case null:
            //do nothing since there's no subaction
            $url .= $pid . "?";
            break;

        default:
            //most common url construction
            $url .= $sub_action . "/";
            $url .= $pid . "?";
            break;
    }

    // @TODO it might be nice to guarantee somehow that before we get here we know the DPLA key is in place
    // since if it isn't this won't return anything anyway
    if ($source == "dpla" && !empty($dak)) {
        $url .= "api_key=" . DPLA_API_KEY . "&";
    }

    if ($source == "drs" && !(empty($dau) || empty($dap))) {
        $token = drstk_drs_auth();
        if ($token != false && is_string($token))
            $url .= "token=" . $token . "&";
    }

    //direct DPLA item pid barfs on extraneous params
    switch ($source) {
        case 'dpla':
            if (empty($pid) && $url_arguments != null) {
                $url .= $url_arguments;
            }
            break;

        case 'drs':
            if ($url_arguments != NULL) {
                $url .= $url_arguments;
            }
            break;
    }
    return $url;
}

/*helper functions for getting default values and cleaning up stored options*/
function drstk_get_pid() {
    $collection_pid = get_option('drstk_collection');
    $collection_pid = explode("/", $collection_pid);
    $collection_pid = end($collection_pid);
    return $collection_pid;
}

function titleize($string) {
    $string = str_replace("_tesim", "", $string);
    $string = str_replace("_sim", "", $string);
    $string = str_replace("_ssim", "", $string);
    $string = str_replace("_ssi", "", $string);
    $string = str_replace("full_", "", $string);
    $string = str_replace("drs_", "", $string);
    $string = str_replace("niec_", "", $string);
    $string = str_replace("_", " ", $string);
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
        $url .= ltrim($path, '/');
    } else {
        $url = rtrim($url, '/') . '/';
    }

    return $url;
}


/*helper method for getting repo type from pid valiues*/
function drstk_get_repo_from_pid($pid) {
    $arr = explode(":", $pid);
    if ($arr[0] == "neu") {
        $repo = "drs";
    } else if ($arr[0] == "wp") {
        $repo = "wp";
    } else if ($arr[0] == "dpla") {
        $repo = "dpla";
    } else {
        $repo = NULL;
    }
    return $repo;
}
