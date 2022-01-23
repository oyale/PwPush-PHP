<?php

namespace PwPush;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

/**
 * Push a secret to any instance of PasswordPush.
 */
class PwPush
{
    private const  API_URI = "p.json";
    private string $urlBase = "https://pwpush.com";
    private string $payload;
    private ?array $options;
    private array  $optionsKeys = ['expire_after_days', 'expire_after_views', 'note', 'retrieval_step', 'deletable_by_viewer'];
    private bool   $validate = false;
    private string $JSON;

    /**
     * Private constructor
     * @param string $payload (Required. The secret to push)
     * @param array|null $options (An associative array with optional conf)
     * @param string|null $urlBase (The base URL for the instance. Default: https://pwpush.com)
     * @param bool|null $validate (Validate the JSON against the schema defined)
     * @throws Exception
     */
    private function __construct(string $payload, ?array $options, ?string $urlBase, ?bool $validate)
    {
        $this->payload = $payload;
        $this->options = $options ?? null;
        $this->urlBase = $urlBase ?? $this->urlBase;
        $this->validate = $validate ?? false;
        if ($validate) {
            $this->JSON = $this->buildJSON($this->payload, $this->options);
        }
    }


    /**
     * Static function to push a new secret to a Password Push instance
     * @param string $payload (Required. The secret to push)
     * @param array|null $options (An associative array with optional conf)
     * @param string|null $urlBase (The base URL for the instance. Default: https://pwpush.com)
     * @param bool|null $validate (Validate the JSON against the schema defined)
     * @return string (The response from PwPush API)
     * @throws GuzzleException
     * @throws Exception Threw when the JSON's validation fails
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

    /**
     * Encode the payload + options array to JSON
     * @throws Exception
     */
    private function buildJSON()
    {

        return json_encode($this->getArray());
    }


    /**
     * Push the secret to PwPush and returns the API response or an Exception in failure
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    private function pushPassword(): array
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
     * Generates an array with payload and options to be encoded in JSON
     * @return array
     * @throws Exception
     */
    private function getArray(): array
    {
        $array['payload'] = $this->payload;
        if (!empty( $this->options)) {
            foreach ($this->optionsKeys as $key) {
                if (!isset($this->options[$key])){
                    continue;
                }
                if (is_null($this->options[$key])) {
                    continue;
                }
                $array['password'][$key] = $this->options[$key];
            }
        }
        return $array;
    }
}