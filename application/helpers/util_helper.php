<?php

function doCurl($url, $params = [], $form_params = true, $access_token = null, $header_params = [])
{
    $curl = curl_init();

    $fp = fopen(FCPATH . 'application/logs/errorlog.txt', 'w');

    echo $url . "\n";

    $config_arr = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_VERBOSE => 1,
        CURLOPT_STDERR => $fp,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
        ),
        //CURLOPT_AUTOREFERER => TRUE,
        //CURLOPT_SSL_VERIFYHOST => false,
        //CURLOPT_SSL_VERIFYPEER => false,
    ];

    if($access_token) {
        $config_arr[CURLOPT_HTTPHEADER][] = 'Authorization: Bearer ' . $access_token;       
    }

    if(count($header_params) > 0) {
        foreach($header_params as $key => $val) {
            $config_arr[CURLOPT_HTTPHEADER][] = "$key: $val";
        }
    }

    if(count($params) > 0) {
        if($form_params) {
            $config_arr[CURLOPT_POSTFIELDS] = paramsToUrlParams($params);
            $config_arr[CURLOPT_HTTPHEADER][] = "Content-Type: application/x-www-form-urlencoded";
        } else {
            $config_arr[CURLOPT_POSTFIELDS] = json_encode($params);
            $config_arr[CURLOPT_HTTPHEADER][] = "Content-Type: application/json";
        }
        
        $config_arr[CURLOPT_CUSTOMREQUEST] = 'POST';
    } else {
        $config_arr[CURLOPT_CUSTOMREQUEST] = 'GET';
    }

    print_r($config_arr);

    curl_setopt_array($curl, $config_arr);

    $response = curl_exec($curl);
    echo $response;
    // log_message('error', $response);

    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    // log_message('error', $httpcode);

    curl_close($curl);
    
    $response = json_decode($response, true);
    return $response;
}

function paramsToUrlParams($params) {
    $query = '';
    foreach($params as $key => $val) {
        $query .= sprintf("%s=%s", $key, $val) . '&';
    }
    return $query;
}

function getUuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}