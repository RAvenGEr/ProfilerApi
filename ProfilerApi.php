<?php

/*
 * Super simple wrapper for the Profiler API
 * @author David Webb
 * 
 * @note The parameters for each detail array are described in the Profiler API
 * documentation.
 * 
 */

namespace dpwlabs\profiler;

class ProfilerApi {
    /* Configuration
     *
     * apikey
     * apipass
     * profiler_url
     * profiler_db
     */

    private $config = [];

    public function setConfig(array $configs) {
        $this->config = $configs;
    }

    public function processCCDonation(array $detail, $db = null) {
        return $this->postApiCall('integration.send', 'OLDON', $detail);
    }

    public function postPledge(array $detail) {
        return $this->postApiCall('integration.send', 'PLG', $detail);
    }
    
    public function postMembership(array $detail) {
        return $this->postApiCall('integration.send', 'MEM', $detail);
    }

    private function postApiCall($method, $datatype, array $data, $db = null) {
        if ($db === null && isset($this->config['profiler_db'])) {
            $db = $this->config['profiler_db'];
        } else if ($db === null) {
            return false;
        }
        $url = 'https://' . $this->config['profiler_url'] . '/ProfilerPROG/api/api_call.cfm';
        $post_data = ['DB' => $this->config['profiler_db'],
            'method' => $method, 'apikey' => $this->config['apikey'],
            'apipass' => $this->config['apipass'], 'datatype' => $datatype];
        $post = array_merge($post_data, $data);
        $result = $this->curl_post($url, $post);
        if ($method == 'integration.send') {
            if (strpos($result,'Pass')) {
                return true;
            }
        }
        return $result;
    }

    /**
     * Send a POST requst using cURL
     * @param string $url to request
     * @param array $post values to send
     * @param array $options for cURL
     * @return string
     */
    private function curl_post($url, array $post = NULL, array $options = array()) {
        $defaults = array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 4,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => http_build_query($post)
        );

        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        if (!$result = curl_exec($ch)) {
            return false;
            //trigger_error(curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

}
