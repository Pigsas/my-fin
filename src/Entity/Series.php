<?php

namespace App\Entity;

use App\Enum\InvoiceType;
use App\Form\SeriesFormType;
use App\Repository\SeriesRepository;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Update;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[AsResource(
    section: 'dashboard',
    formType: SeriesFormType::class,
    templatesDir: '@SyliusAdminUi/crud',
    routePrefix: '/dashboard',
    name: 'series',
    operations: [
        new Index(grid: 'app_series'),
        new Create(),
        new Update(),
    ],
)]
#[ORM\Entity(repositoryClass: SeriesRepository::class)]
#[UniqueEntity(fields: ['series'], message: 'app.alert.series_already_exists')]
class Series implements ResourceInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true, enumType: InvoiceType::class)]
    private ?InvoiceType $type = null;

    #[ORM\Column(length: 5, unique: true)]
    private ?string $series = null;

    #[ORM\Column]
    private ?int $counter = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?InvoiceType
    {
        return $this->type;
    }

    public function setType(?InvoiceType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getSeries(): ?string
    {
        return $this->series;
    }

    public function setSeries(string $series): static
    {
        $this->series = $series;

        return $this;
    }

    public function getCounter(): ?int
    {
        return $this->counter;
    }

    public function setCounter(int $counter): static
    {
        $this->counter = $counter;

        return $this;
    }

    public function getNextDocumentNumber(): string
    {
        return $this->series
            . str_pad(
                (string) ($this->counter + 1),
                12 - strlen($this->series),
                '0',
                STR_PAD_LEFT
            );
    }

    public function incrementCounter(): static
    {
        $this->counter++;
        return $this;
    }
}
