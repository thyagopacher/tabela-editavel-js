<?php
include "Conexao.php";

if(isset($_POST["codpessoa"]) && $_POST["codpessoa"] != NULL && $_POST["codpessoa"] != ""){
    $sql = "delete from pessoa
    where codpessoa = {$_POST["codpessoa"]}";
}else{
    die(die(json_encode(array('mensagem' => 'Erro ao excluir não houve passagem de código!', 'situacao' => false))));
}

$resSalvar = mysqli_query($link, $sql);
if($resSalvar != FALSE){
    die(die(json_encode(array('mensagem' => 'Pessoa excluida com sucesso!', 'situacao' => true))));
}else{
    die(die(json_encode(array('mensagem' => 'Erro ao salvar causado por: '. mysqli_error($link), 'situacao' => false))));
}