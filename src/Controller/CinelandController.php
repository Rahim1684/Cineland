<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Acteur;
use App\Entity\Genre;
use App\Entity\Film;
use App\Repository\ActeurRepository;
use App\Repository\FilmRepository;
use App\Repository\GenreRepository;

class CinelandController extends AbstractController
{
    /**
     * @Route("/cineland", name="cineland")
     */
    public function index(): Response{
        return $this->render('cineland/index.html.twig', [
            'controller_name' => 'CinelandController',
        ]);
    }
    
    public function menu(){
        return $this->render('cineland/menu.html.twig');
    }
    
    public function plus(){
        return $this->render('cineland/plus.html.twig');
    }
    
    public function action13F(Request $request){
        $form = $this->createFormBuilder()
					 ->add('DateMinimum',DateType::class,['widget' => 'choice', 'years' => range(1800,2021)])
					 ->add('DateMaximum',DateType::class,['widget' => 'choice', 'years' => range(1800,2021)])
					 ->add('Chercher', SubmitType::class)
					 ->getForm();
		;
		$form->handleRequest($request);
		if($form->isSubmitted())
		{
			$min = $form['DateMinimum']->getData()->format('Y-m-d');
			$max = $form['DateMaximum']->getData()->format('Y-m-d');
			
			return $this->redirectToRoute('cineland_film_action13',['dateMin'=> $min, 'dateMax'=>$max]); 
		}
		return $this->render('cineland/_form.html.twig',array('form' => $form->createView()));        
    }
    
    public function action14F(Request $request){
        $form = $this->createFormBuilder()					 
					 ->add('DateMaximum',DateType::class,['widget' => 'choice', 'years' => range(1800,2021)])
					 ->add('Chercher', SubmitType::class)
					 ->getForm();
		;
		$form->handleRequest($request);
		if($form->isSubmitted())
		{			
			$max = $form['DateMaximum']->getData()->format('Y-m-d');
			
			return $this->redirectToRoute('cineland_film_action14',['dateMax'=>$max]); 
		}
		return $this->render('cineland/_form.html.twig',array('form' => $form->createView()));        
    }
    
    public function action17F(Request $request){        
		$form = $this->createFormBuilder()
				->add('Acteur1',EntityType::class, array('class' => Acteur::class,'query_builder' => function (ActeurRepository $repo) { return $repo->createQueryBuilder('a');}))
				->add('Acteur2',EntityType::class, array('class' => Acteur::class,'query_builder' => function (ActeurRepository $repo) { return $repo->createQueryBuilder('a');}))
				->add('Chercher', SubmitType::class)
				->getForm();
		;
		$form->handleRequest($request);
		if( $form->isSubmitted() )
		{
			$acteur1 = $form['Acteur1']->getData();
			$acteur2 = $form['Acteur2']->getData();
                        $acteur1=$acteur1->getId();
                        $acteur2=$acteur2->getId();
			
			return $this->redirectToRoute('cineland_film_action17', ['act1' => $acteur1, 'act2' => $acteur2 ]);
		}		
		return $this->render('cineland/_form.html.twig',array('form' => $form->createView()));        
    }
    
    public function action19F(Request $request){        
		$form = $this->createFormBuilder()
				->add('Acteur1',EntityType::class, array('class' => Acteur::class,'query_builder' => function (ActeurRepository $repo) { return $repo->createQueryBuilder('a');}))				
				->add('Chercher', SubmitType::class)
				->getForm();
		;
		$form->handleRequest($request);
		if( $form->isSubmitted() )
		{
			$acteur1 = $form['Acteur1']->getData();			
                        $acteur1=$acteur1->getId();                        
			
			return $this->redirectToRoute('cineland_film_action19', ['act1' => $acteur1 ]);
		}		
		return $this->render('cineland/_form.html.twig',array('form' => $form->createView()));        
    }
    
    public function action22F(Request $request){        
		$form = $this->createFormBuilder()
				->add('Genre',EntityType::class, array('class' => Genre::class,'query_builder' => function (GenreRepository $repo) { return $repo->createQueryBuilder('g');}))				
				->add('Chercher', SubmitType::class)
				->getForm();
		;
		$form->handleRequest($request);
		if( $form->isSubmitted() )
		{
			$genre = $form['Genre']->getData();
                        $nomG=$genre->getNom();
                        $genre=$genre->getId();                        
			
			return $this->redirectToRoute('cineland_film_action22', ['genre' => $genre, 'nom' => $nomG ]);
		}		
		return $this->render('cineland/_form.html.twig',array('form' => $form->createView()));        
    }
    
    public function action23F(Request $request){        
		$form = $this->createFormBuilder()
				->add('Film',EntityType::class, array('class' => Film::class,'query_builder' => function (FilmRepository $repo) { return $repo->createQueryBuilder('f');}))				
                                ->add('Valeur',IntegerType::class)
				->add('Augmenter', SubmitType::class)
                                ->add('Dimunuer', SubmitType::class)
				->getForm();
		;
		$form->handleRequest($request);
		if( $form->isSubmitted() ){
                    $film = $form['Film']->getData();			
                    $noteP=$film->getNote();
                    $film=$film->getId();
                   
                    $note=$form['Valeur']->getData();			
                    if($form->get('Augmenter')->isClicked()){
                        return $this->redirectToRoute('cineland_film_action23', ['film' => $film,'noteP'=>$noteP,'note'=>$note,'code'=>1 ]);
                    }else{
                        return $this->redirectToRoute('cineland_film_action23', ['film' => $film,'noteP'=>$noteP,'note'=>$note,'code'=>0 ]);
                    }                    						
			
		}		
		return $this->render('cineland/_form.html.twig',array('form' => $form->createView()));        
    }
    
    public function action25F(Request $request){        
		$form = $this->createFormBuilder()
				->add('Titre',TextType::class)				
				->add('Chercher', SubmitType::class)
				->getForm();
		;
		$form->handleRequest($request);
		if( $form->isSubmitted() )
		{
			$titre = $form['Titre']->getData();                                                
			
			return $this->redirectToRoute('cineland_film_action25', ['titre' => $titre ]);
		}		
		return $this->render('cineland/_form.html.twig',array('form' => $form->createView()));        
    }
    
    public function action26F(Request $request){        
		$form = $this->createFormBuilder()
				->add('Acteur',EntityType::class, array('class' => Acteur::class,'query_builder' => function (ActeurRepository $repo) { return $repo->createQueryBuilder('a');}))				
                                ->add('Valeur',IntegerType::class)
				->add('Augmenter', SubmitType::class)
				->getForm();
		;
		$form->handleRequest($request);
		if( $form->isSubmitted() )
		{                    				
		    $val=$form['Valeur']->getData();				
                    $acteur1 = $form['Acteur']->getData();			
                    $acteur1=$acteur1->getId();                        
			
			return $this->redirectToRoute('cineland_film_action26', ['act' => $acteur1,'val'=>$val ]);
		}		
		return $this->render('cineland/_form.html.twig',array('form' => $form->createView()));        
    }
    
    public function action4F(Request $request){        
		$form = $this->createFormBuilder()
				->add('Acteur',EntityType::class, array('class' => Acteur::class,'query_builder' => function (ActeurRepository $repo) { return $repo->createQueryBuilder('a');}))				
				->add('Chercher', SubmitType::class)
				->getForm();
		;
		$form->handleRequest($request);
		if( $form->isSubmitted() )
		{
			$acteur1 = $form['Acteur']->getData();			
                        $acteur1=$acteur1->getId();                        
			
			return $this->redirectToRoute('cineland_acteur_action4', ['act' => $acteur1 ]);
		}		
		return $this->render('cineland/_form.html.twig',array('form' => $form->createView()));        
    }
    
       public function action9F(Request $request){        
		$form = $this->createFormBuilder()
				->add('Film',EntityType::class, array('class' => Film::class,'query_builder' => function (FilmRepository $repo) { return $repo->createQueryBuilder('f');}))				                                
				->add('Chercher', SubmitType::class)                                
				->getForm();
		;
		$form->handleRequest($request);
		if( $form->isSubmitted() ){
                    $film = $form['Film']->getData();			                    
                    $film=$film->getId();                   
                        return $this->redirectToRoute('cineland_film_action9', ['film' => $film ]);                                       									
		}		
		return $this->render('cineland/_form.html.twig',array('form' => $form->createView()));        
    }
  
    public function action15F(Request $request){        
		$form = $this->createFormBuilder()
				->add('Film',EntityType::class, array('class' => Film::class,'query_builder' => function (FilmRepository $repo) { return $repo->createQueryBuilder('f');}))				                                
				->add('Chercher', SubmitType::class)                                
				->getForm();
		;
		$form->handleRequest($request);
		if( $form->isSubmitted() ){
                    $film = $form['Film']->getData();			                    
                    $film=$film->getId();                   
                        return $this->redirectToRoute('cineland_acteur_action15', ['film' => $film ]);                                       									
		}		
		return $this->render('cineland/_form.html.twig',array('form' => $form->createView()));        
    }
    
    public function action18F(Request $request){        
		$form = $this->createFormBuilder()
				->add('Acteur1',EntityType::class, array('class' => Acteur::class,'query_builder' => function (ActeurRepository $repo) { return $repo->createQueryBuilder('a');}))				
				->add('Chercher', SubmitType::class)
				->getForm();
		;
		$form->handleRequest($request);
		if( $form->isSubmitted() )
		{
			$acteur1 = $form['Acteur1']->getData();			
                        $acteur1=$acteur1->getId();                        
			
			return $this->redirectToRoute('cineland_genre_action18', ['act' => $acteur1 ]);
		}		
		return $this->render('cineland/_form.html.twig',array('form' => $form->createView()));        
    }
    
    public function Ageminplus(Request $request)
    {
        $form = $this->createFormBuilder()
                ->add('Acteur',EntityType::class, array('class' => Acteur::class,'query_builder' => function (ActeurRepository $repo) { return $repo->createQueryBuilder('a');}))
                ->add('Valeur',IntegerType::class,array('data'=>1))
                ->add('augmenter', SubmitType::class)
                ->getForm();
        ;
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $val = $form['Valeur']->getData();
            $acteur = $form['Acteur']->getData();
            if($val == NULL)
            {
                $val = 1;
            }
            foreach( $acteur->getFilmJouees() as $f )
            {
                $f->setAgeMinimal($f->getAgeMinimal() + $val);
            }
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('cineland_acteur_action4',['act'=>$acteur->getId()]);
        }
        return $this->render('cineland/_form.html.twig',array('form' => $form->createView()));         
    }
    
    
}
