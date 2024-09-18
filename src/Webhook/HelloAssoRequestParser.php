<?php

namespace App\Webhook;

use \SensitiveParameter;
use Symfony\Component\HttpFoundation\ChainRequestMatcher;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcher\IsJsonRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\MethodRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\PathRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RemoteEvent\RemoteEvent;
use Symfony\Component\Webhook\Client\AbstractRequestParser;
use Symfony\Component\Webhook\Exception\RejectWebhookException;

final class HelloAssoRequestParser extends AbstractRequestParser
{
    protected function getRequestMatcher(): RequestMatcherInterface
    {
        return new ChainRequestMatcher([
            new MethodRequestMatcher('POST'),
            new PathRequestMatcher('regex'),
            new IsJsonRequestMatcher(),
        ]);
    }

    /**
     * @throws JsonException
     */
    protected function doParse(Request $request, #[SensitiveParameter] string $secret): ?RemoteEvent
    {
        // Validate the request against $secret.
        $authToken = $request->query->get('X-Authentication-Token');

        if ($authToken !== $secret) {
            throw new RejectWebhookException(Response::HTTP_UNAUTHORIZED, 'Invalid authentication token.');
        }

        // Validate the request payload.
        if (!$request->getPayload()->get('eventType') == 'Payment') {
            throw new RejectWebhookException(Response::HTTP_BAD_REQUEST, 'Request payload is not a payment');
        }

        // Parse the request payload and return a RemoteEvent object.
        $payload = $request->getPayload();

        return new RemoteEvent(
            $payload->getString('name'),
            $payload->getString('id'),
            $payload->all(),
        );
    }
}
