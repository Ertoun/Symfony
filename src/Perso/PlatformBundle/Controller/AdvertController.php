<?php

// src/Perso/PlatformBundle/Controller/AdvertController.php

namespace Perso\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdvertController extends Controller
{
    public function indexAction()
    {
        $content = $this->get('templating')->render('PersoPlatformBundle:Advert:index.html.twig', $tableau = array('nom' => 'ertoun'));

        return new Response($content);
    }

    public function byeAction()
    {
        $ciao = $this->get('templating')->render('PersoPlatformBundle:Advert:bye.html.twig', $tableau = array('nom' => 'ertoun'));

        return new Response($ciao);
    }
}