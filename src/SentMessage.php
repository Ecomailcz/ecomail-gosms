<?php

declare(strict_types = 1);

namespace EcomailGoSms;

final readonly class SentMessage
{

    public function __construct(public string $status, public string $recipient, public string $customId, public string $link)
    {
    }

    /**
     * @param array{
     *     status: string,
     *     recipient: string,
     *     custom_id: string,
     *     link: string
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self($data['status'], $data['recipient'], $data['custom_id'], $data['link']);
    }

}
