<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Pokemon;

use App\Form\CategoryFormType;
use App\Form\PokemonFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
         $pokemons = $pokemon->findBy(['type'=>$category]);
         $categorie = $doctrine->getRepository(Category::class);
         $categories= $categorie->findBy(['name' => $category]);
         return $this->render('pokemon/category.html.twig', [
             'pokemons' => $pokemons,
             'categories' => $categories,
             
            
        ]);
     }

    /**
     * Création d'une catégorie
     * @Route("/category/create", name="create_category")
     * @param ManagerRegistry $doctrine
     * @param Request $request
     * @return Response
     */
    public function CreateCategory(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted()){

            $category->setName($form->get('name')->getData());
            $category->setUrl($form->get('url')->getData());
            $file = $form->get('logo_url')->getData();
            $Filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($Filename);
            $newFilename = $safeFilename.'.'.$file->guessExtension();
            $file->move(
                '../public/static/logo_categories',
                $newFilename
            );
            $category->setLogoUrl($newFilename);
            $entityManager->persist($category);
            $entityManager->flush();

        }
        return $this->render('category/addCategory.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Supression d'une catégorie
     * @Route("/category/{id}/delete", name="delete_category")
     * @param ManagerRegistry $doctrine
     * @param int $id
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function DeleteCategory(ManagerRegistry $doctrine, int $id, Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $entityManager = $doctrine->getManager();
        $category = $entityManager->getRepository(Category::class)->findOneBy(['id'=>$id]);
        $entityManager->remove($category);
        $entityManager->flush();
        $filesystem = new Filesystem();
        $nameFile = $doctrine->getRepository(Category::class)->find($id)->getLogoUrl();
        $filesystem->remove("../public/static/logo_categories/".$nameFile);
        $categorie = $doctrine->getRepository(Category::class)->getCategorie($doctrine);
        return $this->render('pokemon/categories.html.twig', [
            'categorie' => $categorie
        ]);

    }

    /**
     * Modification d'une catégorie
     * @Route("/categorie/{id}/update", name="update_category")
     * @return Response
     */
    public function UpdateCategory(): Response
    {
        return $this->render('pokemon/category.html.twig');
    }


    /**
     * Création d'un pokemon
     * @Route("pokemon/create", name="pokemon_create")
     * @return Response
     */
    public function create(ManagerRegistry $doctrine, Request $request,EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $pokemon = new Pokemon();
        dump($pokemon);
        $form = $this->createForm(PokemonFormType::class, $pokemon);
        $form->handleRequest($request);
        //dump($form->getData());
        if ($form->isSubmitted()) {
          $pokemon->setAuthor($this->getUser()->getUserIdentifier());
          $pokemon->setUser($this->getUser());

            $file = $form->get('image_url')->getData();

            $Filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($Filename);
            $newFilename = $safeFilename.'.'.$file->guessExtension();

            $file->move(
                '../public/static/pokemons',
                $newFilename
            );
            $entityManager->persist($pokemon);
            $entityManager->flush();

            return $this->redirectToRoute('pokemon_categories');
        }
        return $this->renderForm('pokemon/create.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * Récupère et affiche un article
     * @Route("/{name}", name="pokemon_read")
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
     * @Route("/{name}/update", name="pokemon_update")
     * @param ManagerRegistry $doctrine
     * @param string $name
     * @param Request $request
     * @return Response
     */
	public function update(ManagerRegistry $doctrine, string $name, Request $request): Response
	{
        $pokemon = new Pokemon();
        $pokemonUpdate = $doctrine->getRepository(Pokemon::class);
        $pokemonUpdate = $pokemonUpdate->findBy(['name'=>$name]);
        $form = $this->createForm(PokemonFormType::class, $pokemon);
        $form->handleRequest($request);

        if($form->isSubmitted()){


        }
        return $this->renderForm('pokemon/create.html.twig', [
            'form' => $form, 'pokemon' => $pokemonUpdate
        ]);

	}

	/**
	 * Supprime un pokemon
	 * @Route("/{id}/delete", name="pokemon_delete")
	 */
	public function delete(ManagerRegistry $doctrine, int $id, EntityManagerInterface $entityManager): Response
	{
        $entityManager = $doctrine->getManager();
        $pokemon = $entityManager->getRepository(Pokemon::class)->find($id);
        $entityManager->remove($pokemon);
        $entityManager->flush();
        $filesystem = new Filesystem();
        $nameFile = $pokemon->getImageUrl();
        $filesystem->remove("../public/static/pokemons/".$nameFile);

        return $this->redirectToRoute('user');

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

            $liste_pokemon = $doctrine->getRepository(Pokemon::class)
                //->findBy(['author' => $utilisateur->getUserIdentifier()]);
            ->findBy(['user' => $utilisateur]);
            return $this->render('pokemon/detail_pokemon_user.html.twig', [
                'listePokemon' => $liste_pokemon
            ]);
        }
        return $this->render('pokemon/detail_pokemon_user.html.twig');
    }
}