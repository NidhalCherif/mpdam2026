<?php

namespace App\Entity;

use App\Repository\BooksRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: BooksRepository::class)]
class Books
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: ' Le titre est obligatoire!!!')]
    #[Assert\Length(min: 2, max: 255, minMessage: 'le titre doit avoir au mminimum {{limit}} caractères')]
    private ?string $titre = null;

    #[ORM\Column(name: 'is_read')]
    private ?bool $read = null;

    #[ORM\Column]
    #[Assert\Range(min: 0, max: 5, notInRangeMessage: ' la valuer de rating doit être comprise entre {{min}} et {{max}}')]
    private ?int $rating = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getRead(): ?bool
    {
        return $this->read;
    }

    public function setRead(bool $read): static
    {
        $this->read = $read;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }
    #[Assert\Callback]
    public function validateRating(ExecutionContextInterface $context)
    {
        if ($this->rating > 0 && !$this->read) {
            $context->buildViolation('impossible de noter un livre non lu!!!')
                ->atPath('rating')
                ->addViolation();
        }
    }
}
