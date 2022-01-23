<?php

namespace PwPush;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class PwOps
{
    private string $id;
    private const  API_URI = "p.json";
    private string $urlBase = "https://pwpush.com";


    private function __construct($id,  ?string $urlBase){
        $this->id = $id;
        $this->urlBase = $urlBase ?? $this->urlBase;
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public static function delete($id, $urlBase = null): bool
    {
        $PwOps = new self($id, $urlBase);
        $client = new Client([
            'base_uri' => $PwOps->urlBase,
            'timeout' => 5.0,
        ]);

        $response = $client->delete( '/p/' . $PwOps->id . '.json');
        $body = (string)$response->getBody();
        if ($response->getStatusCode() !== 200) {
            $reason = $response->getReasonPhrase();
            throw new Exception($reason . " - " . $body);
        }
        $result = json_decode($body, true);
        return (bool) $result['deleted'];

    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public static function get($id, $urlBase = null){
        $PwOps = new self($id, $urlBase);
        $client = new Client([
            'base_uri' => $PwOps->urlBase,
            'timeout' => 7.0,
        ]);

        $response = $client->get( '/p/' . $PwOps->id. '.json');
        $body = (string)$response->getBody();
        if ($response->getStatusCode() !== 200) {
            $reason = $response->getReasonPhrase();
            throw new Exception($reason . " - " . $body);
        }
        $result = json_decode($body, true);
        return $result['payload'];

    }
}