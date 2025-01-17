<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReplyPostFormType extends AbstractType
{
    public function __construct(
        private readonly PostRepository $postRepository
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'attr' => ['style' => 'resize: none; height: 10rem; border: none; box-shadow: none']
            ])
            ->add('parent', HiddenType::class)
        ;
        $builder->get('parent')->addModelTransformer(new CallbackTransformer(
            function ($post) {
                return $post?->getId();
            },
            function($id) {
                return $this->postRepository->find($id);
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'parent' => null
        ]);
    }
}
