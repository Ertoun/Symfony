<?php
// src/Perso/PlatformBundle/Entity/Image.php

namespace Perso\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="Perso_image")
 * @ORM\Entity
 */
class Image
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(name="url", type="string", length=255)
   */
  private $url;

  /**
   * @ORM\Column(name="alt", type="string", length=255)
   */
  private $alt;

  // Getters et setters
}