<?php

require_once('utils.php');

/**
 * API Worker class
 */
class APIWorker {
    public $utils;

    public function __construct() {
        $this->utils = new Utils();
    }

    /**
     * get token from api
     */
    private function _get_token() {
        // the url to send the POST request to get token
        $url = API_URL . "/register";

        // the params to send with the request
        $params = array(
            "client_id" => CLIENT_ID,
            "email" => CLIENT_EMAIL,
            "name" => CLIENT_NAME
        );

        // send the request and decode the response
        $response = $this->utils->send_request($url, $params, 'POST');
        $result = json_decode($response, true);

        // return the token
        // !! IMPORTANT: should require validation of the response before returning
        return $result['data']['sl_token'];
    }

    /**
     * get posts from api
     */
    public function get_posts($page_number) {
        // get the token
        $token = $this->_get_token();

        // the url to send the GET request to get posts
        $url = API_URL . "/posts";

        // the params to send with the request
        $params = array(
            "sl_token" => $token,
            "page" => $page_number
        );

        // build query string
        $query_string = http_build_query($params);

        // send the request and decode the response
        $response = $this->utils->send_request($url . '?' . $query_string);
        $result = json_decode($response, true);

        // return the posts
        // !! IMPORTANT: should require validation of the response before returning
        return $result['data']['posts'];
    }
}
