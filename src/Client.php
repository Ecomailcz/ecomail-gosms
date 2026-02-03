<?php

declare(strict_types = 1);

namespace EcomailGoSms;

use EcomailGoSms\Exceptions\BadRequest;
use EcomailGoSms\Exceptions\InvalidRequest;
use EcomailGoSms\Exceptions\UnauthorizedRequest;
use EcomailGoSms\Requests\AuthenticationRequest;
use EcomailGoSms\Requests\RefreshAccessTokenRequest;
use EcomailGoSms\Requests\Request;
use EcomailGoSms\Responses\AuthenticationResponse;
use EcomailGoSms\Responses\RefreshAccessTokenResponse;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use SensitiveParameter;
use Throwable;

use function in_array;
use function sprintf;

abstract class Client
{

    public function __construct(
        protected readonly string $publicKey,
        #[SensitiveParameter]
        protected readonly string $privateKey,
        protected ?string $accessToken = null,
        protected readonly ?int $defaultChannel = null,
        protected readonly string $grantType = 'password',
        protected readonly string $scope = '',
        private readonly GuzzleClient $httpClient = new GuzzleClient(['base_uri' => Request::BASE_URL, 'timeout' => 10]),
    ) {
    }

    public function getDefaultChannel(): ?int
    {
        return $this->defaultChannel;
    }

    /**
     * @throws \EcomailGoSms\Exceptions\BadRequest
     * @throws \Throwable
     */
    public function refreshToken(string $refreshToken): RefreshAccessTokenResponse
    {
        $request = new RefreshAccessTokenRequest($refreshToken);

        return new RefreshAccessTokenResponse($this->makeRequest($request));
    }

    /**
     * @throws \EcomailGoSms\Exceptions\BadRequest
     * @throws \Throwable
     */
    public function authenticate(): AuthenticationResponse
    {
        $request = new AuthenticationRequest($this->publicKey, $this->privateKey);

        return new AuthenticationResponse($this->makeRequest($request));
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * @throws \EcomailGoSms\Exceptions\BadRequest
     * @throws \Throwable
     */
    protected function makeRequest(Request $request): ResponseInterface
    {
        try {
            return $this->httpClient->request($request->getMethod(), $request->getEndpoint(), $this->buildRequestHeaders($request));
        } catch (Throwable $throwable) {
            $this->handleExceptions($throwable);

            // @codeCoverageIgnoreStart
            throw $throwable;
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function buildRequestHeaders(Request $request): array
    {
        $options = $request->getOptions();

        if ($this->accessToken !== null) {
            if (!isset($options['headers']) || !is_array($options['headers'])) {
                $options['headers'] = [];
            }

            $options['headers']['Authorization'] = sprintf('Bearer %s', $this->accessToken);
        }

        return $options;
    }

    /**
     * @throws \EcomailGoSms\Exceptions\BadRequest
     * @throws \Throwable
     */
    private function handleExceptions(Throwable $throwable): void
    {
        if ($throwable instanceof ClientException && in_array($throwable->getResponse()->getStatusCode(), [400, 403], true)) {
            throw new BadRequest($throwable->getResponse());
        }

        if ($throwable instanceof ClientException && $throwable->getResponse()->getStatusCode() === 401) {
            throw new UnauthorizedRequest($throwable->getResponse());
        }

        if ($throwable instanceof ClientException && $throwable->getResponse()->getStatusCode() === 422) {
            throw new InvalidRequest($throwable->getResponse());
        }

        throw $throwable;
    }

}
