<?php

namespace App\Entity;

use App\Form\ServiceFormType;
use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Update;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[AsResource(
    section: 'dashboard',
    formType: ServiceFormType::class,
    templatesDir: '@SyliusAdminUi/crud',
    routePrefix: '/dashboard',
    name: 'service',
    operations: [
        new Index(grid: 'app_service'),
        new Create(),
        new Update(),
    ],
)]
#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service implements ResourceInterface
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $price = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    /**
     * @var Collection<int, InvoiceLine>
     */
    #[ORM\OneToMany(targetEntity: InvoiceLine::class, mappedBy: 'service')]
    private Collection $invoiceLine;

    public function __construct()
    {
        $this->invoiceLine = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection<int, InvoiceLine>
     */
    public function getInvoiceLine(): Collection
    {
        return $this->invoiceLine;
    }

    public function addInvoiceLine(InvoiceLine $invoiceLine): static
    {
        if (!$this->invoiceLine->contains($invoiceLine)) {
            $this->invoiceLine->add($invoiceLine);
            $invoiceLine->setService($this);
        }

        return $this;
    }

    public function removeInvoiceLine(InvoiceLine $invoiceLine): static
    {
        if ($this->invoiceLine->removeElement($invoiceLine)) {
            // set the owning side to null (unless already changed)
            if ($invoiceLine->getService() === $this) {
                $invoiceLine->setService(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s (%s &euro;)', $this->getName(), $this->getPrice());
    }
}
