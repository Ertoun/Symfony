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
                        'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
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
        $advert = array(
        'title'   => 'Recherche développpeur Symfony2',
      'id'      => $id,
      'author'  => 'Alexandre',
      'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
      'date'    => new \Datetime());

        return $this->render('PersoPlatformBundle:Advert:view.html.twig', array(
      'advert' => $advert
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
    // Création de l'entité
    $advert = new Advert();
    $advert->setTitle('Recherche développeur Symfony.');
    $advert->setAuthor('Alexandre');
    $advert->setContent("Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…");
    // On peut ne pas définir ni la date ni la publication,
    // car ces attributs sont définis automatiquement dans le constructeur

    // On récupère l'EntityManager
    $em = $this->getDoctrine()->getManager();

    // Étape 1 : On « persiste » l'entité
    $em->persist($advert);

    // Étape 2 : On « flush » tout ce qui a été persisté avant
    $em->flush();

    $antispam = $this->container->get('Perso_platform.antispam');

    $text = '.....................................................';
    if ($antispam->isSpam($text)) {
        throw new \Exception("Detected as spam!");

    }

     else if ($request->isMethod('POST')) {
      // Ici, on s'occupera de la création et de la gestion du formulaire

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

      // Puis on redirige vers la page de visualisation de cettte annonce
      return $this->redirectToRoute('Perso_platform_view', array('id' => $advert->getID()));
    }

    // Si on n'est pas en POST, alors on affiche le formulaire
    return $this->render('PersoPlatformBundle:Advert:add.html.twig');
  }

  public function editAction($id, Request $request)
  {
    $advert = array(
      'title'   => 'Recherche développpeur Symfony',
      'id'      => $id,
      'author'  => 'Alexandre',
      'content' => 'Lemon',
      'date'    => new \Datetime()
    );

    return $this->render('PersoPlatformBundle:Advert:edit.html.twig', array(
      'advert' => $advert
    ));
  }

  public function deleteAction($id)
  {
    // Ici, on récupérera l'annonce correspondant à $id

    // Ici, on gérera la suppression de l'annonce en question

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