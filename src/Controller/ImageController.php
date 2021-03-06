<?php

namespace Controller;

use Entity\Image;
use Form\Type\ImageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ImageController extends Controller
{
    /**
     * @param Request $request
     * @param $name
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("kontrollpanel/bilde/last_opp/{name}", name="image_upload")
     * @Method({"GET", "POST"})
     */
    public function uploadImageAction(Request $request, $name)
    {
        $club = $this->get('club_manager')->getCurrentClub();
        $image = $this->getDoctrine()->getRepository('AppBundle:Image')->findByClubAndName($club, $name);

        if ($image === null) {
            $image = new Image();
            $image->setClub($club);
            $image->setName($name);
        }

        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.image_uploader')->uploadImage($image);

            return $this->redirectToRoute('home');
        }

        return $this->render('image/upload_image.html.twig', array(
            'form' => $form->createView(),
            'image' => $image,
        ));
    }
}
