<?php

namespace Lumnn\SagePayPi\Exceptions;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class BadResponseException extends \Exception
{
    protected RequestInterface $request;

    protected ResponseInterface $response;

    protected ?\stdClass $decodedBody;

    public function __construct(RequestInterface $request, ResponseInterface $response, ?\stdClass $decodedBody, ?string $message = null)
    {
        $this->request = $request;
        $this->response = $response;
        $this->decodedBody = $decodedBody;

        if (!$message) {
            $message = $this->createMessage();
        }

        parent::__construct($message);
    }

    public function getOpayoCode(): ?string
    {
        if ($this->decodedBody && isset($this->decodedBody->code)) {
            return $this->decodedBody->code;
        }

        return null;
    }

    private function createMessage(): string
    {
        $body = $this->decodedBody;

        if ($body && isset($body->code) && isset($body->description)) {
            return sprintf('[%s] %s', $body->code, $body->description);
        }

        return (string) $this->response->getBody();
    }
}
