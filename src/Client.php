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

use function sprintf;

abstract class Client
{

    public function __construct(
        private readonly GuzzleClient $guzzleClient,
        protected readonly string $publicKey,
        #[SensitiveParameter]
        protected readonly string $privateKey,
        protected ?string $accessToken = null,
        protected readonly string $grantType = 'password',
        protected readonly string $scope = '',
    ) {
    }

    /**
     * @throws \EcomailGoSms\Exceptions\BadRequest
     * @throws \Throwable
     */
    public function refreshToken(string $accessToken): RefreshAccessTokenResponse
    {
        $this->accessToken = $accessToken;
        $request = new RefreshAccessTokenRequest($accessToken);

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
            return $this->guzzleClient->request($request->getMethod(), $request->getEndpoint(), $this->buildRequestHeaders($request));
        } catch (Throwable $throwable) {
            $this->handleExceptions($throwable);

            // This line will never be reached due to handleExceptions throwing, but PHPStan needs it
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

        // Add Authorization header if access token is available
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
        if ($throwable instanceof ClientException && $throwable->getResponse()->getStatusCode() === 400) {
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
