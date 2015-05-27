<?php

namespace ImageEditorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ImageEditorBundle\Entity\Image;
use Symfony\Component\HttpFoundation\Request;
use ImageEditorBundle\Service\Edition;
/**
* @Route("/img")
*/
class DefaultController extends Controller
{
    /**
     * @Route("/main", name="main")
     */
    public function mainAction(Request $request)
    {
	    //formularz wrzucenia zdjęcia, 
	    $image = new Image();
	    $image->setName()->setAddDate()->setPath();
	    $form = $this->createFormBuilder($image)
	    ->add('file', 'file', array('required'=>true, 'label'  => 'Dodaj obrazek:'))
	    ->getForm();
	    $form->handleRequest($request);

	 	 if ($this->getRequest()->getMethod() === 'POST') {
	        if ($form->isValid()) {
	            $em = $this->getDoctrine()->getEntityManager();
	            $image->upload();
	            $em->persist($image);
	            $em->flush();
	            $session=$this->getRequest()->getSession();
	            $session->set('obrazek', $image->getPath());
	            $this->redirect($this->generateUrl('base'));
	        }
	    }
	        return $this->render('ImageEditorBundle:Default:index.html.twig', array('form'=>$form->createView()));
    }
    /**
     * @Route("/menu",name="menu")
     */
    public function menuAction()
    {
    	//wybór dostępnych opcji edycji, pogrupowane w kategorie dla każdej grupy
    	//wraz z suwakami, pokrętłami, zakresami   
    	$session=$this->getRequest()->getSession();

    return $this->render('ImageEditorBundle:Default:menu.html.twig', array('image'=>$session->get('obrazek')));
    }

    /**
     * @Route("/menu/base", name="base")
     */
    public function baseAction(Request $request)
    {
    header('Content-Type: image/jpeg');
    $session=$this->getRequest()->getSession();
    $img=$session->get('obrazek');
    $prefix = 'http://localhost/web/uploads/obrazki/'.$img;
    $uploads = __DIR__."/../../../../web/uploads/obrazki/".$img;


    $request  = $this->getRequest();
	$brigness = $request->request->get('level');
	$contrast = $request->request->get('contrast');
	$negate = $request->request->get('negate');
	$gray = $request->request->get('gray');
	$edge = $request->request->get('edge');
    $editor = $this->get('edition');

    $colorize=array(0, 0, 0);

    $editor->setPrefix($prefix);
    $editor->setUploads($uploads);
    $editor->grayAction($prefix, $uploads, $gray);
    $editor->contrastAction($prefix, $uploads, $contrast);
    $editor->brignessAction($prefix, $uploads, $brigness);
    $editor->colorizeAction($prefix, $uploads, $colorize);
    $editor->edgeAction($prefix, $uploads, $edge);
    $editor->negateAction($prefix, $uploads, $negate);


    return $this->render('ImageEditorBundle:Default:baseAction.html.twig', array('image'=>$img));
    }
    /**
     * @Route("/menu/size", name="size")
     */
    public function sizeAction(Request $request)
    {
    //funkcje: przytnij, dostosuj wymiary, powiększ, zmiejsz
    header('Content-Type: image/jpeg');
    $session=$this->getRequest()->getSession();
    $img=$session->get('obrazek');

    return $this->render('ImageEditorBundle:Default:sizeAction.html.twig', array('image'=>$img));
    }


    
}
