<?php


namespace App\Modules\Users\Infra\Database\Repository;


use App\Modules\Users\Infra\Database\Entity\User;
use App\Modules\Users\Repositories\IUserRepository;
use App\Shared\Infra\Database\Criteria;
use App\Shared\Infra\Database\Filter;
use App\Shared\Infra\Database\Repository;
use Exception;

/**
 * Class UserRepository
 * @package App\Modules\Users\Infra\Database\Repository
 */
class UserRepository implements IUserRepository
{

    /**
     * @param string $email
     * @return array|null
     * @throws Exception
     */
    public function findByEmail(string $email): ?User
    {
        $repository = new Repository(User::class);
        $criteria = new Criteria();
        $criteria->add(new Filter('email', '=', $email));
        $result = $repository->load($criteria);
        return $result ? $result[0] : null;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function findAll(): array
    {
        $result = [];
        $users = User::all();
        if ($users) {
            /** @var User $user */
            foreach ($users as $user) {
                $result[] = $user->toArray();
            }
        }
        return $result;
    }

    /**
     * @param int $id
     * @return User|null
     * @throws Exception
     */
    public function findById(int $id): ?User
    {
        $repository = new Repository(User::class);
        $criteria = new Criteria();
        $criteria->add(new Filter('id', '=', $id));
        $result = $repository->load($criteria);
        return $result ? $result[0] : null;
    }

    /**
     * @param array $data
     * @return User|null
     * @throws Exception
     */
    public function create(array $data): ?User
    {
        $user = new User();
        $user->fromArray($data);
        $user->store();
        return $user;
    }
}