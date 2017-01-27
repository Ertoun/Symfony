<?php
// src/Perso/PlatformBundle/Entity/AdvertRepository.php

namespace Perso\PlatformBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class AdvertRepository extends EntityRepository
{
  public function myFindAll()
  {
    // Méthode 1 : en passant par l'EntityManager
    // $queryBuilder = $this->_em->createQueryBuilder()
    //   ->select('a')
    //   ->from($this->_entityName, 'a')
    // ;
    // Dans un repository, $this->_entityName est le namespace de l'entité gérée
    // Ici, il vaut donc Perso\PlatformBundle\Entity\Advert

    // Méthode 2 : en passant par le raccourci (je recommande)
      return $this
    ->createQueryBuilder('a')
    ->getQuery()
    ->getResult()
  ;
  }
  public function getAdvertWithCategories(array $categoryNames)
  {
    $qb = $this->createQueryBuilder('a');

    $qb->innerJoin('a.categories', 'c')->addSelect('c');

  // filtre
    $qb->where($qb->expr()->in('c.name', $categoryNames));

    return $qb->getQuery()->getResult();
  }

  public function getApplicationsWithAdvert($limit)
  {
    $qb = $this->createQueryBuilder('a');

    $qb->innerJoin('a.advert', 'adv')->addSelect('adv');

    $qb->setMaxResult($limit);

    return $qb->getQuery()->getResult();
  }
}