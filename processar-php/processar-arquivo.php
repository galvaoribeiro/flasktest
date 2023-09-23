<?php

    // Caminho para o arquivo wp-load.php
    $wp_load_path = 'wp-load.php'; // Substitua pelo caminho correto
    
    // Verifica se o arquivo wp-load.php existe
    if (file_exists($wp_load_path)) {
        require_once($wp_load_path);
    } else {
        die('Erro: O arquivo wp-load.php não foi encontrado.');
}




// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Diretório de destino para salvar os arquivos
    $diretorioDestino = wp_upload_dir()['path'];

    // Verifica se o diretório de destino existe, se não, tenta criá-lo
    if (!file_exists($diretorioDestino)) {
        mkdir($diretorioDestino, 0755, true);
    }

    // Verifica se um arquivo foi enviado com sucesso
    if ($_FILES["arquivo"]["error"] == UPLOAD_ERR_OK) {
        // Nome do arquivo original
        $nomeArquivoOriginal = $_FILES["arquivo"]["name"];

        // Caminho temporário do arquivo
        $caminhoTemporario = $_FILES["arquivo"]["tmp_name"];

        // Cria um nome de arquivo único para evitar substituições
        $nomeArquivoUnico = uniqid() . "_" . $nomeArquivoOriginal;

        // Caminho completo para o arquivo de destino
        $caminhoCompleto = $diretorioDestino . "/" . $nomeArquivoUnico;

        // Move o arquivo para o diretório de destino
        if (move_uploaded_file($caminhoTemporario, $caminhoCompleto)) {
            // Arquivo carregado com sucesso
            echo "Arquivo carregado com sucesso. Nome do arquivo: " . $nomeArquivoUnico;
        } else {
            // Erro ao mover o arquivo
            echo "Erro ao mover o arquivo para o diretório de destino.";
        }
    } else {
        // Ocorreu um erro durante o upload do arquivo
        echo "Ocorreu um erro durante o upload do arquivo. Código de erro: " . $_FILES["arquivo"]["error"];
    }
} else {
    // Formulário não foi submetido
    echo "O formulário não foi submetido corretamente.";
}
?>
