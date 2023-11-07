<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Task;

use App\Entity\User;
use App\Entity\WorkSession;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{

    protected UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // create 5 employers
        for ($i=0; $i < 5; $i++) { 
            $employer = new User();
            $employer->setEmail($faker->email);
            $employer->setPassword($this->passwordHasher->hashPassword($employer, 'password'));
            $employer->setRoles(['ROLE_EMPLOYER']);
            $employer->setFirstName($faker->firstName);
            $employer->setLastName($faker->lastName);
            $manager->persist($employer);
        }
        $manager->flush();

        // create 5 employees
        for ($i=0; $i < 5; $i++) { 
            $employee = new User();
            $employee->setEmail($faker->email);
            $employee->setPassword($this->passwordHasher->hashPassword($employee, 'password'));
            $employee->setRoles(['ROLE_EMPLOYEE']);
            $employee->setFirstName($faker->firstName);
            $employee->setLastName($faker->lastName);
            $manager->persist($employee);
        }
        $manager->flush();

        // create 10 tasks
        // for ($i=0; $i < 10; $i++) { 
        //     $task = new Task();
        //     $task->setTitle($faker->sentence(3));
        //     $task->setDescription($faker->sentence(10));
        //     $task->setIsDone(false);
        //     $task->setAssignedUser($faker->randomElement($employee));
        //     $manager->persist($task);
        // }

        $manager->flush();

        // create 10 workSessions
        for ($i=0; $i < 10; $i++) { 
            $workSession = new WorkSession();
            $workSession->setStartDate($faker->dateTimeBetween('-1 years', 'now'));
            $workSession->setEndDate($faker->dateTimeBetween('-1 years', 'now'));
            $workSession->setUser($faker->randomElement($employee));
            $workSession->addTask($faker->randomElement($task));
            $manager->persist($workSession);
        }

        $manager->flush();
    }
}
