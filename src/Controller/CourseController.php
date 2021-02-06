<?php

namespace Controller;

use Entity\Course;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class CourseController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/kurs", name="courses")
     * @Method("GET")
     */
    public function showAction()
    {
        $club = $this->get('club_manager')->getCurrentClub();
        $courses = $this->getDoctrine()->getRepository('AppBundle:CourseType')->findAllByClub($club);

        return $this->render('course/show.html.twig', array(
            'courses' => $courses,
        ));
    }

    /**
     * @param Course $course
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/kurs/{id}",
     *     options={"expose"=true},
     *     requirements={"id"="\d+"},
     *     name="course_info"
     * )
     * @Method("GET")
     */
    public function showCourseInfoAction(Course $course)
    {
        $this->get('course.manager')->throw404ifCourseOrCourseTypeIsDeleted($course);

        $this->get('club_manager')->denyIfNotCurrentClub($course);

        return $this->render('course/course_info.html.twig', array('course' => $course));
    }

    /**
     * @param $week
     *
     * @return JsonResponse
     *
     * @Route("/api/kurs/uke/{week}",
     *     name="api_get_course_classes_by_week",
     *     requirements={"id" = "\d+"}
     * )
     * @Method("GET")
     */
    public function getCourseClassesAction($week)
    {
        $currentSemester = $this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester();
        $club = $this->get('club_manager')->getCurrentClub();
        $courseClasses = $this->getDoctrine()->getRepository('AppBundle:CourseClass')->findByWeek($week, $currentSemester, $club);

        return new JsonResponse($courseClasses);
    }
}
