<?php
class CategoriaRepositorio
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function formarObjeto(array $d): Categoria
    {
        return new Categoria(
            isset($d['id']) ? (int)$d['id'] : null,
            $d['categoria'] ?? ''
        );
    }

    public function buscarTodos(): array
    {
        $sql = "SELECT id, categoria FROM categorias ORDER BY categoria";
        $stmt = $this->pdo->query($sql);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($d) => $this->formarObjeto($d), $dados);
    }

    public function buscar(int $id): ?Categoria
    {
        $sql = "SELECT id, categoria FROM categorias WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        return $dados ? $this->formarObjeto($dados) : null;
    }

    public function salvar(Categoria $categoria): void
    {
        $sql = "INSERT INTO categorias (categoria) VALUES (?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$categoria->getCategoria()]);
    }

    public function atualizar(Categoria $categoria): void
    {
        $sql = "UPDATE categorias SET categoria = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $categoria->getCategoria(),
            $categoria->getId()
        ]);
    }

    public function deletar(int $id): bool
    {
        $sql = "DELETE FROM categorias WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
