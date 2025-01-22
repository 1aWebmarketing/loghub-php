<?php

namespace LogHub;

class LogHub
{
    const API_BASE_URL = 'https://api.loghub.cloud/v2';
    const TYPE_INFO = 1;
    const TYPE_SUCCESS = 2;
    const TYPE_WARNING = 3;
    const TYPE_DANGER = 4;
    const TYPE_ERROR = 5;
    const TYPE_ACTION = 6;

    const ENCODING_URL = 'url';
    const ENCODING_JSON = 'json';
    const ENCODING_TEXT = 'text';

    private static string $api_key;
    private static array $timers;

    public static function init(string $api_key)
    {
        self::$api_key = $api_key;
        self::$timers = array();
    }

    public static function log(String $function, $message, Int $type = self::TYPE_INFO, String $url = "", String $encoding = self::ENCODING_TEXT, Int $parent = null) : null | int
    {
        if(!self::$api_key) return null;

        $data = array(
            'domain' => $url,
            'function' => $function,
            'message' => $message,
            'type' => $type,
            'encoding' => $encoding,
            'parent' => $parent,
        );

        $response = self::_post("/log", $data);

        if( $response && $response['status'] == 'ok' )
        {
            return intval($response['id']);
        }
        else
        {
            return null;
        }
    }

    public static function startTimer(string $timer)
    {
        self::$timers[$timer] = microtime(1);
    }

    public static function endTimer(String $timer)
    {
        if(!self::$api_key) return null;
        if( !isset(self::$timers[$timer] ) ) return null;

        $duration = microtime(1) - self::$timers[$timer];

        unset(self::$timers[$timer]);

        $data = array(
            'timer' => $timer,
            'duration' => $duration,
        );

        return self::_post("/timer", $data);
    }

    public static function sendTimer(String $timer, Float $duration)
    {
        if(!self::$api_key) return null;
        
        $data = array(
            'timer' => $timer,
            'duration' => $duration,
        );

        return self::_post("/timer", $data);
    }



    private function _get($endpoint)
    {
        $ch = curl_init( self::API_BASE_URL . $endpoint );
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: Bearer ' . self::$api_key,
        ]);

        $ret = curl_exec($ch);

        if(curl_error($ch)) {
            curl_error($ch);
            return 0;
        }

        return json_decode($ret, 1);

    }

    private function _delete($endpoint)
    {
        $ch = curl_init( self::API_BASE_URL . $endpoint );
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: Bearer ' . self::$api_key,
        ]);

        $ret = curl_exec($ch);

        if(curl_error($ch)) {
            curl_error($ch);
            return 0;
        }

        return json_decode($ret, 1);

    }

    private function _put($endpoint, $data)
    {

        $ch = curl_init( self::API_BASE_URL . $endpoint );
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data) );

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . self::$api_key,
        ]);

        $ret = curl_exec($ch);

        if(curl_error($ch)) {
            curl_error($ch);
            return 0;
        }

        return json_decode($ret, 1);

    }

    private static function _post($endpoint, $data)
    {

        $ch = curl_init( self::API_BASE_URL . $endpoint );
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data) );

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . self::$api_key,
        ]);

        $ret = curl_exec($ch);

        if(curl_error($ch)) {
            curl_error($ch);
            return 0;
        }

        return json_decode($ret, 1);
    }
}
