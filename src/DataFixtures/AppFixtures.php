<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Task;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\WorkSession;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{

    protected UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        

        // create 25 employees
        for ($i=0; $i < 25; $i++) { 
            $employee = new User();
            $employee->setEmail($faker->email);
            $employee->setPassword($this->passwordHasher->hashPassword($employee, 'password'));
            $employee->setRoles(['ROLE_EMPLOYEE']);
            $employee->setFirstName($faker->firstName);
            $employee->setLastName($faker->lastName);
            $employee->setHourlyRate($faker->randomFloat(2, 10, 25));
            $manager->persist($employee);
        }
        $manager->flush();


        $employees = $manager->getRepository(User::class)->findAll();

        // create 300 tasks
        for ($i = 0; $i < 300; $i++) {
            $task = new Task();
            $task->setTitle($faker->sentence(3));
            $task->setDescription($faker->sentence(10));
            $task->setIsDone(false);
            $task->setAssignedUser($faker->randomElement($employees));
            $task->setDueAt($faker->dateTimeBetween('-1 years', 'now'));
            $task->setIsSave($faker->boolean(50));
            $manager->persist($task);
        }

        $manager->flush();

        $tasks = $manager->getRepository(Task::class)->findAll();

        // create 50 workSessions
        for ($i=0; $i < 250; $i++) { 
            $workSession = new WorkSession();
            $workSession->setStartDate($faker->dateTimeBetween('-1 years', 'now'));
            $workSession->setEndDate($faker->dateTimeBetween($workSession->getStartDate(), '+5 hours'));
            $workSession->setUser($faker->randomElement($employees));
            // add between 1 and 10 tasks to the workSession
            for ($j=0; $j < $faker->numberBetween(1, 10); $j++) { 
                $workSession->addTask($faker->randomElement($tasks));
                foreach ($workSession->getTasks() as $task) {
                    $task->setIsDone(true);
                }
            }
            $manager->persist($workSession);
        }

        $manager->flush();

        // create 1 employers
        for ($i=0; $i < 1; $i++) { 
            $employer = new User();
            $employer->setEmail('admin@test.fr');
            $employer->setPassword($this->passwordHasher->hashPassword($employer, 'password'));
            $employer->setRoles(['ROLE_EMPLOYER']);
            $employer->setFirstName($faker->firstName);
            $employer->setLastName($faker->lastName);
            $manager->persist($employer);
        }
        $manager->flush();


        $workSessions = $manager->getRepository(WorkSession::class)->findAll();
        // create 100 comments
        for ($i=0; $i < 100; $i++) { 
            $comment = new Comment();
            $comment->setAuthor($faker->randomElement($employees));
            $comment->setContent($faker->sentence(10));
            $comment->setCreatedAt($faker->dateTimeBetween('-1 years', 'now'));
            $comment->setWorksession($faker->randomElement($workSessions));
            $manager->persist($comment);
        }

        $manager->flush();
    }
}
