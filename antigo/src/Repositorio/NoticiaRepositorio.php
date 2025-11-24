<?php

use App\Modelo\Noticia;

class NoticiaRepositorio
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // =============================
    //  TRANSFORMA LINHA EM OBJETO
    // =============================
    private function formarObjeto(array $linha): Noticia
    {
        return new Noticia(
            isset($linha['idNoticia']) ? (int)$linha['idNoticia'] : null,
            $linha['nome'],
            $linha['conteudo'],
            $linha['fonte'],
            new DateTime($linha['dataNoticia']),
            $linha['imagem'] ?? null,
            $linha['categoriaId'] ?? null,
            $linha['categoriaNome'] ?? null
        );
    }

    // =============================
    //  BUSCAR TODAS
    // =============================
    public function buscarTodas(): array
    {
        $sql = "SELECT  
                    n.id_noticia AS idNoticia,
                    n.nome,
                    n.conteudo,
                    n.fonte,
                    n.dataNoticia,
                    n.imagem,
                    c.id AS categoriaId,
                    c.categoria AS categoriaNome
                FROM tbNoticia n
                LEFT JOIN categorias c ON c.id = n.id_categoria
                ORDER BY n.dataNoticia DESC";

        $statement = $this->pdo->query($sql);
        $dados = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($n) => $this->formarObjeto($n), $dados);
    }

    // =============================
    //  BUSCAR POR ID
    // =============================
    public function buscar(int $idNoticia): ?Noticia
    {
        $sql = "SELECT 
                    n.id_noticia AS idNoticia,
                    n.nome,
                    n.conteudo,
                    n.fonte,
                    n.dataNoticia,
                    n.imagem,
                    c.id AS categoriaId,
                    c.categoria AS categoriaNome
                FROM tbNoticia n
                LEFT JOIN categorias c ON c.id = n.id_categoria
                WHERE n.id_noticia = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idNoticia]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        return $dados ? $this->formarObjeto($dados) : null;
    }

    // =============================
    //  SALVAR
    // =============================
    public function salvar(Noticia $noticia)
    {
        $sql = "INSERT INTO tbNoticia 
                (nome, fonte, conteudo, dataNoticia, imagem, id_categoria)
                VALUES 
                (:nome, :fonte, :conteudo, :dataNoticia, :imagem, :categoria)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':nome', $noticia->getNome());
        $stmt->bindValue(':fonte', $noticia->getFonte());
        $stmt->bindValue(':conteudo', $noticia->getConteudo());
        $stmt->bindValue(':dataNoticia', $noticia->getDataNoticia()->format('Y-m-d'));
        $stmt->bindValue(':categoria', $noticia->getCategoriaId(), PDO::PARAM_INT);

        $stmt->bindValue(':imagem', $noticia->getImagem() ?: 'logo-footnews.png');

        $stmt->execute();
    }

    // =============================
    //  ATUALIZAR
    // =============================
    public function atualizar(Noticia $noticia)
    {
        $sql = "UPDATE tbNoticia SET 
                    nome = :nome,
                    fonte = :fonte,
                    conteudo = :conteudo,
                    dataNoticia = :dataNoticia,
                    imagem = :imagem,
                    id_categoria = :categoria
                WHERE id_noticia = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':nome', $noticia->getNome());
        $stmt->bindValue(':fonte', $noticia->getFonte());
        $stmt->bindValue(':conteudo', $noticia->getConteudo());
        $stmt->bindValue(':dataNoticia', $noticia->getDataNoticia()->format('Y-m-d'));
        $stmt->bindValue(':categoria', $noticia->getCategoriaId(), PDO::PARAM_INT);
        $stmt->bindValue(':imagem', $noticia->getImagem() ?: 'logo-footnews.png');

        $stmt->execute();
    }


    public function deletar(int $id)
    {
        $sql = "SELECT imagem FROM tbNoticia WHERE id_noticia = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $imagem = $stmt->fetchColumn();

        $sqlDel = "DELETE FROM tbNoticia WHERE id_noticia = ?";
        $stmtDel = $this->pdo->prepare($sqlDel);
        $stmtDel->execute([$id]);

        if ($stmtDel->rowCount() && $imagem && $imagem !== 'logo-footnews.png') {
            $caminho = __DIR__ . '/../../uploads/' . $imagem;
            if (is_file($caminho)) @unlink($caminho);
        }
    }


public function buscarTodasComFiltro(
    string $ordem = 'dataNoticia',
    string $direcao = 'DESC',
    int $pagina = 1,
    int $itensPorPagina = 10
): array {
    $offset = ($pagina - 1) * $itensPorPagina;
    $sql = "SELECT  
                n.id_noticia AS idNoticia,
                n.nome,
                n.conteudo,
                n.fonte,
                n.dataNoticia,
                n.imagem,
                c.id AS categoriaId,
                c.categoria AS categoriaNome
            FROM tbNoticia n
            LEFT JOIN categorias c ON c.id = n.id_categoria
            ORDER BY {$ordem} {$direcao}
            LIMIT :limit OFFSET :offset";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':limit', $itensPorPagina, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return array_map(fn($n) => $this->formarObjeto($n), $stmt->fetchAll(PDO::FETCH_ASSOC));
}

public function totalRegistros(): int
{
    return (int) $this->pdo->query('SELECT COUNT(*) FROM tbNoticia')->fetchColumn();
}
}