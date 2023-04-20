<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileFormType;
use App\Traits\PostTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function edit(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, SluggerInterface $slugger, #[CurrentUser] User $user): Response
    {
        $editProfileForm = $this->createForm(EditProfileFormType::class, $user);
        $editProfileForm->handleRequest($request);

        if ($editProfileForm->isSubmitted() && $editProfileForm->isValid()) {
            if ($editProfileForm->get('password')->getData()) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $editProfileForm->get('password')->getData()
                    )
                );
            }
            $profilePicture = $editProfileForm->get('profilePicture')->getData();
            if ($profilePicture) {
                $newFilename = $user->getUsername() . '.' . $profilePicture->guessExtension();

                try {
                    $profilePicture->move(
                        $this->getParameter('profile_pictures'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $user->setProfilePicture('/profile_pictures/' . $newFilename);
            }

            $banner = $editProfileForm->get('banner')->getData();
            if ($banner) {
                $newFilename = $user->getUsername() . '.' . $banner->guessExtension();

                try {
                    $banner->move(
                        $this->getParameter('banner_pictures'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $user->setBanner('/banner_pictures/' . $newFilename);
            }
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
