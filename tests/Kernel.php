<?php

/*
 * This file is part of the tbcd/doctrine-hfsql-driver package.
 *
 * (c) Thomas Beauchataud <thomas.beauchataud@yahoo.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TBCD\Doctrine\HFSQLDriver\Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Exception;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Kernel extends \Symfony\Component\HttpKernel\Kernel
{

    /**
     * @var array
     */
    private array $doctrineConfig;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->doctrineConfig = $config;
        parent::__construct('test', false);
    }


    /**
     * @inheritDoc
     */
    public function registerBundles(): iterable
    {
        yield new DoctrineBundle();
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function (ContainerBuilder $container) {
            $container->loadFromExtension('doctrine', $this->doctrineConfig);
        });
    }
}