<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $isDone = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?User $assignedUser = null;

    #[ORM\ManyToMany(targetEntity: WorkSession::class, mappedBy: 'tasks')]
    private Collection $workSessions;

    public function __construct()
    {
        $this->workSessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isIsDone(): ?bool
    {
        return $this->isDone;
    }

    public function setIsDone(bool $isDone): static
    {
        $this->isDone = $isDone;

        return $this;
    }

    public function getAssignedUser(): ?User
    {
        return $this->assignedUser;
    }

    public function setAssignedUser(?User $assignedUser): static
    {
        $this->assignedUser = $assignedUser;

        return $this;
    }

    /**
     * @return Collection<int, WorkSession>
     */
    public function getWorkSessions(): Collection
    {
        return $this->workSessions;
    }

    public function addWorkSession(WorkSession $workSession): static
    {
        if (!$this->workSessions->contains($workSession)) {
            $this->workSessions->add($workSession);
            $workSession->addTask($this);
        }

        return $this;
    }

    public function removeWorkSession(WorkSession $workSession): static
    {
        if ($this->workSessions->removeElement($workSession)) {
            $workSession->removeTask($this);
        }

        return $this;
    }
}
