<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\CreatePostFormType;
use App\Form\RegistrationFormType;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(Request $request, #[CurrentUser] User $user, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $post->setAuthor($user);
        $form = $this->createForm(CreatePostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_index');
        }

        $timeline = $entityManager->getRepository(Post::class)->findByFollow($user);

        return $this->render('index/index.html.twig', [
            'create_post_form' => $form->createView(),
            'reply_form' => $form->createView(),
            'timeline' => $timeline
        ]);
    }
}
