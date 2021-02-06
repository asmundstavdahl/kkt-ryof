<?php

namespace Controller;

use Entity\Message;
use Form\Type\MessageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MessageController.
 *
 * @Route("/kontrollpanel")
 */
class MessageController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/melding", name="cp_message")
     * @Method({"GET", "POST"})
     */
    public function showAction(Request $request)
    {
        $club = $this->get('club_manager')->getCurrentClub();
        $messages = $this->getDoctrine()->getRepository('AppBundle:Message')->findLatestMessages($club);

        $message = new Message();
        $message->setClub($club);

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($message);
            $manager->flush();

            return $this->redirectToRoute('cp_message');
        }

        return $this->render('control_panel/show_message.html.twig', array(
            'messages' => $messages,
            'form' => $form->createView(),
        ));
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/melding/slett/{id}",
     *     name="cp_delete_message",
     *     requirements={"id" = "\d+"}
     * )
     *
     * @Method({"POST"})
     */
    public function deleteMessageAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $message = $manager->getRepository('AppBundle:Message')->find($id);
        $this->get('club_manager')->denyIfNotCurrentClub($message);

        if (!is_null($message)) {
            $manager->remove($message);
            $manager->flush();
        }

        return $this->redirectToRoute('cp_message');
    }
}
