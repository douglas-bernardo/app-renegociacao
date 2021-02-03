<?php

namespace App\Controller;

use App\Core\AbstractController;
use App\Database\Criteria;
use App\Database\Filter;
use App\Database\Repository;
use App\Database\Transaction;
use App\Model\User;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController
{

    public function index()
    {        
        try {
            Transaction::open($_ENV['APPLICATION']);

            $result = array();
            $repository = new Repository('App\Model\User');
            $criteria = new Criteria;
            $usuarios = $repository->load($criteria);

            if ($usuarios) {
                foreach ($usuarios as $usuario) {
                    $result[] = $usuario->toArray();
                }
            }
            
            return new JsonResponse([
                'total' => count($result),
                'data' => $result
            ]);

            Transaction::close();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function create(Request $request)
    {
        try {

            $data = $request->toArray();
            $data = filter_var_array($data, FILTER_SANITIZE_STRING);

            Transaction::open($_ENV['APPLICATION']);

            $repository = new Repository('App\Model\User');
            $criteria = new Criteria();
            $criteria->add(new Filter('email', '=', $data['email']));
            $checkUserExist = $repository->load($criteria);

            if ($checkUserExist) {
                return new JsonResponse(['error' => 'Email address already exists!']);
            }

            $user = (new User())->fromArray($data);
            $user->password = password_hash($user->password, PASSWORD_DEFAULT);
            $user->store();

            Transaction::close();

            return new JsonResponse(['user' => $user->toArray()]);
        } catch (Exception $e) {
            Transaction::rollback();
            return new JsonResponse(['error' => $e->getMessage()]);
        }
    }
}
