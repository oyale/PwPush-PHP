<?php

namespace PwPush;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class PwPush
{
    private const  API_URI = "p.json";
    private string $urlBase;
    private string $url;
    private string $payload;
    private ?array $options;
    private array  $optionsKeys = ['expire_after_days', 'expire_after_views', 'note', 'retrieval_step', 'deletable_by_viewer'];
    private bool   $validate = false;
    private string $JSON;

    private function __construct(string $payload, ?array $options, ?string $urlBase, ?bool $validate)
    {
        $this->payload = $payload;
        $this->options = $options ?? null;
        $this->urlBase = $urlBase ?? "https://pwpush.com";
        $this->validate = $validate ?? false;
        $this->url = $urlBase . self::API_URI;
        $this->JSON = $this->constructJSON($this->payload, $this->options);
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public static function push(string $payload, ?array $options = null, ?string $urlBase = null, ?bool $validate = false): string
    {
        $PwPush = new self($payload, $options, $urlBase, $validate);
        if ($PwPush->validate && !JSONValidator::validate($PwPush->JSON)) {
            throw new Exception('JSON does not conform to the schema');
        }
        $response = $PwPush->pushPassword();
        return $PwPush->urlBase . '/p/' . $response['url_token'];
    }

    private function constructJSON()
    {

        return json_encode($this->getArray());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    private function pushPassword()
    {
        $client = new Client([
            'base_uri' => $this->urlBase,
            'timeout' => 5.0,
        ]);
        $response = $client->request('POST', '/'.self::API_URI, [
            'json' => $this->getArray()]);
        $body = (string)$response->getBody();
        if ($response->getStatusCode() !== 201) {
            $reason = $response->getReasonPhrase();
            throw new Exception($reason . " - " . $body);
        }

        return json_decode($body, true);
    }

    /**
     * @return array
     */
    private function getArray(): array
    {
        $json['payload'] = $this->payload;
        if (!empty( $this->options)) {
            foreach ($this->optionsKeys as $key) {
                if (is_null($this->options[$key])) {
                    continue;
                }
                $json['password'][$key] = $this->options[$key];
            }
        }
        return $json;
    }
}