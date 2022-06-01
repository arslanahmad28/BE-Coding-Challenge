<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ImportJobRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImportJobRepository::class)]
class ImportJob
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $filepath;

    #[ORM\Column(type: 'integer')]
    private $startingRow;

    #[ORM\Column(type: 'integer')]
    private $endingRow;

    #[ORM\Column(type: 'string', length: 255)]
    private $status;

    /**
     * getId
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * getName
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * setName
     *
     * @param  mixed $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * getFilepath
     *
     * @return string
     */
    public function getFilepath(): ?string
    {
        return $this->filepath;
    }

    /**
     * setFilepath
     *
     * @param  string $filepath
     * @return self
     */
    public function setFilepath(string $filepath): self
    {
        $this->filepath = $filepath;

        return $this;
    }

    /**
     * getStartingRow
     *
     * @return int
     */
    public function getStartingRow(): ?int
    {
        return $this->startingRow;
    }

    /**
     * setStartingRow
     *
     * @param  int $startingRow
     * @return self
     */
    public function setStartingRow(int $startingRow): self
    {
        $this->startingRow = $startingRow;

        return $this;
    }

    /**
     * getEndingRow
     *
     * @return int
     */
    public function getEndingRow(): ?int
    {
        return $this->endingRow;
    }

    /**
     * setEndingRow
     *
     * @param  int $endingRow
     * @return self
     */
    public function setEndingRow(int $endingRow): self
    {
        $this->endingRow = $endingRow;

        return $this;
    }

    /**
     * getStatus
     *
     * @return string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * setStatus
     *
     * @param  string $status
     * @return self
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
