<?php

namespace App\DataProvider;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\PaginationExtension;

class UserDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $managerRegistry;
    private $paginationExtension;
    private $context;

    public function __construct(ManagerRegistry $managerRegistry, PaginationExtension $paginationExtension)
    {
       $this->managerRegistry = $managerRegistry;
       $this->paginationExtension = $paginationExtension;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        $this->context = $context;
        return $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null)
    {
        $queryBuilder = $this->managerRegistry
            ->getManagerForClass($resourceClass)
            ->getRepository($resourceClass)->createQueryBuilder('test')
            ->where('test.archive = :archive')
            ->setParameter('archive', 0);
        
        
        //$this->paginationExtension->applyToCollection($queryBuilder, new QueryNameGenerator(), $resourceClass, $operationName, $this->context);

        if ($this->paginationExtension instanceof QueryResultCollectionExtensionInterface
            && $this->paginationExtension->supportsResult($resourceClass, $operationName, $this->context)) {
            return $this->paginationExtension->getResult($queryBuilder, $resourceClass, $operationName, $this->context);
        }  
        return $queryBuilder->getQuery()->getResult();
    }
}