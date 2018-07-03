<?php

namespace AppBundle\Form;

use AppBundle\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;

class AdminQcmType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('question', TextareaType::class, array(
            'label' => 'Question',
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'Vous devez remplir ce champ'
                )),
                new Length(array(
                    'min' => 5,
                    'max' => 1000,
                    'minMessage' => 'La question doit faire plus de {{ limit }} caractères',
                    'maxMessage' => 'La question doit faire moins de {{ limit }} caractères'
                ))
            )
        ))
            ->add('questionImageFile', FileType::class, array(
                'required' => false,
                'label' => 'Image de la question',
                'constraints' => array(
                    new File(array(
                        'maxSize' => '1M',
                        'maxSizeMessage' => 'L\'image doit peser moins de 1MB.',
                        'mimeTypes' => 'image/*',
                        'mimeTypesMessage' => 'Ce format de fichier n\'est pas accepté.'
                    ))
                )
            ))
            ->add('answerA', TextType::class, array(
                'label' => 'Réponse A',
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Vous devez remplir ce champ'
                    )),
                    new Length(array(
                        'max' => 250,
                        'maxMessage' => 'La proposition doit faire moins de {{ limit }} caractères'
                    ))
                )
            ))
            ->add('veracityA', CheckboxType::class, array(
                'label' => 'Véracité',
                'required' => false,
                'data' => false
            ))
            ->add('answerB', TextType::class, array(
                'label' => 'Réponse B',
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Vous devez remplir ce champ'
                    )),
                    new Length(array(
                        'max' => 250,
                        'maxMessage' => 'La proposition doit faire moins de {{ limit }} caractères'
                    ))
                )
            ))
            ->add('veracityB', CheckboxType::class, array(
                'label' => 'Véracité',
                'required' => false,
                'data' => false
            ))
            ->add('answerC', TextType::class, array(
                'label' => 'Réponse C',
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Vous devez remplir ce champ'
                    )),
                    new Length(array(
                        'max' => 250,
                        'maxMessage' => 'La proposition doit faire moins de {{ limit }} caractères'
                    ))
                )
            ))
            ->add('veracityC', CheckboxType::class, array(
                'label' => 'Véracité',
                'required' => false,
                'data' => false
            ))
            ->add('answerD', TextType::class, array(
                'label' => 'Réponse D',
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Vous devez remplir ce champ'
                    )),
                    new Length(array(
                        'max' => 250,
                        'maxMessage' => 'La proposition doit faire moins de {{ limit }} caractères'
                    ))
                )
            ))
            ->add('veracityD', CheckboxType::class, array(
                'label' => 'Véracité',
                'required' => false,
                'data' => false
            ))
            ->add('answerE', TextType::class, array(
                'label' => 'Réponse E',
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Vous devez remplir ce champ'
                    )),
                    new Length(array(
                        'max' => 250,
                        'maxMessage' => 'La proposition doit faire moins de {{ limit }} caractères'
                    ))
                )
            ))
            ->add('veracityE', CheckboxType::class, array(
                'label' => 'Véracité',
                'required' => false,
                'data' => false
            ))
            ->add('comment', TextareaType::class, array(
                'label' => 'Correction',
                'constraints' => array(
                    new Length(array(
                        'max' => 10000,
                        'maxMessage' => 'La correction doit faire moins de {{ limit }} caractères'
                    ))
                )
            ))
            ->add('commentImageFile', FileType::class, array(
                'required' => false,
                'label' => 'Image de la correction',
                'constraints' => array(
                    new File(array(
                        'maxSize' => '1M',
                        'maxSizeMessage' => 'L\'image doit peser moins de 1MB.',
                        'mimeTypes' => 'image/*',
                        'mimeTypesMessage' => 'Ce format de fichier n\'est pas accepté.'
                    ))
                )
            ))
            ->add('countdown', IntegerType::class, array(
                'label' => 'Temps pour répondre (sec)',
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Vous devez remplir ce champ'
                    )),
                    new Type(array(
                        'type' => 'integer',
                        'message' => 'Entrez un nombre entier'
                    )),
                    new Range(array(
                        'min' => 30,
                        'minMessage' => 'Le temps minimum est {{ limit }} secondes',
                        'max' => 180,
                        'maxMessage' => 'Le temps maximum est {{ limit }} secondes'
                    ))
                )
            ))
            ->add('categories', EntityType::class, array(
                'class' => 'AppBundle\Entity\Category',
                'choice_label' => 'name',
                'label' => 'Choisir une ou plusieurs catégorie(s) :',
                'query_builder' => function(CategoryRepository $repository){
                    return $repository->getCategoriesList();
                },
                'choice_value' => 'id',
                'multiple' => true
            ))
            ->add('published', CheckboxType::class, array(
                'label' => 'Publier'
            ));

    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Qcm'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_qcm';
    }


}
