<?php
class Categoria
{
    private ?int $id;
    private string $categoria;

    public function __construct(?int $id, string $categoria)
    {
        $this->id = $id;
        $this->categoria = $categoria;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoria(): string
    {
        return $this->categoria;
    }
}
?>
