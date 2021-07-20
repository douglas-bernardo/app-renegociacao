<?php


namespace App\Shared\Facades\ContainerDependenceInjection;


use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ContainerDependenceInjection
 * @package App\Shared\Facades\ContainerDependenceInjection
 */
class ContainerDependenceInjection
{

    /**
     * @var ContainerBuilder
     */
    protected static ContainerBuilder $containerBuilder;

    /**
     * ContainerDependenceInjection constructor.
     */
    private function __construct(){}

    /**
     * @return ContainerBuilder
     */
    public static function getInstance(): ContainerBuilder
    {
        if (empty(self::$containerBuilder)){
            self::$containerBuilder = new ContainerBuilder();
        }
        return self::$containerBuilder;
    }
}