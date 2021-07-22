<?php

namespace App\Shared\Core;

use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class TokenSubscriber implements EventSubscriberInterface
{

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof TokenAuthenticatedController) {

            $request = $event->getRequest();

            $authHeader = $request->headers->get('authorization');

            if (empty($authHeader) ) {
                throw new AccessDeniedHttpException('This action needs a valid token!');
            }

            $requestToken = explode(' ', $authHeader);
            $token1 = $requestToken[1] ?? '';

            if (empty($token1) ) {
                throw new AccessDeniedHttpException('Token invalid or not sent!');
            }

            $config = getConfigJWT();

            try {
                $token = $config->parser()->parse($token1);

                $config->setValidationConstraints(
                    new IssuedBy('http://app.renegociacao'),
                    new SignedWith(new Sha256(), InMemory::plainText($_ENV['JWT_SECRET'])),
                    new LooseValidAt(SystemClock::fromUTC())
                );

                $constraints = $config->validationConstraints();
                $config->validator()->assert($token, ...$constraints);

                $request->attributes->add([
                    'user' => [
                        'ts_usuario_id' => $token->claims()->get('ts_usuario_id'),
                        'uid' => $token->claims()->get('uid'),
                        'roles' => $token->claims()->get('roles')
                    ]
                ]);
            } catch (Exception $e) {
                throw new AccessDeniedHttpException($e->getMessage());
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController'
        ];
    }
}
