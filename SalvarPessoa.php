<?php
include "Conexao.php";

if(isset($_POST["codpessoa"]) && $_POST["codpessoa"] != NULL && $_POST["codpessoa"] != ""){
    $sql = "update pessoa set nome = '{$_POST["nome"]}', dtnascimento = '{$_POST["dtnascimento"]}', email = '{$_POST["email"]}' 
    where codpessoa = {$_POST["codpessoa"]}";
}else{
    $sql = "insert into pessoa(nome, email, dtnascimento) values('{$_POST["nome"]}', '{$_POST["email"]}', '{$_POST["dtnascimento"]}');";
}

$resSalvar = mysqli_query($link, $sql);
if($resSalvar != FALSE){
    die(die(json_encode(array('mensagem' => 'Pessoa salva com sucesso!', 'situacao' => true))));
}else{
    die(die(json_encode(array('mensagem' => 'Erro ao salvar causado por: '. mysqli_error($link), 'situacao' => false))));
}