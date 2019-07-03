<?php declare(strict_types = 1);

namespace EcomailGoSms;

use GuzzleHttp\Client;

class Client
{

    /**
     * Client ID
     *
     * @var string
     */
    private $client_id;

    /**
     * Client Secret
     *
     * @var string
     */
    private $client_secret;

    /**
     * Default Channel
     *
     * @var int
     */
    private $default_channel;

    /**
     * API key
     *
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * API token
     *
     * @var string
     */
    private $token = null;

    public function __construct(string $client_id, string $client_secret, int $default_channel)
    {
        $this->client = new Client([
            'base_uri' => 'https://app.gosms.cz',
        ]);
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->default_channel = $default_channel;
    }

    public function authenticate(): Client
    {
        $res = $this->client->request('GET', 'oauth/v2/token', [
            'query' => [
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'grant_type' => 'client_credentials',
            ]
        ]);

        if($res->getStatusCode() !== 200) {
            throw new AuthorizationException($res->getBody()->getContents());
        }

        $content = json_decode($res->getBody()->getContents());

        $this->token = $content->access_token; 

        return $this;
    }

    public function sendSms(string $phoneNumber, string $message, ?int $channel): stdClass
    {
        if($channel === null) {
            $channel = $this->default_channel;
        }

        if (!preg_match('~^\+[0-9]{11,12}$~', $phoneNumber)) {
            throw new InvalidNumber('Invalid recipient number format');
        }

        if (!is_string($message) || empty($message)) {
            throw new InvalidFormat('Invalid message format');
        }

        $res = $this->makeRequest('POST', 'api/v1/messages', [
            'message' => $message,
            'recipients' => $phoneNumber,
            'channel' => $channel
        ]);

        return $res;
    }

    private makeRequest(string $type, string $endpoint, ?array $params): stdClass
    {
        if($type === 'GET') {
            $params['access_token'] = $this->token;
            $res = $this->client->request('GET', $endpoint, [
                'query' => $params
            ]);
        } elseif($type === 'POST') {
            $res = $this->client->request('POST', $endpoint, [
                'json' => $params,
                'query' => [
                    'access_token' => $this->token,
                ],
            ]);
        }

        if($res->getStatusCode() !== 200) {
            throw new RequestException($res->getBody()->getContents());
        }

        return json_decode($res->getBody()->getContents());
    }

}