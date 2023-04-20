<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('displayName', TextType::class, [])
            ->add('username', TextType::class, [])
            ->add('email', EmailType::class, [])
            ->add('password', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => false,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Password Confirmation']
            ])
            ->add('profilePicture', FileType::class, [
                'label' => 'Profile picture (.jpg or .png)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '3072k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid .jpg or .png image'
                    ])
                ]
            ])
            ->add('banner', FileType::class, [
                'label' => 'Banner picture (.jpg or .png)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '3072k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid .jpg or .png image'
                    ])
                ]
            ])
            ->add('bio', TextareaType::class, [
                'attr' => ['style' => 'resize: none; height: 10rem'],
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary rounded-pill']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'parent' => null
        ]);
    }
}
