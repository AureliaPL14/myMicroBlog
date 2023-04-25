<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use App\Form\ConversationFormType;
use App\Form\MessageFormType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class MessengerController extends AbstractController
{
    #[Route('/messenger/{conversation}', name: 'app_messenger')]
    public function index(#[CurrentUser]User $user, EntityManagerInterface $entityManager, Request $request, Conversation $conversation = null): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageFormType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setAuthor($user);
            $message->setConversation($conversation);
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('app_messenger', ['conversation' => $conversation->getId()]);
        }
        return $this->render('messenger/index.html.twig', [
            'conversation' => $conversation,
            'form' => $form
        ]);
    }

    #[Route('/conversation/new', name: 'app_new_conversation')]
    public function conversation(#[CurrentUser]User $user, EntityManagerInterface $entityManager, Request $request): RedirectResponse|Response
    {
        $conversation = new Conversation();
        $mutuals = $user->getFollowers()->filter(function ($follower) use ($user) {
            if ($user->getFollowings()->contains($follower)) {
                return $follower;
            }
            return null;
        });
        $form = $this->createForm(ConversationFormType::class, $conversation, ['mutuals' => $mutuals]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conversation->getUsers()->add($user);
            if (!$conversation->getName()) {
                $name = implode(', ', $conversation->getUsers()->map(function ($member) {
                    return $member->getDisplayName();
                })->toArray());
                $conversation->setName($name);
            }
            $entityManager->persist($conversation);
            $entityManager->flush();

            return $this->redirectToRoute('app_messenger', ['conversation' => $conversation->getId()]);
        }

        return $this->render('messenger/new.html.twig', [
            'form' => $form
        ]);
    }
}
