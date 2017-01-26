<?php

// src/Perso/PlatformBundle/Controller/AdvertController.php

namespace Perso\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Perso\PlatformBundle\Entity\Advert;
use Perso\PlatformBundle\Entity\Image;
use Perso\PlatformBundle\Entity\Application;
use Perso\PlatformBundle\Entity\AdvertSkill;
use Perso\PlatformBundle\Entity\Skill;

class AdvertController extends Controller
{
    public function indexAction($page)
    {
        // $content = $this->get('templating')->render('PersoPlatformBundle:Advert:index.html.twig', $tableau = array('nom' => 'ertoun'));

        // return new Response($content);
        if ($page = 0) {throw new NotFoundHttpException('Page "'.$page.'" inexistante.');}
        else {
                $listAdverts = array(
                    array(
                        'title'   => 'Recherche développpeur Symfony',
                        'id'      => 1,
                        'author'  => 'Alexandre',
                        'content' => 'Nous recherchons un développeur débutant sur Lyon. Blabla…',
                        'date'    => new \Datetime()),
                      array(
                        'title'   => 'Mission de webmaster',
                        'id'      => 2,
                        'author'  => 'Hugo',
                        'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
                        'date'    => new \Datetime()),
                      array(
                        'title'   => 'Offre de stage webdesigner',
                        'id'      => 3,
                        'author'  => 'Mathieu',
                        'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
                        'date'    => new \Datetime())
                      );
                return $this->render('PersoPlatformBundle:Advert:index.html.twig', array('listAdverts' => $listAdverts));
            }
    }
    public function viewAction($id, Request $request)
    {
      $em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
    $advert = $em
      ->getRepository('PersoPlatformBundle:Advert')
      ->find($id)
    ;

    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    // On avait déjà récupéré la liste des candidatures
    $listApplications = $em
      ->getRepository('PersoPlatformBundle:Application')
      ->findBy(array('advert' => $advert))
    ;

    // On récupère maintenant la liste des AdvertSkill
    $listAdvertSkills = $em
      ->getRepository('PersoPlatformBundle:AdvertSkill')
      ->findBy(array('advert' => $advert))
    ;

    return $this->render('PersoPlatformBundle:Advert:view.html.twig', array(
      'advert'           => $advert,
      'listApplications' => $listApplications,
      'listAdvertSkills' => $listAdvertSkills
    ));
    }

    public function byeAction()
    {
        $ciao = $this->get('templating')->render('PersoPlatformBundle:Advert:bye.html.twig', $tableau = array('nom' => 'ertoun'));

        return new Response($ciao);
    }
    public function viewSlugAction($slug, $year, $_format)
    {
        return new Response(
            "On pourrait afficher l'annonce correspondant au
            slug '".$slug."', créée en ".$year." et au format ".$_format.".");
    }

    // Ajoutez cette méthode :
  public function addAction(Request $request)
  {
    // On récupère l'EntityManager
    $em = $this->getDoctrine()->getManager();
    // Création de l'entité
    $advert = new Advert();
    $advert->setTitle('Recherche développeur Symfony.');
    $advert->setAuthor('Alexandre');
    $advert->setContent("Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…");
    // Création de l'entité Image
    $image = new Image();
    $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
    $image->setAlt('Job de rêve');
    // On lie l'image à l'annonce
    $advert->setImage($image);
    // Création d'une première candidature
    $application1 = new Application();
    $application1->setAuthor('Marine');
    $application1->setContent("J'ai toutes les qualités requises.");
    // Création d'une deuxième candidature par exemple
    $application2 = new Application();
    $application2->setAuthor('Pierre');
    $application2->setContent("Je suis très motivé.");
    // On lie les candidatures à l'annonce
    $application1->setAdvert($advert);
    $application2->setAdvert($advert);
    // On récupère toutes les compétences possibles
    $listSkills = $em->getRepository('PersoPlatformBundle:Skill')->findAll();
    // Pour chaque compétence
    foreach ($listSkills as $skill) {
      // On crée une nouvelle « relation entre 1 annonce et 1 compétence »
      $advertSkill = new AdvertSkill();
      // On la lie à l'annonce, qui est ici toujours la même
      $advertSkill->setAdvert($advert);
      // On la lie à la compétence, qui change ici dans la boucle foreach
      $advertSkill->setSkill($skill);
      // Arbitrairement, on dit que chaque compétence est requise au niveau 'Expert'
      $advertSkill->setLevel('Expert');
      // Et bien sûr, on persiste cette entité de relation, propriétaire des deux autres relations
      $em->persist($advertSkill);
    }
    // Étape 1 : On « persiste » l'entité
    $em->persist($advert);
    // Étape 1 bis : si on n'avait pas défini le cascade={"persist"},
    // on devrait persister à la main l'entité $image
    // $em->persist($image);
    // Étape 1 ter : pour cette relation pas de cascade lorsqu'on persiste Advert, car la relation est
    // définie dans l'entité Application et non Advert. On doit donc tout persister à la main ici.
    $em->persist($application1);
    $em->persist($application2);
    // Étape 2 : On « flush » tout ce qui a été persisté avant
    $em->flush();
    // Reste de la méthode qu'on avait déjà écrit
    if ($request->isMethod('POST')) {
      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
      // Puis on redirige vers la page de visualisation de cettte annonce
      return $this->redirectToRoute('Perso_platform_view', array('id' => $advert->getId()));
    }
    // Si on n'est pas en POST, alors on affiche le formulaire
    return $this->render('PersoPlatformBundle:Advert:add.html.twig');
  }

  public function editAction($id, Request $request)
  {
     $em = $this->getDoctrine()->getManager();
    // On récupère l'annonce $id
    $advert = $em->getRepository('PersoPlatformBundle:Advert')->find($id);
    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }
    // La méthode findAll retourne toutes les catégories de la base de données
    $listCategories = $em->getRepository('PersoPlatformBundle:Category')->findAll();
    // On boucle sur les catégories pour les lier à l'annonce
    foreach ($listCategories as $category) {
      $advert->addCategory($category);
    }
    // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
    // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine
    // Étape 2 : On déclenche l'enregistrement
    $em->flush();
    if ($request->isMethod('POST')) {
      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');
      return $this->redirectToRoute('Perso_platform_view', array('id' => 5));
    }
    return $this->render('PersoPlatformBundle:Advert:edit.html.twig', array(
      'advert' => $advert
    ));
  }

  public function editImageAction($advertId)
  {
    $em = $this->getDoctrine()->getManager();

    // On récupère l'annonce
    $advert = $em->getRepository('PersoPlatformBundle:Advert')->find($advertId);

    // On modifie l'URL de l'image par exemple
    $advert->getImage()->setUrl('test.png');

    // On n'a pas besoin de persister l'annonce ni l'image.
    // Rappelez-vous, ces entités sont automatiquement persistées car
    // on les a récupérées depuis Doctrine lui-même

    // On déclenche la modification
    $em->flush();

    return new Response('OK');
  }

  public function deleteAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    // On récupère l'annonce $id
    $advert = $em->getRepository('PersoPlatformBundle:Advert')->find($id);
    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }
    // On boucle sur les catégories de l'annonce pour les supprimer
    foreach ($advert->getCategories() as $category) {
      $advert->removeCategory($category);
    }
    // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
    // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis DPersotrine
    // On déclenche la modification
    $em->flush();

    return $this->render('PersoPlatformBundle:Advert:delete.html.twig');
  }

  public function menuAction()
  {
        $listAdverts = array(
      array('id' => 2, 'title' => 'Recherche développeur Symfony'),
      array('id' => 5, 'title' => 'Mission de webmaster'),
      array('id' => 9, 'title' => 'Offre de stage webdesigner')
    );

    return $this->render('PersoPlatformBundle:Advert:menu.html.twig', array(
      // Tout l'intérêt est ici : le contrôleur passe
      // les variables nécessaires au template !
      'listAdverts' => $listAdverts
    ));
    }


  public function mailAction()
  {
      $mailer = $this->container->get('mailer');
  }
}