<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class LoadTaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('assignedUser', EntityType::class, [
            'class' => 'App\Entity\User',
            'query_builder' => function (\Doctrine\ORM\EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->where('u.roles LIKE :role')
                    ->setParameter('role', '%ROLE_EMPLOYEE%')
                    ->orderBy('u.firstname', 'ASC');
            },
            'label' => 'Employé',
            'required' => true,
            'attr' => [
                'class' => 'form-select block w-full px-3 py-2 mt-1 border rounded-md shadow-sm bg-white focus:outline-none focus:ring focus:border-blue-300',
            ],
        ])
        ->add('dueAt', DateTimeType::class, [
            'widget' => 'single_text',
            'label' => 'Date d\'échéance',
            'attr' => [
                'class' => 'form-input block w-full px-3 py-2 mt-1 border rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300',
            ],
        ])
        ->add('tasks', EntityType::class, [
            'class' => 'App\Entity\Task',
            'query_builder' => function (\Doctrine\ORM\EntityRepository $er) {
                return $er->createQueryBuilder('t')
                    ->where('t.isSave = true')
                    ->orderBy('t.title', 'ASC');
            },
            'label' => 'Tâches à charger',
            'choice_label' => 'title',
            'required' => true,
            'multiple' => true,
            'expanded' => true,
            'attr' => [
                'class' => 'form-checkbox',
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Charger',
            'attr' => [
                'class' => 'bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition duration-300 mt-4 inline-block',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
    }
}
