<?php


namespace App\Shared\Bundle\Controller;


use App\Shared\Bundle\Security\AuthorizationManager;
use App\Shared\Errors\ApiException;
use App\Shared\Facades\ContainerDependenceInjection\ContainerDependenceInjection;
use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class AbstractController
 * @package App\Shared\Bundle\Controller
 */
class AbstractController
{
    /**
     * @var ValidatorInterface
     */
    protected ValidatorInterface $validator;
    /**
     * @var ContainerBuilder
     */
    protected ContainerBuilder $containerBuilder;
    /**
     * @throws Exception
     */

    protected AuthorizationManager $authorizationManager;

    protected array $roles;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->validator = Validation::createValidator();
        $this->authorizationManager = AuthorizationManager::getInstance();

        $this->containerBuilder = ContainerDependenceInjection::getInstance();
        $loader = new YamlFileLoader($this->containerBuilder, new FileLocator());
        $loader->load(CONF_CONTAINER_CONFIG);
    }

    /**
     * @throws ApiException
     */
    public function validate($value, $constraints = null, $groups = null)
    {
        $errors = '';
        $violations = $this->validator->validate($value, $constraints, $groups);
        if (0 !== count($violations)) {
            foreach ($violations as $violation) {
                $errors .= $violation->getPropertyPath() . ':' . $violation->getMessage();
            }
            throw new ApiException($errors);
        }
    }
}