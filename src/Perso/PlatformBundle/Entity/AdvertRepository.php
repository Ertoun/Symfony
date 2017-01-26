<?php
// src/Perso/PlatformBundle/Entity/AdvertRepository.php

namespace Perso\PlatformBundle\Entity;

use Doctrine\ORM\EntityRepository;

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
}