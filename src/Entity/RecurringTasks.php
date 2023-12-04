<?php

namespace App\Entity;

use App\Repository\RecurringTasksRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecurringTasksRepository::class)]
class RecurringTasks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Task $task = null;

    #[ORM\ManyToOne(inversedBy: 'recurringTasks')]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $recurrency = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): static
    {
        $this->task = $task;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getRecurrency(): ?string
    {
        return $this->recurrency;
    }

    public function setRecurrency(string $recurrency): static
    {
        $this->recurrency = $recurrency;

        return $this;
    }
}
