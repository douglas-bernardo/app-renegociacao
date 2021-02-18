<?php

namespace App\Controller;

use App\Database\Criteria;
use App\Database\Filter;
use App\Database\Repository;
use App\Database\Transaction;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use DateTimeImmutable;

class SessionController
{
    public function create(Request $request)
    {
        try {
            $data = $request->toArray();

            Transaction::open($_ENV['APPLICATION']);

            $repository = new Repository('App\Model\User');
            $criteria = new Criteria();
            $criteria->add(new Filter('email', '=', $data['email']));

            $user = $repository->load($criteria);

            if (!$user) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'Credentials' => 'Incorrect e-mail/password!'
                    ]
                ], 401);
            }

            $passwordMatched = password_verify($data['password'], $user[0]->password);

            if (!$passwordMatched) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'Credentials' => 'Incorrect e-mail/password!'
                    ]
                ], 401);
            }

            $now = new DateTimeImmutable();
            $tokenIdentity = md5(uniqid(rand(), true));
            $config = getConfigJWT();

            $token = $config->builder()
                ->issuedBy('http://app.renegociacao')
                ->identifiedBy($tokenIdentity)
                ->expiresAt($now->modify('+1 day'))
                ->withHeader('ts_usuario_id', $user[0]->ts_usuario_id)
                ->withHeader('uid', $user[0]->id)
                ->withHeader('tokenIdentity', $tokenIdentity)
                ->getToken($config->signer(), $config->signingKey());

            unset($user[0]->password);
            return new JsonResponse([
                'status' => 'success',
                'user' => $user[0]->toArray(),
                'token' => $token->toString()
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ],500);
        }
    }
}
