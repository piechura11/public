<?php

namespace LinkerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use LinkerBundle\Entity\Link;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use LinkerBundle\Form\LinkForm;
use LinkerBundle\Service\Helper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use LinkerBundle\Service\Paginator;
use LinkerBundle\Service\Search;
use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\Validator\Constraints\All;

class LinkerController extends Controller
{
    /**
     * @Route("/index")
     */
    public function indexAction(Request $request )
    {   
        $link = new Link();
        $link->setAddDate();
        $form = $this -> createForm(new LinkForm(), $link);
        $form->handleRequest($request);
        $helper = $this->get('linker_helper');
        if($form->isValid())
        {
            $flag = true;
            //sprawdzenie czy haslo zostalo ustawione przy trybie chronionym
            if($link->getModyficator()==2 && $link->getPassword()==null)
            {
                $form->get('password')->addError(new FormError('Podaj hasło'));
                $flag = false;
            }
            //sprawdzenie unikalności shortLinku
            if($link->getShortLink())
            {
                if(!$helper->unique($link->getShortLink()))
                {
                    $form->get('shortLink')->addError(new FormError('Rządana nazwa już istnieje w bazie danych, wybierz inną 
        				lub zostanie ona wygenerowana'));
                    $flag = false;
                }
            }
             //jeśli trafił na błędy renderuje formularz z wiadomościami                                  
             if($flag == false)
             {
                 return $this->render('LinkerBundle:Default:index.html.twig', array('form'=>$form->createView()));  
             }  
            //generowanie shortLinku
           if(!$link->getShortLink()) 
            {
                //sprawdzenie unikalności, jeśli nie jest, generuje
                $i = false;
                while($i == false)
                {
                    $randomString = $helper->randGenerator();
                    if($helper->unique($randomString))
                    {
                    $link->setShortLink($randomString);
                    $i = true;
                    }
                }
            }    
               $dm = $this->getDoctrine()->getManager();
               $dm ->persist($link);
               $dm ->flush();
            return $this->redirectToRoute('show', array('link'=>$link->getShortLink()));
        }
        return $this->render('LinkerBundle:Default:index.html.twig', array('form'=>$form->createView()));
    }

    /**
     * @Route("/show/{link}", name="show")
     */
    public function showAction($link){
        $linker=new Link();
        $dm=$this->getDoctrine()->getManager();
        $linker=$dm->getRepository('LinkerBundle:Link')->findOneByShortLink($link);
        return $this->render('LinkerBundle:Default:show.html.twig', array('link'=>$linker->getShortLink()));
    }

    /**
     * @Route("/long/{shortLink}")
     */
    public function showLongAction(Request $request, $shortLink){
        $link = new Link();
        $dm=$this->getDoctrine()->getManager();
        $link=$dm->getRepository('LinkerBundle:Link')->findOneByShortLink($shortLink);

        $shortLink=$link->getShortLink();
        //jeśli tryb chroniony podaj haslo
        if($link->getModyficator() == 2){
            $protected = new Link();
            $form = $this->createFormBuilder($protected)
            ->add('password', 'password', array('required'=>true))
            ->getForm();

            $form->handleRequest($request);
            if($form->isValid()){
                //sprawdza poprawność hasla
                if($link->getPassword() == $protected->getPassword()){

                    return $this->redirect($link->getLongLink());   
                }
                else{
                    $form->get('password')->addError(new FormError('Podaj hasło aby przejść do strony'));
                    return $this->render('LinkerBundle:Default:protected.html.twig', array(
                        'form'=>$form->createView(), 
                        'shortLink'=>$shortLink
                ));                    
                }
            }
            return $this->render('LinkerBundle:Default:protected.html.twig', array(
                'form'=>$form->createView(), 
                'shortLink'=>$shortLink
                ));
        }
    	return $this->redirect($link->getLongLink());
    }

    /**
     * @Route("/baza/{rpp}/{page}", defaults={"rpp": "10", "page": "1"})
     */
    public function bazaAction($rpp, $page)
    {
        //wyswietla wszytkie wpisy publiczne wraz z opisem i skrótem oraz czasem dodania
        $qb = $this->getDoctrine()->getManager()->getRepository('LinkerBundle:Link')
        ->createQueryBuilder('u');
        $qb->select( 'u.shortLink', 'u.addDate', 'u.longLink', 'u.description')
        ->where('u.modyficator = 0')
        ->orderBy('u.addDate', 'ASC');
        $dane = $qb->getQuery()->getResult();
        //liczba recordów w tabeli
        $totalCount=count($dane);

        $qb->setFirstResult($rpp*($page - 1))
        ->setMaxResults($rpp);
        $dane = $qb->getQuery()->getResult();
        //paginacja
        $paginator = new Paginator($totalCount, $page, $rpp);
        //totalPages
        $totalPages = $paginator->getTotalPages();
        //tablica stron 1,2,3....
        $pagesList = $paginator->getPagesList();
        $option = array(5, 10, 20);

        return $this->render('LinkerBundle:Default:baza.html.twig', array(
            'qb'=>$dane, 
            'totalCount'=>$totalCount,
            'page' => $page,
            'rpp' => $rpp,
            'totalPages' => $totalPages,
            'pagesList' => $pagesList,
            'option' =>$option
            ));
    }
    /**
     * @Route("/search")
     */
    public function searchAction(Request $request)
    {
        // zrobić formularze zapytań, kolejność jak w bazie, shortlink, longlink, opis, przedział daty 
        //sortowanie wg DEC/ASC 
        //formularz
        
        $form = $this->createFormBuilder()
        ->add('shortLink', 'text', array('required'=>false))
        ->add('longLink', 'text', array('required'=>false))
        ->add('addDate', 'date', array('widget'=> 'single_text',
        'format'=>'yyyy-MM-dd',
         'required'=>false))
        ->setMethod('GET')
        ->getForm();

        $dane=array();
        $form->handleRequest($request);
            
        $data = $request->query->all();
        $qb = $this->getDoctrine()->getManager()->getRepository('LinkerBundle:Link')
            ->createQueryBuilder('a');
            $qb->select('a')
            ->where('a.modyficator = 0')
            ->orderBy('a.addDate', 'ASC');
            $dane = $this->get('linker_search')->querySearch($qb, $form->getData());
            $dane = $qb->getQuery()->getResult();
        
        return $this->render('LinkerBundle:Default:search.html.twig', array(
            'form'=>$form->createView(),
            'qb'=>$dane));
    }	

}
