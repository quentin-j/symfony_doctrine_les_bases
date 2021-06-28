<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/posts", name="list")
     */
    public function list(PostRepository $postRepository): Response
    {
        // récupérer la liste de tous les postes
        // $postRepository = $this->getDoctrine()->getRepository(Post::class); // <= on peut récupèrer cet objet en typant une variable en paramètre de la fonction
        $allPosts = $postRepository->findAll();

        return $this->render('main/list.html.twig', [
            'all_posts' => $allPosts
        ]);
    }

    /**
     * @Route("/post/create", name="create")
     */
    public function create(EntityManagerInterface $entityManager): Response
    {
        // créer un post 
        $post = new Post();
        $today = new DateTime();

        $randomString = uniqid(); // <= permet d'avoir des chaine de caractère unique
        $post->setTitle('Title ' . $randomString);
        $post->setBody('body ' . $randomString);
        $post->setNbLikes(rand(0, 500));

        $post->setCreatedAt($today);
        $post->setUpdatedAt($today);

        // enregistre le post en BDD
        // $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($post);
        $entityManager->flush();

        return $this->redirectToRoute('list');
    }
    /**
     * @Route("post/update/{id}", name="update")
     */
    public function update(Post $postToUpdate, EntityManagerInterface $entityManager): Response
    {
        // récupèrer l'objet
        // $postRepository = $this->getDoctrine()->getRepository(Post::class);
        // $postToUpdate = $postRepository->find($id);

        // mettre à jour 
        $postToUpdate->setUpdatedAt(new DateTime());

        // $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('list');
    }
      /**
     * @Route("post/delete/{id}", name="delete")
     */
    public function delete(Post $postToDelete, EntityManagerInterface $entityManager): Response
    {
        // récupèrer l'objet
        // $postRepository = $this->getDoctrine()->getRepository(Post::class);
        // $postToDelete = $postRepository->find($id);

        // supprimer l'objet 
        // $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($postToDelete);
        $entityManager->flush();

        return $this->redirectToRoute('list');
    }
}
