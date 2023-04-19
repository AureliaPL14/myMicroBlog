<?php

namespace App\Traits;

use App\Entity\Post;
use App\Entity\User;
use App\Form\CreatePostFormType;
use App\Form\ReplyPostFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

trait PostTrait
{
    public function createPost(User $user): Post
    {
        $post = new Post();
        $post->setAuthor($user);

        return $post;
    }

    public function replyFormTool(Request $request, EntityManagerInterface $entityManager, Post $post): RedirectResponse|FormInterface
    {
        $replyForm = $this->createForm(ReplyPostFormType::class, $post);
        $replyForm->handleRequest($request);

        if ($replyForm->isSubmitted() && $replyForm->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_index');
        }

        return $replyForm;
    }

    public function freshPostTool(Request $request, EntityManagerInterface $entityManager, Post $post): RedirectResponse|FormInterface
    {
        $createPostForm = $this->createForm(CreatePostFormType::class, $post);
        $createPostForm->handleRequest($request);

        if ($createPostForm->isSubmitted() && $createPostForm->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_index');
        }

        return $createPostForm;
    }
}