<?php

namespace App\DataPersister;

use App\Entity\Profil;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

final class ProfilDataPersister implements ContextAwareDataPersisterInterface
{
    private $em;
    private $UserRepository;
    public function __construct(EntityManagerInterface $em, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Profil;
    }

    public function persist($data, array $context = [])
    {
      $data->setLibelle($data->getLibelle());
      $data->setArchive(0);
      $this->em->persist($data);
      $this->em->flush();
      return $data;
    }

    public function remove($data, array $context = [])
    {
      $data->setArchive(1);
      foreach ($data->getUsers() as $user){
        $user->setArchive(1);
      }
      $this->em->persist($data);
      $this->em->flush();
    }

}