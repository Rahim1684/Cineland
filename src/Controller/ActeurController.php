<?php

namespace App\Controller;

use App\Entity\Acteur;
use App\Form\ActeurType;
use App\Repository\ActeurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/acteur")
 */
class ActeurController extends AbstractController
{
    /**
     * @Route("/", name="acteur_index", methods={"GET"})
     */
    public function index(ActeurRepository $acteurRepository): Response
    {
        return $this->render('acteur/index.html.twig', [
            'acteurs' => $acteurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="acteur_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $acteur = new Acteur();
        $form = $this->createForm(ActeurType::class, $acteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($acteur);
            $entityManager->flush();

            return $this->redirectToRoute('acteur_index');
        }

        return $this->render('acteur/new.html.twig', [
            'acteur' => $acteur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="acteur_show", methods={"GET"})
     */
    public function show(Acteur $acteur): Response
    {
        return $this->render('acteur/show.html.twig', [
            'acteur' => $acteur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="acteur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Acteur $acteur): Response
    {
        $form = $this->createForm(ActeurType::class, $acteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('acteur_index');
        }

        return $this->render('acteur/edit.html.twig', [
            'acteur' => $acteur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="acteur_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Acteur $acteur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$acteur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($acteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('acteur_index');
    }
    
    public function action4($act): Response{                                
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $req=$conn->prepare("SELECT * FROM `acteur` WHERE id=$act ");                
        $req->execute();                       
        $result=$req->fetchAll();
        
        $req=$conn->prepare("SELECT * FROM `film` WHERE id IN (SELECT film_id from `acteur_film` WHERE acteur_id=$act) ");                
        $req->execute();                       
        $result2=$req->fetchAll();
        return $this->render('acteur/action4.html.twig', ['Acteurs' => $result,'films'=>$result2]);
    }
    
    
    public function action15($film): Response{                                
        $conn = $this->getDoctrine()->getManager()->getConnection();
        
        $req=$conn->prepare("SELECT * FROM `acteur` WHERE id IN (SELECT acteur_id from `acteur_film` WHERE film_id=$film) ");                
        $req->execute();                       
        $result=$req->fetchAll();
        return $this->render('acteur/action15.html.twig', ['Acteurs' => $result]);
    }
     
    
    public function action16(): Response{                                
        $conn = $this->getDoctrine()->getManager()->getConnection();        
        $req=$conn->prepare("SELECT * FROM `acteur` WHERE id IN (SELECT acteur_id FROM `acteur_film` GROUP by acteur_id HAVING COUNT(DISTINCT film_id) >=3)");                
        $req->execute();                       
        $result=$req->fetchAll();
        return $this->render('acteur/action16.html.twig', ['Acteurs' => $result]);
    }
    
    public function action21(): Response{                                
        $conn = $this->getDoctrine()->getManager()->getConnection();        
        $req=$conn->prepare("CREATE VIEW reqTmp AS SELECT * from `acteur_film` INNER JOIN `film` ON `acteur_film`.`film_id`=`film`.`id` ");                
        $req->execute();
                
        $req=$conn->prepare("CREATE VIEW reqTmp2 AS SELECT acteur_id,film_id,titre,genre_id,nom from `reqTmp` INNER JOIN `genre` ON `reqTmp`.`genre_id`=`genre`.`id`");                
        $req->execute();
        
        $req=$conn->prepare("SELECT nom_prenom,nom from `reqTmp2` INNER JOIN `acteur` ON `reqTmp2`.`acteur_id`=`acteur`.`id` ORDER BY nom_prenom");                
        $req->execute();
        
        $result=$req->fetchAll();        
        $req=$conn->prepare("DROP VIEW `reqTmp`");                
        $req->execute();
        $req=$conn->prepare("DROP VIEW `reqTmp2`");                
        $req->execute();
        return $this->render('acteur/action21.html.twig', ['Acteurs' => $result]);
    }
            
}
