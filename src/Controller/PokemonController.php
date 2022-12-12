<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Pokemon;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilder;
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
     * @Route("/pokemon/create", name="pokemon_create")
     * @return Response
     */
    public function create(): Response
    {
         $pokemon = new Pokemon();
         $form = $this->createFormBuilder($pokemon)

        ->add('nom', TextType::class)
        ->add('catégorie', TextType::class)
        ->add('save', SubmitType::class, ['label'=>'Créer Pokemon'])
        ->getForm();

        //$form = $this->createForm(Pokemon::class, $pokemon);
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

		$pokemon = $doctrine->getRepository(Pokemon::class);
		$pokemon = $pokemon->getPokemon($doctrine, $category, $id);
		return $this->render('pokemon/read.html.twig', [
			'pokemon' => $pokemon
		]);
	}

	/**
	 * Met à jour l'article
	 * @Route("/pokemon/{category}/{id}/update", name="pokemon_update")
	 * @return Response
	 */
	public function update(ManagerRegistry $doctrine, int $category, int $id): Response
	{
        $pokemon = $doctrine->getRepository(Pokemon::class);
        $pokemon = $pokemon->getPokemon($doctrine, $category, $id);
        $form = $this->createFormBuilder($pokemon)

            ->add('name', TextType::class , [
                'data' => "$pokemon[0].name" ,
            ])
            ->add('category', TextType::class)
            ->add('save', SubmitType::class, ['label'=>'Créer Pokemon'])
            ->getForm();

        //$form = $this->createForm(Pokemon::class, $pokemon);
        return $this->renderForm('pokemon/create.html.twig', [
            'form' => $form
        ]);
		//return $this->render('pokemon/create.html.twig');
	}

	/**
	 * Supprime un pokemon
	 * @Route("/pokemon/{category}/{id}", name="pokemon_delete")
	 */
	public function delete()
	{

	}
}