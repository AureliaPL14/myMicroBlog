<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\User;
use App\Form\ConversationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class MessengerController extends AbstractController
{
    #[Route('/messenger/{conversation}', name: 'app_messenger')]
    public function index(Conversation $conversation = null): Response
    {
        return $this->render('messenger/index.html.twig', [
            'conversation' => $conversation
        ]);
    }

    #[Route('/conversation/new', name: 'app_new_conversation')]
    public function conversation(#[CurrentUser]User $user, EntityManagerInterface $entityManager, Request $request): RedirectResponse|Response
    {
        $conversation = new Conversation();
        $form = $this->createForm(ConversationFormType::class, $conversation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($conversation);
            $entityManager->flush();

            return $this->redirectToRoute('app_messenger', ['conversation' => $conversation]);
        }

        return $this->render('messenger/new.html.twig');
    }
}
