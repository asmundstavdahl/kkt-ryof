<?php

namespace Controller;

use Entity\CourseType;
use Form\Type\CourseTypeType;
use Entity\Image;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminCourseTypeController.
 *
 * @Route("/kontrollpanel")
 */
class AdminCourseTypeController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/kurs/type", name="cp_course_type")
     * @Method("GET")
     */
    public function showAction()
    {
        $club = $this->get('club_manager')->getCurrentClub();
        $courses = $this->getDoctrine()->getRepository('AppBundle:CourseType')->findAllByClub($club);

        return $this->render('course/control_panel/show_course_type.html.twig', array(
            'courses' => $courses,
        ));
    }

    /**
     * @param Request         $request
     * @param CourseType|null $courseType
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/kurs/type/ny", name="cp_create_course_type")
     * @Route("/kurs/type/{id}",
     *     requirements={"id" = "\d+"},
     *     name="cp_edit_course_type"
     * )
     * @Method({"GET", "POST"})
     */
    public function editCourseTypeAction(Request $request, CourseType $courseType = null)
    {
        $this->get('course.manager')->throw404ifCourseOrCourseTypeIsDeleted($courseType);

        // Check if this is a create or edit
        $isCreate = $courseType === null;
        if ($isCreate) {
            $club = $this->get('club_manager')->getCurrentClub();

            // Initialize a new CourseType with a new image
            $image = new Image();
            $image->setClub($club);

            $courseType = new CourseType();
            $courseType->setClub($club);
            $courseType->setImage($image);
        }

        $this->get('club_manager')->denyIfNotCurrentClub($courseType);

        $form = $this->createForm(CourseTypeType::class, $courseType);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Upload image
            $image = $courseType->getImage();
            $image->setName($courseType->getName());
            if ($image->getFile() !== null) {
                $this->get('app.image_uploader')->uploadImage($image);
            } elseif ($isCreate) {
                $this->get('app.image_uploader')->setDefaultCourseTypeImage($image);
            }

            // Save CourseType
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($image);
            $manager->persist($courseType);
            $manager->flush();

            return $this->redirectToRoute('cp_course_type');
        }

        return $this->render('course/control_panel/show_edit_course_type.html.twig', array(
            'courseType' => $courseType,
            'form' => $form->createView(),
        ));
    }

    /**
     * @param CourseType $courseType
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/kurs/type/slett/{id}",
     *     requirements={"id" = "\d+"},
     *     name="cp_delete_course_type"
     * )
     *
     * @Method({"POST"})
     */
    public function deleteCourseTypeAction(CourseType $courseType)
    {
        $this->get('club_manager')->denyIfNotCurrentClub($courseType);

        // Soft delete CourseType
        $courseType->delete();
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($courseType);
        $manager->flush();

        return $this->redirectToRoute('cp_course_type');
    }
}
