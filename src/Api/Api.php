<?php
namespace KpakpandoEventsBooking\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;

class Api
{
    static $token = DEF_PSK_SECRET_KEY;
    public static function invokeApiCall($arParams)
    {
        try
        {
            $client = new Client();
            $headers = [
                'Authorization' => 'Bearer '.self::$token,
                'Content-Type' => 'application/json'
            ];
            $method = strtoupper($arParams['method']);
            $url = $arParams['url']; //will includes query params for GET method

            if ($method == 'POST')
            {
                $body = json_encode($arParams['body']);
                $request = new Request($method, $url, $headers, $body);
                $res = $client->sendAsync($request)->wait();
            }
            else
            {
                if (array_key_exists('params', $arParams))
                {
                    $url .= $arParams['params'];
                }
                $request = new Request($method, $url, $headers);
                $res = $client->sendAsync($request)->wait();
            }
            //echo $res->getBody();exit;
            return $res->getBody();
        }
        catch(RequestException $e)
        {
            getJsonRow(false, 'An error occured');
            /*
            $statusCode = $e->getCode();
            $response = $e->getResponse();
            $response = $response->getBody()->getContents();
            echo $response;
            exit;
            */
        }
        catch(ClientException $e)
        {
            getJsonRow(false, 'An error occured');
            /*
            $statusCode = $e->getCode();
            $response = $e->getResponse();
            $response = $response->getBody()->getContents();
            $response = json_decode($response, true);
            exit;
            */
        }
    }
}