<?php
namespace LD\APIBundle\Services\Factories;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base entity factory
 */
abstract class BaseFactory implements ContainerAwareInterface, FactoryInterface
{
    protected $container = null;

    // $router = $this->get('router');

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Get the container object
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    public function getType($obj)
    {
        if (is_object($obj)) {

            return get_class($obj);
        }

        return gettype($obj);
    }
}
