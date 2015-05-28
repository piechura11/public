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
    $prefix = 'http://localhost/web/uploads/obrazki/'.$img;
    $request  = $this->getRequest();
	$wsp = $request->request->all();
	echo 'X1: '.$wsp['x1']; 
		echo 'X1: '.$wsp['y1']; 
			echo 'X1: '.$wsp['x2']; 

	
/*
    $source = imagecreatefromjpeg($prefix);
    list($width, $height) = getimagesize($prefix);

    ///zczytanie wymiarów fotki
    $newWidth = 300;
	$newHeight = 225;
	$mini = imagecreatetruecolor($newWidth, $newHeight);

	imagecopyresized($mini,    // uchwyt obrazka wynikowego
	$source,                      // uchwyt obrazka źródłowego 
	20,                         // współrzędna x punktu od którego zaczynamy nanoszenie
	60,                         // współrzędna y punktu od którego zaczynamy nanoszenie
	0,                         // współrzędna x punktu od którego zaczynamy kopiowanie
	0,                         // współrzędna y punktu od którego zaczynamy kopiowanie
	$newWidth,                    // szerokość skopiowanego obrazka na obrazku wynikowym
	$newHeight,                   // wysokość skopiowanego obrazka na obrazku wynikowym
	$width,             // szerokość obszaru kopiowanego z obrazka źródłowego
	$height);            // wysokość obszaru kopiowanego z obrazka źródłowego

	imagejpeg($mini, null, 100);
*/
    return $this->render('ImageEditorBundle:Default:menuSize.html.twig', array('image'=>$img));
    }


    
}
