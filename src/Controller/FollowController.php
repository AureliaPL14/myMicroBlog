<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class FollowController extends AbstractController
{
    #[Route('/follow', name: 'app_follows')]
    public function index(): Response
    {
        return $this->render('follow/index.html.twig', [
            'controller_name' => 'FollowController',
        ]);
    }

    #[Route('/follow/{followed}', name: 'app_follow')]
    public function follow(Request $request, #[CurrentUser] User $following, User $followed, EntityManagerInterface $entityManager): RedirectResponse
    {
        $followed->addFollower($following);
        $entityManager->flush();
        return $this->redirectToRoute('app_profile', ['user' => $followed]);
    }

    #[Route('/unfollow/{unfollowed}', name: 'app_unfollow')]
    public function unfollow()
    {}
}
