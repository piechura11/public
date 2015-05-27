<?php

namespace DemotyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DemotyBundle\Entity\Mem;
use Symfony\Component\HttpFoundation\Request;

    /**
     * @Route("/user")
     */
class UserController extends Controller
{
   /**
     * @Route("/addMem", name="addMem")
     */
    public function addMemAction(Request $request)
    {
    $image = new Mem();
    $image->setAddDate()->setConfirm(false);
        $form = $this->createFormBuilder($image)
        ->add('title', 'text', array('required'=>true, 'label'  => 'Podaj tytuł:'))
        ->add('file', 'file', array('required'=>true, 'label'  => 'Dodaj obrazek:'))
        ->add('lowTex','text', array('required'=>true, 'label'  => 'Dodaj dolny tekst:'))
        ->add('upperTex', 'text', array('required'=>false, 'label'  => 'Dodaj górny tekst:'))
        ->getForm();

    $form->handleRequest($request);
    if ($this->getRequest()->getMethod() === 'POST') {
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $image->upload();
            $em->persist($image);
            $em->flush();
           $this->redirect($this->generateUrl('index'));
        }
    }

        return $this->render('DemotyBundle:User:addMem.html.twig', array('form'=>$form->createView()));
    }
    /**
     * @Route("/index", name="index")
     */
    public function indexAction()
    {
        $qb = $this->getDoctrine()->getManager()->getRepository('DemotyBundle:Mem')
        ->createQueryBuilder('u');
        $qb->select( 'u')
        ->where('u.confirm = true')
        ->orderBy('u.addDate', 'ASC');
        $dane = $qb->getQuery()->getResult();
        return $this->render('DemotyBundle:User:index.html.twig', array('dane'=>$dane));
    }
    
    /**
     * @Route("/mail", name="mail")
     */
    public function mailAction(Request $request)
    {
        $form = $this->createFormBuilder()
        ->add('mail', 'email', array('required'=>true))
        ->add('temat', 'text', array('required'=>true))
        ->add('tresc', 'textarea', array('required'=>true))
        ->getForm();
        $form->handleRequest($request);
        if($form->isValid()){
            $mailer = $this->get('mailer');
            $message = $mailer->createMessage()
            ->setSubject($form["temat"]->getData())
            ->setFrom($form["mail"]->getData())
            ->setTo('piechura11@gmail.com')
            ->setBody($form["tresc"]->getData());
            $mailer->send($message);
            return $this->redirect($this->generateUrl('mail'));

        }
        return $this->render('DemotyBundle:User:mail.html.twig', array('form'=>$form->createView()));
    }
    /**
     * @Route("/showlast/{number}", name="showlast")
     */
    public function showalastAction($number)
    {
        $qb = $this->getDoctrine()->getManager()->getRepository('DemotyBundle:Mem')
        ->createQueryBuilder('u');
        $qb->select( 'u')
        ->where('u.confirm = 1')
        ->orderBy('u.addDate', 'ASC')
        ->setMaxResults($number);
        $dane = $qb->getQuery()->getResult();
        return $this->render('DemotyBundle:User:index.html.twig', array('dane'=>$dane));
    }
}
