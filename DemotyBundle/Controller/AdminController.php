<?php

namespace DemotyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DemotyBundle\Entity\Mem;
use Symfony\Component\HttpFoundation\Request;

    /**
     * @Route("/administrator")
     */
class AdminController extends Controller
{
    /**
     * @Route("/nowy", name="nowy")
     */
    public function adddMemAction(Request $request)
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
           $this->redirect($this->generateUrl('nowy'));
        }
    }

        return $this->render('DemotyBundle:Admin:addMem.html.twig', array('form'=>$form->createView()));
    }
    /**
     * @Route("/indexall", name="indexall")
     */
    public function indexallAction()
    {
    	$qb = $this->getDoctrine()->getManager()->getRepository('DemotyBundle:Mem')
        ->createQueryBuilder('u');
        $qb->select( 'u')
        ->where('u.confirm = true')
        ->orderBy('u.addDate', 'ASC');
        $dane = $qb->getQuery()->getResult();
        return $this->render('DemotyBundle:Admin:index.html.twig', array('dane'=>$dane));
    }
    /**
     * @Route("/toconfirm", name="toconfirm")
     */
    public function toConfirmAction()
    {
    	$qb = $this->getDoctrine()->getManager()->getRepository('DemotyBundle:Mem')
        ->createQueryBuilder('u');
        $qb->select( 'u')
        ->where('u.confirm = false')
        ->orderBy('u.addDate', 'ASC');
        $dane = $qb->getQuery()->getResult();
        return $this->render('DemotyBundle:Admin:confirm.html.twig', array('dane'=>$dane));
    }
    /**
     * @Route("/confirm/{id}", name="confirm")
     */
    public function confiAction(Request $request, $id)
    {
    	$mem = new Mem();
    	$mem->setUpdateDate();
        $dm=$this->getDoctrine()->getManager();
        $mem=$dm->getRepository('DemotyBundle:Mem')->findOneById($id);
        $mem->setConfirm(true);
        $dm ->persist($mem);
        $dm ->flush();

        return $this->redirectToRoute('toconfirm');
    }
    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function removeAction(Request $request, $id)
    {
    	$mem = new Mem();
        $dm = $this->getDoctrine()->getManager();
        $mem=$dm->getRepository('DemotyBundle:Mem')->find($id);

        $mem->removeUpload();
        $dm->remove($mem);
        $dm->flush();

        return $this->redirectToRoute('toconfirm');
    }
    /**
     * @Route("/email", name="email")
     */
    public function emailAction(Request $request)
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
            return $this->redirect($this->generateUrl('email'));

        }
        return $this->render('DemotyBundle:Admin:mail.html.twig', array('form'=>$form->createView()));
    }
    /**
     * @Route("/editMem/{id}", name="edi")
     */
    public function editAction(Request $request, $id)
    {
    	$dm = $this->getDoctrine()->getManager();
        $mem=$dm->getRepository('DemotyBundle:Mem')->find($id);
        $form = $this->createFormBuilder($mem)
        ->add('title', 'text', array('required'=>true, 'label'  => 'Podaj tytuł:'))
        ->add('lowTex','text', array('required'=>true, 'label'  => 'Dodaj dolny tekst:'))
        ->add('upperTex', 'text', array('required'=>false, 'label'  => 'Dodaj górny tekst:'))
        ->getForm();

        $form->handleRequest($request);
        if($form->isValid()){

            $dm=$this->getDoctrine()->getManager();
            //$dm->persist($mem);
            $dm->flush();
            
            return $this->redirectToRoute('index');
        }

        return $this->render('DemotyBundle:Admin:edit.html.twig', array('form'=>$form->createView()));
    }
    /**
     * @Route("/showlasts/{number}", name="showlasts")
     */
    public function showlastsAction($number)
    {
        $qb = $this->getDoctrine()->getManager()->getRepository('DemotyBundle:Mem')
        ->createQueryBuilder('u');
        $qb->select( 'u')
        ->where('u.confirm = 1')
        ->orderBy('u.addDate', 'ASC')
        ->setMaxResults($number);
        $dane = $qb->getQuery()->getResult();
        return $this->render('DemotyBundle:Admin:index.html.twig', array('dane'=>$dane));
    }

}
