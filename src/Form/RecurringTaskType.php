<?php

namespace App\Form;

use App\Entity\RecurringTasks;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RecurringTaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('recurrency', ChoiceType::class, [
                'choices' => [
                    'Daily' => 'd',
                    'Weekly' => 'w',
                    'Monthly' => 'm',
                ],
            ])
            ->add('task', EntityType::class, [
                'class' => 'App\Entity\Task',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->where('t.isDone = false')
                        ->orderBy('t.title', 'ASC');
                },
                'required' => true,
            ])
            ->add('user', EntityType::class, [
                'class' => 'App\Entity\User',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles LIKE :role')
                        ->setParameter('role', '%ROLE_EMPLOYEE%')
                        ->orderBy('u.firstname', 'ASC');
                },
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecurringTasks::class,
        ]);
    }
}
