<?php
    require_once __DIR__ . '/src/conexao-bd.php';
try {
    echo "Conexão realizada com sucesso!";
} catch(PDOException $e){
    echo "Erro na conexão: " . $e->getMessage();
}


?>