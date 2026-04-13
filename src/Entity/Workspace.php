<?php

namespace App\Entity;

use App\Repository\WorkspaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkspaceRepository::class)]
class Workspace
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'workspace', targetEntity: Column::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $columns;

    #[ORM\ManyToOne(inversedBy: 'workspaces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $user = null;

    public function __construct()
    {
        $this->columns = new ArrayCollection();
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

    public function addColumn(Column $column): self
    {
        if (!$this->columns->contains($column)) {
            $this->columns[] = $column;
            $column->setWorkspace($this);
        }

        return $this;
    }

    public function removeColumn(Column $column): self
    {
        if ($this->columns->removeElement($column)) {
            if ($column->getWorkspace() === $this) {
                $column->setWorkspace(null);
            }
        }

        return $this;
    }

    public function getColumns(): Collection
    {
        return $this->columns;
    }
}
