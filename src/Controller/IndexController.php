<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Traits\PostTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class IndexController extends AbstractController
{
    use PostTrait;

    #[Route('/', name: 'app_index')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($user instanceof User) {
            $post = $this->createPost($user);
            $replyFormTool = $this->replyFormTool($request, $entityManager, $post);
            $postFormTool = $this->freshPostTool($request, $entityManager, $post);
            $timeline = $entityManager->getRepository(Post::class)->findByFollow($user);

            if ($postFormTool instanceof RedirectResponse) {
                return $postFormTool;
            }
            if ($replyFormTool instanceof RedirectResponse) {
                return $replyFormTool;
            }
        } else {
            $timeline = $entityManager->getRepository(Post::class)->findAll();
        }

        return $this->render('index/index.html.twig', [
            'create_post_form' => isset($postFormTool) ? $postFormTool->createView() : null,
            'reply_form' => isset($replyFormTool) ? $replyFormTool->createView() : null,
            'timeline' => $timeline
        ]);
    }
}
