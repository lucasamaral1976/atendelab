<?php
require_once '../config/database.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AtendeLab</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin:0;
            padding:0;
        }

        .container{
            max-width:800px;
            margin:50px auto;
            background:white;
            padding:30px;
            border-radius:10px;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
        }

        h1{
            color:#2c3e50;
        }

        .status{
            background:#d4edda;
            color:#155724;
            padding:15px;
            border-radius:5px;
            margin-top:20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>AtendeLab</h1>

    <p>
        Sistema de gerenciamento de atendimentos acadêmicos.
    </p>

    <div class="status">
        ✅ Projeto funcionando corretamente.<br>
        ✅ PHP conectado ao MySQL.<br>
        ✅ Ambiente configurado com sucesso.
    </div>

    <hr>

    <h3>Informações do Projeto</h3>

    <ul>
        <li>Disciplina: Desenvolvimento Web</li>
        <li>Projeto: AtendeLab</li>
        <li>Aluno: Lucas Borges do Amaral</li>
        <li>Banco: atendelab</li>
    </ul>
</div>

</body>
</html>