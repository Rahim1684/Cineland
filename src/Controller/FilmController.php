<?php

namespace App\Controller;

use App\Entity\Film;
use App\Form\FilmType;
use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/film")
 */
class FilmController extends AbstractController
{
    /**
     * @Route("/", name="film_index", methods={"GET"})
     */
    public function index(FilmRepository $filmRepository): Response
    {
        return $this->render('film/index.html.twig', [
            'films' => $filmRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="film_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response{
        $film = new Film();
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($film);
            $entityManager->flush();

            return $this->redirectToRoute('film_index');
        }

        return $this->render('film/new.html.twig', [
            'film' => $film,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="film_show", methods={"GET"})
     */
    public function show(Film $film): Response
    {
        return $this->render('film/show.html.twig', [
            'film' => $film,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="film_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Film $film): Response
    {
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('film_index');
        }

        return $this->render('film/edit.html.twig', [
            'film' => $film,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="film_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Film $film): Response
    {
        if ($this->isCsrfTokenValid('delete'.$film->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($film);
            $entityManager->flush();
        }

        return $this->redirectToRoute('film_index');
    }
            
    
    public function action9($film): Response{                                
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $req=$conn->prepare("SELECT * FROM `film` WHERE id=$film");                
        $req->execute();                       
        $result=$req->fetchAll();
                
        $req=$conn->prepare("SELECT * FROM `acteur` WHERE id IN (SELECT acteur_id from `acteur_film` WHERE film_id=$film) ");                
        $req->execute();                       
        $result2=$req->fetchAll();
        
        $req=$conn->prepare("SELECT nom FROM `genre` WHERE id IN (SELECT genre_id FROM `film` WHERE id=$film)");                
        $req->execute();                       
        $result3=$req->fetchAll();
        
        return $this->render('film/action9.html.twig', ['films' => $result,'genre'=>$result3,'Acteurs'=>$result2]);
    }
        
    public function action13($dateMin,$dateMax): Response{        
        $dateMin= "'$dateMin'";
        $dateMax= "'$dateMax'";
        
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $req=$conn->prepare("SELECT titre,duree,date_sortie,note,age_minimal FROM `film` WHERE date_sortie BETWEEN $dateMin AND $dateMax");                
        $req->execute();        
        $result=$req->fetchAll();        
        return $this->render('film/action13.html.twig', ['films' => $result,'dateMin'=>$dateMin,'dateMax'=>$dateMax]);
    }
    
    public function action14($dateMax): Response{                
        $dateMax= "'$dateMax'";
        
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $req=$conn->prepare("SELECT titre,duree,date_sortie,note,age_minimal FROM `film` WHERE date_sortie < $dateMax");                
        $req->execute();        
        $result=$req->fetchAll();        
        return $this->render('film/action14.html.twig', ['films' => $result,'dateMax'=>$dateMax]);
    }
    
    public function action17($act1,$act2): Response{                
                
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $req=$conn->prepare("CREATE View reqTmp  As SELECT film_id FROM `acteur_film` WHERE acteur_id=$act1 or acteur_id=$act2");                
        $req->execute();
        
        $req=$conn->prepare("SELECT * from `film` WHERE id IN (SELECT film_id As nb FROM `reqTmp` GROUP by film_id HAVING COUNT(film_id)=2)");                
        $req->execute();
        
        $result=$req->fetchAll();
        $req=$conn->prepare("DROP VIEW `reqTmp`");                
        $req->execute();
        return $this->render('film/action17.html.twig', ['films' => $result]);
    }
    
    public function action19($act1): Response{                                        
        $conn = $this->getDoctrine()->getManager()->getConnection();        
        $req=$conn->prepare("SELECT titre,duree FROM `film` WHERE id IN (SELECT film_id FROM `acteur_film` WHERE acteur_id=$act1)");                
        $req->execute();
        
        $result=$req->fetchAll();        
        return $this->render('film/action19.html.twig', ['films' => $result]);
    }
    
    public function action20(): Response{                
        $conn = $this->getDoctrine()->getManager()->getConnection();        
        $req=$conn->prepare("CREATE View reqTmp  As SELECT * from `acteur` INNER JOIN `acteur_film` ON `acteur`.`id`=`acteur_film`.`acteur_id`");                
        $req->execute();
                
        $req=$conn->prepare("SELECT nom_prenom,titre,duree,date_sortie,note,age_minimal from `reqTmp` INNER JOIN `film` ON `reqTmp`.`film_id`=`film`.`id` ORDER by nom_prenom,date_sortie ASC");                
        $req->execute();
        
        $result=$req->fetchAll();        
        $req=$conn->prepare("DROP VIEW `reqTmp`");                
        $req->execute();
        return $this->render('film/action20.html.twig', ['films' => $result]);
    }
    
    public function action22($genre,$nom): Response{                
        $conn = $this->getDoctrine()->getManager()->getConnection();        
        $req=$conn->prepare("CREATE VIEW reqTmp AS SELECT duree FROM `film`WHERE `film`.`genre_id`=$genre");                
        $req->execute();
                
        $req=$conn->prepare("SELECT AVG(duree) FROM reqTmp");                
        $req->execute();
        
        $result=$req->fetchAll();        
        $req=$conn->prepare("DROP VIEW `reqTmp`");                
        $req->execute();
        return $this->render('film/action22.html.twig', ['films' => $result,'nom'=>$nom]);
    }
    
    public function action23($film,$noteP,$note,$code): Response{                                
        $conn = $this->getDoctrine()->getManager()->getConnection();
        if($code==1){
            $note=$noteP+$note;
        }else{
            $note=$noteP-$note;
        }
        $req=$conn->prepare("UPDATE `film` SET `note` = $note WHERE `film`.`id` = $film");                
        $req->execute();                       
        return $this->redirectToroute('film_show', ['id' => $film]);
    }
    
    public function action25($titre): Response{                                
        $conn = $this->getDoctrine()->getManager()->getConnection();        
        $req=$conn->prepare("SELECT * FROM film WHERE titre LIKE '$titre%'");                
        $req->execute();   
        $result=$req->fetchAll();
        return $this->render('film/action25.html.twig', ['films' => $result]);
    }
                
       
            
}
