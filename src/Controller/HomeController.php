<?php

namespace App\Controller;

use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Gestion de la page d'accueil
 */
class HomeController extends AbstractController
{
	/**
	 * Retourne deux catégories et articles aléatoires
	 * @Route("/", name="home")
	 * @param PokemonRepository $pokemonRepository
	 * @return Response
	 */
	public function index(PokemonRepository $pokemonRepository): Response
	{
		// Récupère 2 catégories et 4 articles aléatoires avec findRandom()
		$randomArticle = $pokemonRepository->findRandom();

		return $this->render('home/index.html.twig', [
			'randomArticle' => $randomArticle
		]);
	}
}
