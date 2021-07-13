<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Comment;
use App\Entity\Post;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SandBoxController extends AbstractController
{
    /**
     * @Route("/sb/create", name="create")
     */
    public function create(): Response
    {
        // Récupère l'entity manager
        $entityManager = $this->getDoctrine()->getManager();

        // Crée un auteur puis un post puis un commentaire

        $author =new Author();
        // Création de valeur pour notre author
        $slug = uniqid();   //<= permet de créer une chaine de caractère unique

        $today = new DateTime();

        $author->setLastname('firstname '. $slug);
        $author->setFirstname($slug);
        $author->setCreatedAt($today);
        $author->setUpdateAt($today);

        // Enregistre un nouvel objet
        $entityManager->persist($author);
        $entityManager->flush();

        dump($author);

        $post = new Post();

        $post->setTitle('Un post '.$slug);
        $post->setBody('lorem ipsum dolor colorum en nermo lorem ipsum dolor colorum en nermo lorem ipsum dolor colorum en nermo');
        $post->setCreatedAt($today);
        $post->setUpdatedAt($today);
        $post->setNbLikes(rand(0, 1000));
        $post->setAuthor($author);

        $entityManager->persist($post);

        dump($post);

        $comment = new Comment();

        $comment->setUsername('commentateur '. $slug);
        $comment->setBody('lorem ipsum dolor colorum en nermo');
        $comment->setCreatedAt($today);
        $comment->setPublishedAt($today);
        $comment->setPost($post);

        $entityManager->persist($comment);

        $entityManager->flush();

        dump($post);
        dump($author);
        return $this->render('sand_box/index.html.twig', [
            'controller_name' => 'SandBoxController',
        ]);
    }
    /**
     * @Route("/sb/author/{id}", name="author_post")
     */
    public function authorPost(Author $author, EntityManagerInterface $entityManager): Response
    {
        return $this->render('sand_box/author.html.twig', [
            'author' => $author,
        ]);
    }
    /**
     * @Route("/sb/add_post/{id}", name="author_add_post")
     */
    public function addPostAuthor(Author $author, EntityManagerInterface $entityManager): Response
    {
        //si on n'utilise le convertissuer de paramètre // Récupère l'auteur
        // $authorRepository = $this->getDoctrine()->getRepository(Author::class);
        // $author = $authorRepository->find($id);

        // Création du post
        $slug = uniqid();
        $today = new DateTime();
        $post = new Post();
        $post->setTitle('Un post à lier impérativement'.$slug);
        $post->setBody('lorem ipsum dolor colorum en nermo lorem ipsum dolor colorum en nermo lorem ipsum dolor colorum en nermo');
        $post->setCreatedAt($today);
        $post->setUpdatedAt($today);
        $post->setNbLikes(rand(0, 1000));
        $post->setAuthor($author);

        $entityManager->persist($post);

        // Ici il faudrait plutot faire un $post->setAuthor
        // Car c'est l'entity post qui est owner de la relation
        $author->addPost($post);

        return $this->render('sand_box/index.html.twig', [
            'author' => $author,
        ]);
    }
}
