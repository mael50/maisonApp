<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'assignedUser', targetEntity: Task::class)]
    private Collection $tasks;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: WorkSession::class)]
    private Collection $workSessions;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: RecurringTasks::class)]
    private Collection $recurringTasks;

    #[ORM\Column(nullable: true)]
    private ?float $hourlyRate = null;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->workSessions = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->recurringTasks = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setAssignedUser($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getAssignedUser() === $this) {
                $task->setAssignedUser(null);
            }
        }

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
            $workSession->setUser($this);
        }

        return $this;
    }

    public function removeWorkSession(WorkSession $workSession): static
    {
        if ($this->workSessions->removeElement($workSession)) {
            // set the owning side to null (unless already changed)
            if ($workSession->getUser() === $this) {
                $workSession->setUser(null);
            }
        }

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    public function getMonthWorkTime(): DateTime
    {
        $month_year = new DateTime();

        $worktime = new DateTime('@0');

        foreach($this->getWorkSessions() as $workSession) {
            if($workSession->getStartDate()->format('m') === $month_year->format('m') && $workSession->getStartDate()->format('Y') === $month_year->format('Y')) {
                $worktime->add($workSession->getDuration());
            }
        }

        return $worktime;
    }

    public function getWorkTimeFromMonth($month): DateTime
    {
        $worktime = new DateTime('@0');

        foreach($this->getWorkSessions() as $workSession) {
            if($workSession->getStartDate()->format('m') === $month) {
                $worktime->add($workSession->getDuration());
            }
        }

        return $worktime;
    }

    /**
     * @return Collection<int, RecurringTasks>
     */
    public function getRecurringTasks(): Collection
    {
        return $this->recurringTasks;
    }

    public function addRecurringTask(RecurringTasks $recurringTask): static
    {
        if (!$this->recurringTasks->contains($recurringTask)) {
            $this->recurringTasks->add($recurringTask);
            $recurringTask->setUser($this);
        }

        return $this;
    }

    public function removeRecurringTask(RecurringTasks $recurringTask): static
    {
        if ($this->recurringTasks->removeElement($recurringTask)) {
            // set the owning side to null (unless already changed)
            if ($recurringTask->getUser() === $this) {
                $recurringTask->setUser(null);
            }
        }

        return $this;
    }

    public function getHourlyRate(): ?float
    {
        return $this->hourlyRate;
    }

    public function setHourlyRate(?float $hourlyRate): static
    {
        $this->hourlyRate = $hourlyRate;

        return $this;
    }

}
