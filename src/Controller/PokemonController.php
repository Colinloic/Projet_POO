<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Pokemon;

use App\Form\PokemonFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Gestion des articles
 */
class PokemonController extends AbstractController
{
	/**
	 * @var ManagerRegistry
	 */
	protected $em;

	/**
	 * Constructeur
	 * Hydrate l'entityManager
	 * @param ManagerRegistry $entityManager
	 */
	public function __construct(ManagerRegistry $entityManager) {
		$this->em = $entityManager;
	}

    /**
     * Récupère la liste des catégories
     * @Route("/categorie", name="pokemon_categories")
     * @param ManagerRegistry $doctrine
     * @return Response
     */
	public function readAllCategories(ManagerRegistry $doctrine): Response
	{
		$categorie = $doctrine->getRepository(Category::class);
		$categorie = $categorie->getCategorie($doctrine);
		return $this->render('pokemon/categories.html.twig', [
			'categorie' => $categorie
		]);
	}

	    /**
     * Récupère et affiche tous les pokemons d'une catégorie
     * @Route("/categorie/{category}", name="pokemon_category")
     * @param ManagerRegistry $doctrine
     * @param string $category
     * @return Response
     */

     public function readAll(ManagerRegistry $doctrine, string $category): Response
     {
         $pokemon = $doctrine->getRepository(Pokemon::class);
         $categorie = $doctrine->getRepository(Category::class);
         $categories= $categorie->findBy(['name' => $category]);
         $pokemons = $pokemon->findBy(['type'=>$category]);
         $categorie = $doctrine->getRepository(Category::class);
         $categories= $categorie->findBy(['name' => $category]);
         return $this->render('pokemon/category.html.twig', [
             'pokemons' => $pokemons,
             'categories' => $categories,
             
            
        ]);
     }


    /**
     * Création d'un article
     * @Route("pokemon/create", name="pokemon_create")
     * @return Response
     */
    public function create(ManagerRegistry $doctrine, Request $request): Response
    {
        $pokemon = new Pokemon();
        $form = $this->createForm(PokemonFormType::class, $pokemon);
        $form->handleRequest($request);
        dump($form->getData());
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            return $this->redirectToRoute('pokemon_categories');
        }
        return $this->renderForm('pokemon/create.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * Récupère et affiche un article
     * @Route("/pokemon/{name}", name="pokemon_read")
     * @param ManagerRegistry $doctrine
     * @param string $name
     * @return Response
     */
	public function read(ManagerRegistry $doctrine, string $name): Response
	{
        $pokemon = $doctrine->getRepository(Pokemon::class);
        $pokemon = $pokemon->findBy(['name'=>$name]);

		return $this->render('pokemon/read.html.twig', [
			'pokemon' => $pokemon
		]);
	}

	/**
	 * Met à jour l'article
	 * @Route("/pokemon/{name}/update", name="pokemon_update")
	 * @return Response
	 */
	public function update(ManagerRegistry $doctrine, string $name, Request $request): Response
	{
        $pokemon = new Pokemon();
        $pokemonUpdate = $doctrine->getRepository(Pokemon::class);
        $pokemonUpdate = $pokemonUpdate->findBy(['name'=>$name]);

        $form = $this->createForm(PokemonFormType::class, $pokemon);
        $form->handleRequest($request);
        return $this->renderForm('pokemon/create.html.twig', [
            'form' => $form, 'pokemon' => $pokemonUpdate
        ]);

	}

	/**
	 * Supprime un pokemon
	 * @Route("/pokemon/{name}/delete", name="pokemon_delete")
	 */
	public function delete(ManagerRegistry $doctrine, string $name, Request $request)
	{
        $entityManager = $doctrine->getManager();
        $pokemon = $entityManager->getRepository(Pokemon::class)->findBy(['name'=>$name]);

        dump($pokemon);
        $entityManager->remove($pokemon[0]);
        $entityManager->flush();

	}
    /**
     * detail des créations des users
     * @Route("/user/pokemon", name="user")
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function pokemonsUser(ManagerRegistry $doctrine): Response
    {
        $utilisateur = $this->getUser();
        if($utilisateur){
            $liste_pokemon = $doctrine->getRepository(Pokemon::class);
            $liste_pokemon = $liste_pokemon->getPokemonUser($doctrine, 4);
            return $this->render('pokemon/detail_pokemon_user.html.twig', [
                'listePokemon' => $liste_pokemon
            ]);
        }
        return $this->render('pokemon/detail_pokemon_user.html.twig');
    }
}