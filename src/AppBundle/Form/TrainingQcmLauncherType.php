<?php
/**
 * Created by PhpStorm.
 * User: nicolasrizzon
 * Date: 19/06/2018
 * Time: 10:10
 */

namespace AppBundle\Form;

use AppBundle\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;


class TrainingQcmLauncherType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('serieLength', ChoiceType::class, array(
                'choices' => array(
                    '5' => 5,
                    '10' => 10,
                    '20' => 20
                ),
                'label' => 'Choisissez un nombre de QCM',
                'required' => true
            ))
        ;
    }
}