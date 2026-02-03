<?php

declare(strict_types = 1);

namespace EcomailGoSms\Contracts;

interface HttpClient
{

    /**
     * Make HTTP request to GoSms API
     *
     * @param string $method HTTP method
     * @param string $uri API endpoint
     * @param array<string, mixed> $data Request data
     * @param array<string, string> $headers Additional headers
     * @return array{status: int, body: array<string, mixed>}
     */
    public function request(string $method, string $uri, array $data = [], array $headers = []): array;

}
