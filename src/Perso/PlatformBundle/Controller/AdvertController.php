<?php

// src/Perso/PlatformBundle/Controller/AdvertController.php

namespace Perso\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdvertController extends Controller
{
    public function indexAction($page)
    {
        // $content = $this->get('templating')->render('PersoPlatformBundle:Advert:index.html.twig', $tableau = array('nom' => 'ertoun'));

        // return new Response($content);
        if ($page < 1) {
      // On déclenche une exception NotFoundHttpException, cela va afficher
      // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
      throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
    }

    // Ici, on récupérera la liste des annonces, puis on la passera au template

    // Mais pour l'instant, on ne fait qu'appeler le template
    return $this->render('PersoPlatformBundle:Advert:index.html.twig');
    }

    public function viewAction($id, Request $request)
    {
        return $this->render('PersoPlatformBundle:Advert:view.html.twig', array(
      'id' => $id
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
     if ($request->isMethod('POST')) {
      // Ici, on s'occupera de la création et de la gestion du formulaire

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

      // Puis on redirige vers la page de visualisation de cettte annonce
      return $this->redirectToRoute('Perso_platform_view', array('id' => 5));
    }

    // Si on n'est pas en POST, alors on affiche le formulaire
    return $this->render('PersoPlatformBundle:Advert:add.html.twig');
  }

  public function editAction($id, Request $request)
  {
    // Ici, on récupérera l'annonce correspondante à $id

    // Même mécanisme que pour l'ajout
    if ($request->isMethod('POST')) {
      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

      return $this->redirectToRoute('Perso_platform_view', array('id' => 5));
    }

    return $this->render('PersoPlatformBundle:Advert:edit.html.twig');
  }

  public function deleteAction($id)
  {
    // Ici, on récupérera l'annonce correspondant à $id

    // Ici, on gérera la suppression de l'annonce en question

    return $this->render('PersoPlatformBundle:Advert:delete.html.twig');
  }
}