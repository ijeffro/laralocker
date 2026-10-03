<?php

namespace Ijeffro\Laralocker\Exceptions;

use Illuminate\Http\Client\Response;
use RuntimeException;

class LearningLockerException extends RuntimeException
{
    public ?Response $response = null;

    public static function fromResponse(Response $response, string $method, string $path): static
    {
        $message = $response->json('message') ?? $response->json('error') ?? trim($response->body());

        $exception = new static(
            sprintf('Learning Locker answered %s %s with %d: %s', $method, $path, $response->status(), $message ?: $response->reason()),
            $response->status(),
        );
        $exception->response = $response;

        return $exception;
    }

    public static function missingCredentials(): static
    {
        return new static('Learning Locker is not configured: set LEARNING_LOCKER_URL, LEARNING_LOCKER_KEY and LEARNING_LOCKER_SECRET.');
    }

    public function status(): ?int
    {
        return $this->response?->status();
    }
}
