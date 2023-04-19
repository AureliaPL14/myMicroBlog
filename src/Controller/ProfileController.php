<?php

namespace App\Controller;

use App\Entity\User;
use App\Traits\PostTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ProfileController extends AbstractController
{
    use PostTrait;

    #[Route('/profile/{user}', name: 'app_profile')]
    public function index(Request $request, EntityManagerInterface $entityManager, User $user, #[CurrentUser] User $current): Response
    {
        $replyForm = $this->replyFormTool($request, $entityManager, $this->createPost($current));
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'reply_form' => $replyForm
        ]);
    }
}
