<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Acteur;
use App\Entity\Film;
use App\Entity\Genre;

class InitController extends AbstractController
{
    /**
     * @Route("/cineland/init", name="init")
     */
    public function index(): Response{
        
        $g1=new Genre();
        $g2=new Genre();
        $g3=new Genre();
        $g4=new Genre();
        $g5=new Genre();
        
        $acteur_repository=$this->getDoctrine()->getRepository(Acteur::class);
        $genre_repository=$this->getDoctrine()->getRepository(Genre::class);
        $entityManager=$this->getDoctrine()->getManager();
        $g1->setNom("animation");
        $entityManager->persist($g1);
        $g2->setNom("policier");
        $entityManager->persist($g2);
        $g3->setNom("drame");
        $entityManager->persist($g3);
        $g4->setNom("comédie");
        $entityManager->persist($g4);
        $g5->setNom("X");
        $entityManager->persist($g5);
        $entityManager->flush();
        
        $a1=new Acteur();
        $a2=new Acteur();
        $a3=new Acteur();
        $a4=new Acteur();
        $a5=new Acteur();
        $a6=new Acteur();
        
        $a1->setNomPrenom("Galabru Michel");
        $a1->setDateNaissance(new \DateTime("27-10-1922"));
        $a1->setNationalite("france");
        $entityManager->persist($a1);
        $entityManager->flush();
        
        $a2->setNomPrenom("Deneuve Catherine");
        $a2->setDateNaissance(new \DateTime("22-10-1943"));
        $a2->setNationalite("france");
        $entityManager->persist($a2);
        $entityManager->flush();
        
        $a3->setNomPrenom("Depardieu Gérard");
        $a3->setDateNaissance(new \DateTime("27-12-1948"));
        $a3->setNationalite("Russie");
        $entityManager->persist($a3);
        $entityManager->flush();
        
        $a4->setNomPrenom("Lanvin Gérard");
        $a4->setDateNaissance(new \DateTime("21-06-1950"));
        $a4->setNationalite("france");
        $entityManager->persist($a4);
        $entityManager->flush();
        
        $a5->setNomPrenom("Désiré Dupond");
        $a5->setDateNaissance(new \DateTime("23-12-2021"));
        $a5->setNationalite("groland");
        $entityManager->persist($a5);
        $entityManager->flush();
        
        $a6->setNomPrenom("Kafanga akim");
        $a6->setDateNaissance(new \DateTime("30-06-1960"));
        $a6->setNationalite("DRC");
        $entityManager->persist($a6);
        $entityManager->flush();
        
        $f1=new Film();
        $f2=new Film();
        $f3=new Film();
        $f4=new Film();
        $f5=new Film();
                
        $f1->setTitre("Astérix aux jeux olympiques");
        $f1->setDuree(117);
        $f1->setDateSortie(new \DateTime("20-01-2008"));
        $f1->setNote(8);
        $f1->setAgeMinimal(0);
        $f1->setGenre($g1);
        $entityManager->persist($f1);
        $entityManager->flush();
        
        $f2->setTitre("Le Dernier Métro");
        $f2->setDuree(131);
        $f2->setDateSortie(new \DateTime("17-09-1980"));
        $f2->setNote(15);
        $f2->setAgeMinimal(12);
        $f2->setGenre($g1);
        $f2->addActeurJoue($a2);
        $f2->addActeurJoue($a3);
        $entityManager->persist($f2);
        
        
        $f3->setTitre("le choix des armes");
        $f3->setDuree(135);
        $f3->setDateSortie(new \DateTime("19-10-1981"));
        $f3->setNote(13);
        $f3->setAgeMinimal(18);
        $f3->setGenre($g2);
        $f3->addActeurJoue($a2);
        $f3->addActeurJoue($a3);
        $f3->addActeurJoue($a4);
        $entityManager->persist($f3);
        
        
        $f4->setTitre("Les Parapluies de Cherbourg");
        $f4->setDuree(91);
        $f4->setDateSortie(new \DateTime("19-02-1964"));
        $f4->setNote(9);
        $f4->setAgeMinimal(0);
        $f4->setGenre($g3);
        $f4->addActeurJoue($a2);        
        $entityManager->persist($f4);
        
        $f5->setTitre("La Guerre des boutons");
        $f5->setDuree(90);
        $f5->setDateSortie(new \DateTime("19-04-1962"));
        $f5->setNote(7);
        $f5->setAgeMinimal(0);
        $f5->setGenre($g4);
        $f5->addActeurJoue($a1);        
        $entityManager->persist($f5);
        
        $entityManager->flush();
        
        return $this->render('init/index.html.twig', [
            'controller_name' => 'InitController',
        ]);
        
    }
    
    /**
     * @Route("/cineland/init2", name="init2")
     */
    public function action13(): Response{
        return new Response("<html> <body><h1>TOKOOS</h1></body></html>");    
    }
        
}
