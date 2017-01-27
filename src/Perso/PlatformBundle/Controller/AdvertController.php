<?php

// src/Perso/PlatformBundle/Controller/AdvertController.php

namespace Perso\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        if ($page < 1)
        {
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        $listAdverts = $this->getDoctrine()->getManager()->getRepository('PersoPlatformBundle:Advert')->findAll();

        return $this->render('PersoPlatformBundle:Advert:index.html.twig', array('listAdverts' => $listAdverts));
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
      $em = $this->getDoctrine()->getManager();

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

      $em->flush();

      return $this->render('PersoPlatformBundle:Advert:delete.html.twig');
    }

    public function menuAction()
    {
      $em = $this->getDoctrine()->getManager();

      $listAdverts = $em->getRepository('PersoPlatformBundle:Advert')->findBy(
        array(),  // pas de critère
        array('date' => 'desc'),  //trie date desc
        $limit, //limit le nb d'annonces
        0); //offset 0 = à partir du 1er

      return $this->render('PersoPlatformBundle:Advert:menu.html.twig', array(
        // Tout l'intérêt est ici : le contrôleur passe
        // les variables nécessaires au template !
        'listAdverts' => $listAdverts
      ));
    }
}