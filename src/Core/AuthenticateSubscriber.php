<?php

namespace App\Core;

use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthenticateSubscriber implements EventSubscriberInterface
{
    public function onRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        $authHeader = $request->headers->get('authorization');

        if (!$authHeader) {
            throw new AccessDeniedHttpException('This action needs a valid token!');
        }

        $requestToken = explode(' ', $authHeader);

        $config = getConfigJWT();

        try {

            $token = $config->parser()->parse($requestToken[1]);

            $config->setValidationConstraints(
                new IssuedBy('http://app.renegociacao'),
                new SignedWith(new Sha256(), InMemory::plainText($_ENV['JWT_SECRET'])),
                new LooseValidAt(SystemClock::fromUTC())
            );

            $constraints = $config->validationConstraints();

            $config->validator()->assert($token, ...$constraints);

            $request->attributes->add([
                'user' => [
                    'ts_usuario_id' => $token->headers()->get('ts_usuario_id'),
                    'uid' => $token->headers()->get('uid')
                ]
            ]);
        } catch (Exception $e) {
            throw new AccessDeniedHttpException($e->getMessage());
        }
    }

    public static function getSubscribedEvents(): array
    {
        return ['request' => 'onRequest'];
    }
}
