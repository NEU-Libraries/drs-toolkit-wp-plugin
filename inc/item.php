<?php
/**
 * get single record from DRS
 *
 * Also includes a helper function for getting a video document or returning
 * a 404 message
 */
function get_or_create_doc( $wp_query, $pid ){
    echo "WE MADE IT TO GET OR CREATE";
    global $piddoc;    // $solrdoc will be available to our theme templates

    $pid = 'neu:' . $pid;

    // try to retrieve an item based on pid
    // if we can't get one, then return a 404 and let's get out of here
    try {
        //create json object from api
        $piddoc = new NUSolrDoc( $pid );
        return $piddoc;
        //print_r($piddoc->_data);
        //print_r($piddoc->data);

    } catch (Exception $e) {
        //error
        $wp_query->is_404 = true;
        return;
    }
}

class NUSolrDoc {

    protected $pid = '';
    protected $_data = array();
    private $_query_url = 'http://libtomcat.neu.edu:8080/solr/fedora/select/?q={%Q%}&version=2.2&start=0&rows=1&wt=json';

    public function __construct($pid, $query_base = '') {

        $q = '';

        if ($query_base) {
            $q = $query_base .'%20AND%20';
        }

        $q .= 'pid:"' . $pid . '"';

        $this->pid = $pid;
        $url = str_replace('{%Q%}', $q, $this->_query_url);

        $this->load_json_data( $url);
        //print_r($this->load_json_data( $url));
        return $this;
    }

    /**
     * Returns a value from the solr response without any formatting.
     * Many values are in array format.
     */
    public function __get( $name ) {

        if (array_key_exists($name, $this->_data)) {
            return $this->_data[$name];
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

    /**
     * Loads the data into the object based on the numeric ID provided.
     *
     * Will throw an exception if no results are returned or if nothing
     * comes back from the server.
     *
     */
    private function load_json_data( $query_url ) {

        $data = $this->get_response( $query_url );

        // will be false if it is a bad response or no response
        if ( $data ) {

            $json = json_decode($data);

            // ensure we have one result
            if ($json->response->numFound == 1) {
                $this->_data = (array)$json->response->docs[0];

            } else {
                //throw new NoResultsFound('No results were found with the ID of: ' . $this->pid );
            }

        } else {
            //throw new NoResponseFromServer('There was a problem communicating with the solr database');
        }
        //just ploping this in for example
        $this->_data = "{\"title='TitleTitleTitle'\":[\"test_pic.jpeg\"],\"title='Date created'\":[\"May 15, 2015\"],\"title='Type of resource'\":[\"Still image\"],\"title='Format'\":[\"Image\"],\"title='Abstract/Description'\":[\"Lorem Ipsum Lorem Ipsum Lorem Ipsum\"],\"title='Subjects and keywords'\":[[\"a\"],[\"content\"]]}";
        echo $this->_data;
        $this->_data = json_decode($this->_data);
        //print_r($this->_data);
        global $title;
        $title = $this->_data->title='TitleTitleTitle';
        echo "title is " . $title;
        return $title;

    }

    /**
     * Basic curl response mechanism.
     */
    protected function get_response( $url ) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

        // if it returns a 403 it will return no $output
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

}
