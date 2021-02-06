<?php

namespace Controller;

class AboutController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/om", name="about")
     * @Method("GET")
     */
    #[Route("/om", "about")]
    public function showAction()
    {
        return $this->render('about/show.html.twig');
    }
}
