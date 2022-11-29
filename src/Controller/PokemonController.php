<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Pokemon;
use Doctrine\Persistence\ManagerRegistry;
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
	 * @return Response
	 */

	 /* A modifier */
	public function readAll(ManagerRegistry $doctrine): Response
	{
		$categorie = $doctrine->getRepository(Pokemon::class);
		$categorie = $categorie->getPokemonCategorie($doctrine);
		return $this->render('pokemon/category.html.twig');
	}

	/**
	 * Création d'un article
	 * @Route("/pokemon/create", name="pokemon_create")
	 * @return Response
	 */
	public function create(): Response
	{
		return $this->render('pokemon/create.html.twig');
	}

	/**
	 * Récupère et affiche un article
	 * @Route("/pokemon/{category}/{id}", name="pokemon_create")
	 * @return Response
	 */
	public function read(): Response
	{
		return $this->render('pokemon/read.html.twig');
	}

	/**
	 * Met à jour l'article
	 * @Route("/pokemon/{category}/{id}/update", name="pokemon_update")
	 * @return Response
	 */
	public function update(): Response
	{
		return $this->render('pokemon/update.html.twig');
	}

	/**
	 * Supprime un pokemon
	 * @Route("/pokemon/{category}/{id}", name="pokemon_delete")
	 */
	public function delete()
	{

	}
}