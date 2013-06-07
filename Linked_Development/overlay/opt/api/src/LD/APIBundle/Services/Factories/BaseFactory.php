<?php
namespace LD\APIBundle\Services\Factories;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base entity factory
 */
abstract class BaseFactory implements FactoryInterface
{
    protected $container = null;

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

    /**
     * Get as human readable type for an object
     *
     * @param mixed $obj An array, object or literal
     *
     * @return string
     */
    public function getType($obj)
    {
        if (is_object($obj)) {

            return get_class($obj);
        }

        return gettype($obj);
    }
}
