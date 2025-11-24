<?php
declare(strict_types=1);

namespace App\Modelo;

class Noticia
{
    private ?int $idNoticia;
    private string $nome;
    private string $conteudo;
    private string $fonte;
    private \DateTime $dataNoticia;
    private ?string $imagem;

    private ?int $categoriaId;  

    public function __construct(
        ?int $idNoticia,
        string $nome,
        string $conteudo,
        string $fonte,
        \DateTime $dataNoticia,
        ?string $imagem,
        ?int $categoriaId = null,     
    ) {
        $this->idNoticia = $idNoticia;
        $this->nome = $nome;
        $this->conteudo = $conteudo;
        $this->fonte = $fonte;
        $this->dataNoticia = $dataNoticia;
        $this->imagem = $imagem;

        $this->categoriaId = $categoriaId;
    }

    public function getIdNoticia(): ?int {
        return $this->idNoticia;
    }

    public function getNome(): string { 
        return $this->nome; 
    }

    public function getFonte(): string { 
        return $this->fonte; 
    }

    public function getConteudo(): string { 
        return $this->conteudo; 
    }

    public function getImagem(): ?string { 
        return $this->imagem; 
    }

    public function setImagem(?string $imagem): void {
        $this->imagem = $imagem;
    }

    public function getDataNoticia(): \DateTime { 
        return $this->dataNoticia; 
    }

    public function getDataBr(): string {
        return $this->dataNoticia->format('d/m/Y H:i');
    }

    public function getImagemDiretorio(): string
    {
        $uploadsPath = __DIR__ . '/../../uploads/';
        if ($this->imagem && file_exists($uploadsPath . $this->imagem)) {
            return 'uploads/' . $this->imagem;
        }
        return 'img/' . ($this->imagem ?: 'logo-footnews.png');
    }

    public function getCategoriaId(): ?int {
        return $this->categoriaId;
    }
}
