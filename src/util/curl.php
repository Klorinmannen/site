<?php
namespace util;

class curl
{
    const TIME_OUT_SECONDS = 3;

    public static function execute_get_call($url)
    {
        $handle = curl_init();
        curl_setopt($handle, \CURLOPT_HTTPGET, true);        
        curl_setopt($handle, \CURLOPT_URL, $url);        
        curl_setopt($handle, \CURLOPT_HEADER, false);
        curl_setopt($handle, \CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, \CURLOPT_TIMEOUT, self::TIME_OUT_SECONDS);                             

        $result = curl_exec($handle);
        curl_close($handle);
        return $result;
    }   
}
