<?php

namespace App\Entity;

use App\Enum\InvoiceStatus;
use App\Enum\InvoiceType;
use App\Factory\InvoiceFactory;
use App\Form\InvoiceFormType;
use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Update;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[AsResource(
    section: 'dashboard',
    formType: InvoiceFormType::class,
    templatesDir: '@SyliusAdminUi/crud',
    routePrefix: '/dashboard',
    name: 'invoice',
    operations: [
        new Index(grid: 'app_invoice'),
        new Create(factory: InvoiceFactory::class),
        new Update(),
    ],
)]
#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
#[UniqueEntity(fields: ['documentNumber'], message: 'app.alert.invoice_with_number_already_exists')]
class Invoice implements ResourceInterface
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['invoice:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[Groups(['invoice:read'])]
    private ?Client $client = null;

    #[ORM\Column(enumType: InvoiceType::class)]
    #[Assert\NotBlank]
    #[Groups(['invoice:read'])]
    private ?InvoiceType $type = InvoiceType::STANDARD;

    #[ORM\Column(length: 12, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 12)]
    #[Groups(['invoice:read'])]
    private ?string $documentNumber = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    #[Groups(['invoice:read'])]
    private ?\DateTime $dueDate;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    #[Groups(['invoice:read'])]
    private ?\DateTime $date;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['invoice:read'])]
    private ?string $comment = null;

    #[ORM\Column(enumType: InvoiceStatus::class)]
    #[Assert\NotBlank]
    #[Groups(['invoice:read'])]
    private ?InvoiceStatus $status = null;

    /**
     * @var Collection<int, InvoiceLine>
     */
    #[ORM\OneToMany(targetEntity: InvoiceLine::class, mappedBy: 'invoice', cascade: ['persist'], orphanRemoval: true)]
    #[Assert\Count(min: 1)]
    #[Assert\Valid]
    #[Groups(['invoice:read'])]
    private Collection $lines;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['invoice:read'])]
    private ?\DateTime $paidAt = null;

    #[ORM\ManyToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Series $series = null;

    public function __construct()
    {
        $this->lines = new ArrayCollection();
        $this->dueDate = new \DateTime();
        $this->date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getType(): ?InvoiceType
    {
        return $this->type;
    }

    public function setType(InvoiceType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDocumentNumber(): ?string
    {
        return $this->documentNumber;
    }

    public function setDocumentNumber(string $documentNumber): static
    {
        $this->documentNumber = $documentNumber;

        return $this;
    }

    public function getDueDate(): ?\DateTime
    {
        return $this->dueDate;
    }

    public function setDueDate(\DateTime $dueDate): static
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

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

    public function getStatus(): ?InvoiceStatus
    {
        return $this->status;
    }

    public function setStatus(InvoiceStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, InvoiceLine>
     */
    public function getLines(): Collection
    {
        return $this->lines;
    }

    public function addLine(InvoiceLine $invoiceLine): static
    {
        if (!$this->lines->contains($invoiceLine)) {
            $this->lines->add($invoiceLine);
            $invoiceLine->setInvoice($this);
        }

        return $this;
    }

    public function removeLine(InvoiceLine $invoiceLine): static
    {
        if ($this->lines->removeElement($invoiceLine)) {
            // set the owning side to null (unless already changed)
            if ($invoiceLine->getInvoice() === $this) {
                $invoiceLine->setInvoice(null);
            }
        }

        return $this;
    }

    public function getPaidAt(): ?\DateTime
    {
        return $this->paidAt;
    }

    public function setPaidAt(?\DateTime $paidAt): static
    {
        $this->paidAt = $paidAt;

        return $this;
    }

    public function getSeries(): ?Series
    {
        return $this->series;
    }

    public function setSeries(?Series $series): static
    {
        $this->series = $series;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->lines->reduce(function ($total, InvoiceLine $invoiceLine) {
            return $total += (float)$invoiceLine->getTotal();
        });
    }

    public function getSubTotal(): ?float
    {
        return $this->lines->reduce(function ($total, InvoiceLine $invoiceLine) {
            return $total += (float)$invoiceLine->getPrice() * (float)$invoiceLine->getAmount();
        });
    }

    public function getDiscountAmount(): ?float
    {
        return $this->lines->reduce(function ($total, InvoiceLine $invoiceLine) {
            return $total += (float)$invoiceLine->getPrice() * (float)$invoiceLine->getAmount() * (float)$invoiceLine->getDiscount() / 100;
        });
    }

    public function setLines(array $array): self
    {
        $this->lines = new ArrayCollection($array);
        return $this;
    }
}
