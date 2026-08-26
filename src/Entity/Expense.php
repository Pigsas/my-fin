<?php

namespace App\Entity;

use App\Enum\ExpenseStatus;
use App\Enum\ExpenseType;
use App\Form\ExpenseFormType;
use App\Repository\ExpenseRepository;
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
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Attribute as Vich;
use Vich\UploaderBundle\Validator\Constraints as VichAssert;
use Symfony\Component\Validator\Constraints as Assert;

#[AsResource(
    section: 'dashboard',
    formType: ExpenseFormType::class,
    templatesDir: '@SyliusAdminUi/crud',
    routePrefix: '/dashboard',
    name: 'expense',
    operations: [
        new Index(grid: 'app_expense'),
        new Create(),
        new Update(),
        new Delete()
    ],
)]
#[ORM\Entity(repositoryClass: ExpenseRepository::class)]
#[UniqueEntity(fields: ['documentNumber'], message: 'app.alert.invoice_with_number_already_exists')]
#[Vich\Uploadable]
class Expense implements ResourceInterface
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'expenses')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Client $client = null;

    #[ORM\Column(enumType: ExpenseType::class)]
    #[Assert\NotBlank]
    private ?ExpenseType $type = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTime $date = null;

    #[ORM\Column(length: 50, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 50)]
    private ?string $documentNumber = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(0)]
    private ?string $total = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(0)]
    private ?string $vat = null;

    #[ORM\Column(enumType: ExpenseStatus::class)]
    #[Assert\NotBlank]
    private ?ExpenseStatus $status = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[Vich\UploadableField(mapping: 'expenses', fileNameProperty: 'fileName', size: 'fileSize')]
    #[VichAssert\FileRequired(target: 'fileName')]
    #[Assert\File(
        mimeTypes: [
            'application/pdf',
            'image/*',
        ],
    )]
    private ?File $file = null;

    #[ORM\Column(nullable: true)]
    private ?string $fileName = null;

    #[ORM\Column(nullable: true)]
    private ?int $fileSize = null;

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

    public function getType(): ?ExpenseType
    {
        return $this->type;
    }

    public function setType(ExpenseType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): static
    {
        $this->date = $date;

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

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getVat(): ?string
    {
        return $this->vat;
    }

    public function setVat(string $vat): static
    {
        $this->vat = $vat;

        return $this;
    }

    public function getStatus(): ?ExpenseStatus
    {
        return $this->status;
    }

    public function setStatus(ExpenseStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function setFile(?File $file = null): void
    {
        $this->file = $file;

        if (null !== $file) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFileName(?string $fileName): void
    {
        $this->fileName = $fileName;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileSize(?int $fileSize): void
    {
        $this->fileSize = $fileSize;
    }

    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }
}
