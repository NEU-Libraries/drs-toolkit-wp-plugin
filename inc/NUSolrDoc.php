<?php

/**
 * NUSolrDoc is the parent class of a Northeastern Solr document.
 *
 * It is constructed by passing the PID of a document you wish to view
 * For example: pid:191274
 * like localhost/wordpress/index.php?solr_doc_id=191274
 *
 * You can choose to limit results to a specific colid as well by specifying
 * a $query_base in the form of 'colid:"neu:191160"' (or any other limiting
 * query you prefer).
 *
 */
class NUSolrDoc {

    protected $pid = '';
    protected $_data = array();

    public function __construct($pid, $query_base = '') {

        $q = '';

        if ($query_base) {
            $q = $query_base .'%20AND%20';
        }

        $q .= 'pid:"' . $pid . '"';

        $this->pid = $pid;
        $url = str_replace('{%Q%}', $q, $this->_query_url);

        $this->load_json_data( $url);
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
     * $solrdoc->str('key_name') and $solrdoc->get_string('key_name')
     *
     * Returns a value for a given key or nothing if the value does
     * not exist.
     *
     * @param  $key
     * @return string
     */
    public function str($key) { return $this->get_string($key); }
    public function get_string($key) {

        $output = '';

        if (!isset($this->_data[$key])) {
            return $output;
        }

        $data = $this->_data[$key];

        if ( is_array($data) ) {
            if ( sizeof($data) == 1) {
                $output = $data[0];
            } elseif ( sizeof($data) > 1) {
                $output = implode(' ', $data);
            }
        } else {
            $output = $data;
        }

        return $this->clean_string($output);
    }

    /**
     * $solrdoc->get_value_list('key_name')
     *
     * Returns values for a single key in a list format (for ul or ol)
     * Used like:
     *
     * <?php echo '<ul>' . $solrdoc->get_value_list('key_name') . '</ul>'
     *
     * @param  $key
     *
     */
    public function get_value_list( $key ) {
        $output = '';

        if (!isset($this->_data[$key])) {
            return $output;
        }

        $data = $this->_data[$key];

        if ( is_array($data) ) {
            foreach ($data as $value) {
                $output .= '<li>' . $this->clean_string($value) . '</li>';
            }
        } else {
            $output = '<li>' . $this->clean_string($data) . '</li>';
        }
        return $output;
    }


    /**
     * $solrdoc->get_key_value_list(array('key_name1', 'key2'));
     * $solrdoc->get_key_value_list(array('key_name1' => "Pretty Title"
     *                                    'key2' => "Pretty Too"), 'dl' );
     *
     * Returns a list of keys and values either as an ol, ul, or dl.
     *
     * Pass no variables and will return the entire list of $this->_data.
     *
     * Pass it an array of keys you wish to have output with optional
     * (and probably desired) pretty titles.
     *
     * @param  array  $args
     * @param  string $type
     */
    public function get_key_value_list($args = array(), $type = 'ul' ) {

        $output = '';

        // if an empty array, just returnget all the keys to make this quick!
        if (!$args) {
            $args = array_keys($this->_data);
        }

        if ($type != 'ul' && $type != 'dl' && $type != 'ol') {
            return 'Specify a $type that is either ul, ol, or dl';
            # code...
        }

        foreach ($args as $key => $title) {

            if (is_numeric($key)) {
                $key = $title;
            }

            if (!isset($this->_data[$key])) {
                continue;
            }

            $values = $this->_data[$key];

            if ( $type == 'ul' || $type == 'ol') {
                $output .= '<li>' . $title . '</li>';
                $output .= '<'. $type . '>';
            } else {
                $output .= '<dt>' . $title . '</dt>';
            }

            if ( $type == 'ul' || $type == 'ol') {
                $values_tag = 'li';
            } else {
                $values_tag = 'dd';
            }

            if ( is_array( $values ) ) {
                foreach ($values as $value) {
                    $output .= '<'.$values_tag.'>' . $this->clean_string( $value ) . '</'.$values_tag.'>';
                }
            } else {
                $output .= '<'.$values_tag.'>';
                $output .= $this->clean_string($values);
                $output .= '</'.$values_tag.'>';
            }

            if ( $type == 'ul' || $type == 'ol') {
                $output .= '</' . $type . '>';
            }
        }

        return $output;
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

    /**
     * Some strings have a '\n' and it's good to clean that out.
     * @param  [type] $val
     * @return string
     */
    private function clean_string( $val ){
        $str = strval( $val );
        $str = str_replace("\r\n", "", $str);
        return $str;
    }

}
