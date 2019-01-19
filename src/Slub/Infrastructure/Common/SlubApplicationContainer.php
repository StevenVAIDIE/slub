<?php

declare(strict_types=1);

namespace Slub\Infrastructure\Common;

use Psr\Container\ContainerInterface;
use Slub\Application\PutPRToReview\PutPRToReviewHandler;
use Slub\Domain\Query\IsSupportedInterface;
use Slub\Domain\Repository\PRRepositoryInterface;
use Slub\Infrastructure\Persistence\FileBased\Repository\FileBasedPRRepository;
use Slub\Infrastructure\Persistence\InMemory\Query\InMemoryIsSupported;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author    Samir Boulil <samir.boulil@akeneo.com>
 * @copyright 2019 Akeneo SAS (http://www.akeneo.com)
 */
class SlubApplicationContainer implements ContainerInterface
{
    /** @var ContainerInterface */
    private $container;

    const PERSISTENCE_ROOT_DIR_PARAMETER = 'persistence.file_based.root_dir';

    public function __construct()
    {
        $this->container = $this->buildContainer();
    }

    public function get($id)
    {
        return $this->container->get($id);
    }

    public function has($id)
    {
        return $this->container->has($id);
    }

    private function buildContainer(): ContainerInterface
    {
        $containerBuilder = new ContainerBuilder();
        $this->loadConfigFiles($containerBuilder);

        /**
         * Handler
         */
        $containerBuilder->register(PutPRToReviewHandler::class, PutPRToReviewHandler::class)
            ->addArgument(new Reference(PRRepositoryInterface::class))
            ->addArgument(new Reference(IsSupportedInterface::class))
            ->setPublic(true);

        /**
         * Persistence
         */
        $containerBuilder->register(PRRepositoryInterface::class, FileBasedPRRepository::class)
            ->addArgument($this->getPersistencePath($containerBuilder) . '/pr_repository.json')
            ->setPublic(true);

        $containerBuilder->register(IsSupportedInterface::class, InMemoryIsSupported::class)
            ->addArgument(['akeneo/pim-community-dev'])
            ->setPublic(true);


        $containerBuilder->compile();

        return $containerBuilder;
    }

    private function getPersistencePath(ContainerBuilder $containerBuilder): string
    {
        return sprintf('%s/%s', $this->getProjectDir(), 'var/persistence');
    }

    private function getProjectDir(): string
    {
        return __DIR__ . '/../../../..';
    }

    /**
     * @param $containerBuilder
     *
     */
    private function loadConfigFiles(ContainerBuilder $containerBuilder): void
    {
        $loader = new YamlFileLoader($containerBuilder, new FileLocator($this->getProjectDir()));
        $loader->load($this->getProjectDir() . '/config/parameters.yml');
    }
}
