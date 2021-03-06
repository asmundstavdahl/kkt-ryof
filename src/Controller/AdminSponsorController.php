<?php

namespace Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \Entity\Sponsor;
use \Form\Type\SponsorType;

/**
 * Class AdminSponsorController.
 *
 * @Route("/kontrollpanel")
 */
class AdminSponsorController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/sponsors", name="cp_sponsors")
     * @Method({"GET", "POST"})
     */
    public function showSponsorsAction(Request $request)
    {
        $currentClub = $this->get("club_manager")->getCurrentClub();
        $sponsors = $this->getDoctrine()->getRepository(Sponsor::class)->findAllByClub($currentClub);
        return $this->render("sponsor/manage.html.twig", [
            "sponsors" => $sponsors
        ]);
    }

    /**
     * @param Request $request
     * @param Sponsor $sponsor
     *
     * @return Response
     *
     * @Route("/sponsors/{sponsor}/edit", name="cp_edit_sponsor")
     * @Method({"GET", "POST"})
     */
    public function editSponsorAction(Request $request, Sponsor $sponsor)
    {
        #$currentClub = $this->get("club_manager")->getCurrentClub();
        #$sponsors = $this->getDoctrine()->getRepository(Sponsor::class)->findAllByClub($currentClub);

        $form = $this->createForm(SponsorType::class, $sponsor);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($sponsor);
            $manager->flush();

            return $this->redirectToRoute('cp_sponsors');
        }

        return $this->render("sponsor/edit.html.twig", [
            "sponsor" => $sponsor,
            "form" => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/sponsors/new", name="cp_new_sponsor")
     * @Method({"GET", "POST"})
     */
    public function newSponsorAction(Request $request)
    {
        $currentClub = $this->get("club_manager")->getCurrentClub();

        $sponsor = new Sponsor();
        $sponsor->setClub($currentClub);

        $form = $this->createForm(SponsorType::class, $sponsor);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($sponsor);
            $manager->flush();

            return $this->redirectToRoute('cp_sponsors');
        }

        return $this->render("sponsor/edit.html.twig", [
            "sponsor" => $sponsor,
            "form" => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Sponsor $sponsor
     *
     * @return Response
     *
     * @Route("/sponsors/{sponsor}/delete", name="cp_delete_sponsor")
     * @Method({"GET"})
     */
    public function deleteSponsorAction(Request $request, Sponsor $sponsor)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($sponsor);
        $em->flush();

        return $this->redirectToRoute('cp_sponsors');
    }

    /**
     * @param Request $request
     * @param Sponsor $sponsor
     *
     * @return Response
     *
     * @Route("/sponsors/{sponsor}/image", name="cp_edit_sponsor_image")
     * @Method({"GET"})
     */
    public function editSponsorImageAction(Request $request, Sponsor $sponsor)
    {
        $currentClub = $this->get("club_manager")->getCurrentClub();

        $image = $this->getDoctrine()
            ->getRepository(\Entity\Image::class)
            ->findByClubAndName($currentClub, $sponsor->getImageName());

        return $this->render("image/editable_image_template.html.twig", [
            "image" => $image,
        ]);
    }
}
