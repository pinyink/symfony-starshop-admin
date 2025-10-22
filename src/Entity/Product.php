<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $nama = null;

    #[ORM\Column(nullable: true)]
    private ?int $harga = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $tanggal = null;

    #[ORM\Column(nullable: true)]
    private ?int $tahun = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNama(): ?string
    {
        return $this->nama;
    }

    public function setNama(string $nama): static
    {
        $this->nama = $nama;

        return $this;
    }

    public function getHarga(): ?int
    {
        return $this->harga;
    }

    public function setHarga(?int $harga): static
    {
        $this->harga = $harga;

        return $this;
    }

    public function getTanggal(): ?\DateTime
    {
        return $this->tanggal;
    }

    public function setTanggal(?\DateTime $tanggal): static
    {
        $this->tanggal = $tanggal;

        return $this;
    }

    public function getTahun(): ?int
    {
        return $this->tahun;
    }

    public function setTahun(?int $tahun): static
    {
        $this->tahun = $tahun;

        return $this;
    }
}
