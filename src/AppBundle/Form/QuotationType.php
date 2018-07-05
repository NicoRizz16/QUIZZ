<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class QuotationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', TextareaType::class, array(
                'label' => 'Contenu',
                'required' => true,
                'constraints' => array(
                    new Length(array(
                        'min' => 5,
                        'max' => 2000,
                        'minMessage' => 'Le contenu de la citation doit faire plus de {{ limit }} caractères',
                        'maxMessage' => 'Le contenu de la citation doit faire moins de {{ limit }} caractères'
                    )),
                    new NotBlank(array(
                        'message' => 'Vous devez indiquer un contenu'
                    )))))
                ->add('author', TextType::class, array(
                'label' => 'Auteur',
                'required' => true,
                'constraints' => array(
                    new Length(array(
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'L\'auteur de la citation doit faire plus de {{ limit }} caractères',
                        'maxMessage' => 'L\'auteur de la citation doit faire moins de {{ limit }} caractères'
                    )),
                    new NotBlank(array(
                        'message' => 'Vous devez indiquer un auteur'
                    ))
                ))
        );
    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Quotation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_quotation';
    }


}
