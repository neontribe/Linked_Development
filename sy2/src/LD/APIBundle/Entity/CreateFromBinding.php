<?php

namespace LD\APIBundle\Entity;

use Symfony\Component\Routing\RouterInterface;

interface CreateFromBinding
{
    /**
     * Take a binding entry from virtuoso and return a new object
     *
     * @param array                                      $binding The array of data from virtuoso
     * @param \Symfony\Component\Routing\RouterInterface $router  The router object used to generate the metadata url
     *
     * @return \LD\APIBundle\Entity\Region
     * @throws \RuntimeException
     */
    public static function createFromBinding(array $binding, RouterInterface $router);
}