<?php

namespace App\Entity;

use App\Form\ClientFormType;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Update;
use Sylius\Resource\Model\ResourceInterface;
use Sylius\Resource\Metadata\Index;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[AsResource(
    section: 'dashboard',
    formType: ClientFormType::class,
    templatesDir: '@SyliusAdminUi/crud',
    routePrefix: '/dashboard',
    name: 'client',
    operations: [
        new Index(grid: 'app_client'),
        new Create(),
        new Update(),
    ],
)]
#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[UniqueEntity(fields: ['code'], message: 'app.alert.client_with_code_already_exists')]
class Client implements ResourceInterface
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['client:read', 'invoice:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups(['client:read', 'invoice:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups(['client:read'])]
    private ?string $address = null;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups(['client:read'])]
    private ?string $code = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups(['client:read'])]
    private ?string $vatCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 1, max: 255)]
    #[Assert\Email]
    #[Groups(['client:read'])]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups(['client:read'])]
    private ?string $mobile = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups(['client:read'])]
    private ?string $contact = null;

    /**
     * @var Collection<int, Invoice>
     */
    #[ORM\OneToMany(targetEntity: Invoice::class, mappedBy: 'client', fetch: 'EXTRA_LAZY')]
    private Collection $invoices;

    /**
     * @var Collection<int, Expense>
     */
    #[ORM\OneToMany(targetEntity: Expense::class, mappedBy: 'client')]
    private Collection $expenses;

    public function __construct()
    {
        $this->invoices = new ArrayCollection();
        $this->expenses = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getVatCode(): ?string
    {
        return $this->vatCode;
    }

    public function setVatCode(?string $vatCode): static
    {
        $this->vatCode = $vatCode;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): static
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return Collection<int, Invoice>
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): static
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices->add($invoice);
            $invoice->setClient($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): static
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getClient() === $this) {
                $invoice->setClient(null);
            }
        }

        return $this;
    }

    public function getTotalInvoices(): int
    {
        return $this->invoices->count();
    }

    public function __toString(): string
    {
        return $this->getName()??'';
    }

    /**
     * @return Collection<int, Expense>
     */
    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    public function addExpense(Expense $expense): static
    {
        if (!$this->expenses->contains($expense)) {
            $this->expenses->add($expense);
            $expense->setClient($this);
        }

        return $this;
    }

    public function removeExpense(Expense $expense): static
    {
        if ($this->expenses->removeElement($expense)) {
            // set the owning side to null (unless already changed)
            if ($expense->getClient() === $this) {
                $expense->setClient(null);
            }
        }

        return $this;
    }
}
