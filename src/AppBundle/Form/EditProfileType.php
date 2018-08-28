<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditProfileType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('profileImageFile', FileType::class, array(
                'required' => false,
                'label' => 'Image de profil',
                'constraints' => array(
                    new File(array(
                        'maxSize' => '1M',
                        'maxSizeMessage' => 'L\'image doit peser moins de 1MB.',
                        'mimeTypes' => 'image/*',
                        'mimeTypesMessage' => 'Ce format de fichier n\'est pas accepté.'
                    ))
                )
            ))
            ->add('username', TextType::class, array(
                'label' => 'Nom d\'utilisateur :',
                'required' => true,
                'constraints' => array(
                    new Length(array(
                        'min' => 3,
                        'max' => 50,
                        'minMessage' => 'Le nom d\'utilisateur doit faire plus de {{ limit }} caractères',
                        'maxMessage' => 'Le nom d\'utilisateur doit faire moins de {{ limit }} caractères'
                    )),
                    new NotBlank(array(
                        'message' => 'Vous devez indiquer un nom d\'utilisateur'
                    ))
                )
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Adresse e-mail :',
                'constraints' => array(
                    new Email(array(
                        'message' => 'Vous devez entrer une adresse email valide.',
                        'checkMX' => true
                    )),
                    new NotBlank(array(
                        'message' => 'Vous devez indiquer une adresse email'
                    ))
                )
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'first_options'  => array('label' => 'Nouveau mot de passe'),
                'second_options' => array('label' => 'Confirmation'),
                'constraints' => array(
                    new Length(array(
                        'min' => 3,
                        'max' => 30,
                        'minMessage' => 'Le mot de passe doit faire plus de {{ limit }} caractères',
                        'maxMessage' => 'Le mot de passe doit faire moins de {{ limit }} caractères'
                    ))
                )
            ))
        ;

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

}
