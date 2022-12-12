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
     * @Route("/pokemon", name="pokemon_categories")
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
     * @Route("/pokemon/{category}", name="pokemon_category")
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


         return $this->render('pokemon/category.html.twig', [
             'pokemons' => $pokemons,
             'categories' => $categories,
        ]);
     }


    /**
     * Création d'un article
     * @Route("user/pokemon/create", name="pokemon_create")
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
     * @Route("/pokemon/{category}/{id}", name="pokemon_read")
     * @param ManagerRegistry $doctrine
     * @param int $category
     * @param int $id
     * @return Response
     */
	public function read(ManagerRegistry $doctrine, int $category, int $id): Response
	{

        $PokemonCat = $doctrine->getRepository(Category::class);
        $pokemon = $doctrine->getRepository(Pokemon::class);
        $pokemon = $pokemon->getPokemon($doctrine, $category, $id);

		//$pokemon = $pokemon->getPokemon($doctrine, $category, $id);
		return $this->render('pokemon/read.html.twig', [
			'pokemon' => $pokemon
		]);
	}

	/**
	 * Met à jour l'article
	 * @Route("user/pokemon/{category}/{id}/update", name="pokemon_update")
	 * @return Response
	 */
	public function update(ManagerRegistry $doctrine, int $category, int $id, Request $request): Response
	{
        $pokemon = new Pokemon();
        $pokemonUpdate = $doctrine->getRepository(Pokemon::class);
        $pokemonUpdate = $pokemonUpdate->getPokemon($doctrine, $category, $id);
        $form = $this->createForm(PokemonFormType::class, $pokemon);
        $form->handleRequest($request);
        return $this->renderForm('pokemon/create.html.twig', [
            'form' => $form, 'pokemon' => $pokemonUpdate
        ]);

	}

	/**
	 * Supprime un pokemon
	 * @Route("user/pokemon/{category}/{id}", name="pokemon_delete")
	 */
	public function delete()
	{

	}
    /**
     * detail des créations des users
     * @Route("/user", name="user")
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function detailPokemonUser(ManagerRegistry $doctrine): Response
    {
        $utilisateur = $this->getUser();
        if($utilisateur){
            $liste_pokemon = $doctrine->getRepository(Pokemon::class);
            $liste_pokemon = $liste_pokemon->getPokemonUser($doctrine, 4);
            return $this->render('auth/detail_pokemon_user.html.twig', [
                'listePokemon' => $liste_pokemon
            ]);
        }
        return $this->render('auth/detail_pokemon_user.html.twig');
    }
}