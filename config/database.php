<?php

$host   = '127.0.0.1';
$porta  = '3306';          // use 3307 se seu XAMPP estiver nessa porta
$banco  = 'atendelab';
$usuario = 'root';
$senha  = '';              // senha do MySQL local (geralmente vazia no XAMPP)

try {
    $pdo = new PDO(
        "mysql:host={$host};port={$porta};dbname={$banco};charset=utf8mb4",
        $usuario,
        $senha,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Erro na conexão: ' . $e->getMessage());
}
