<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LogRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity(repositoryClass: LogRepository::class)]
class Log
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $serviceName;

    #[ORM\Column(type: 'datetime')]
    private $date;

    #[ORM\Column(type: 'string', length: 255)]
    private $request;

    #[ORM\Column(type: 'integer')]
    private $statusCode;

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
     * getServiceName
     *
     * @return string
     */
    public function getServiceName(): ?string
    {
        return $this->serviceName;
    }

    /**
     * setServiceName
     *
     * @param  string $serviceName
     * @return self
     */
    public function setServiceName(string $serviceName): self
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    /**
     * getDate
     *
     * @return DateTimeInterface
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * setDate
     *
     * @param  DateTimeInterface $date
     * @return self
     */
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * getRequest
     *
     * @return string
     */
    public function getRequest(): ?string
    {
        return $this->request;
    }

    /**
     * setRequest
     *
     * @param  string $request
     * @return self
     */
    public function setRequest(string $request): self
    {
        $this->request = $request;

        return $this;
    }

    /**
     * getStatusCode
     *
     * @return int
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    /**
     * setStatusCode
     *
     * @param  int $statusCode
     * @return self
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * fromAssociativeArray
     *
     * @param  array $data
     * @return Log
     */
    public function fromAssociativeArray(array $data): Log
    {
        $this->serviceName = $data["serviceName"];
        $this->date = new DateTime($data["date"]);
        $this->request = $data["request"];
        $this->statusCode = (int) $data["statusCode"];
        return $this;
    }
}
