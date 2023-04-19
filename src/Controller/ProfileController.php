<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileFormType;
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

    #[Route('/edit', name: 'app_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, #[CurrentUser] User $user): Response
    {
        $editProfileForm = $this->createForm(EditProfileFormType::class, $user);
        $editProfileForm->handleRequest($request);

        if ($editProfileForm->isSubmitted() && $editProfileForm->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_profile', [
                'user' => $user,
                'reply_form' => $this->replyFormTool($request, $entityManager, $this->createPost($user))
            ]);
        }
        return $this->render('profile/edit.html.twig', [
            'user' => $user,
            'edit_profile_form' => $editProfileForm->createView()
        ]);
    }
}
