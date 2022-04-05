<?php

/**
 * Utils class
 */
class Utils {
    /**
     * get response from curl request
     */
    public function send_request($url, $params = array(), $method = 'GET') {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        } else if ($method == 'GET') {
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);
        return $response;
    }
}
