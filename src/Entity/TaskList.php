<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskListRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class TaskList
{
    use Timestamps;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, options={"default"="background.png"}, nullable=true)
     */
    private $background;

    /**
     * @ORM\Column(type="string", length=255, options={"default"="background.png"}, nullable=true)
     */
    private $backgroundPath;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="list", cascade={"REMOVE"})
     */
    private $tasks;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="lists")
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Preference", mappedBy="list", cascade={"PERSIST", "REMOVE"})
     */
    private $preferences;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBackground(): ?string
    {
        return $this->background;
    }

    public function setBackground(string $background): self
    {
        $this->background = $background;

        return $this;
    }

    public function getBackgroundPath(): ?string
    {
        return $this->backgroundPath;
    }

    public function setBackgroundPath(string $backgroundPath): self
    {
        $this->backgroundPath = $backgroundPath;

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setList($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getList() === $this) {
                $task->setList(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPreferences(): ?Preference
    {
        return $this->preferences;
    }

    public function setPreferences(?Preference $preferences): self
    {
        $this->preferences = $preferences;

        // set (or unset) the owning side of the relation if necessary
        $newList = $preferences === null ? null : $this;
        if ($newList !== $preferences->getList()) {
            $preferences->setList($newList);
        }

        return $this;
    }
}
