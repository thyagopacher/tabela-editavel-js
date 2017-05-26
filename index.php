<?php
include "Conexao.php";
?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <title>Tabela editável</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        <style>
            .pointer{
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <?php
        $colunas = array('Nome', 'E-mail', 'Nascimento');
        ?>
        <div class="container">
            <h2>Tabela editável</h2>
            <p>A tabela seguinte serve para clica na linha e editar dados ou apagar diretamente nela:</p>            
            <table id="tabela_editada" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center"><i id="adicionarLinhaTable" class="fa fa-plus pointer" aria-hidden="true"></i></th>
                            <?php
                            foreach ($colunas as $key => $coluna) {
                                echo '<th>', $coluna, '</th>';
                            }
                            ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $reslinha = mysqli_query($link, "select * from pessoa");
                    $qtdlinha = mysqli_num_rows($reslinha);
                    if ($qtdlinha > 0) {
                        $linha = 1;
                        while ($pessoa = mysqli_fetch_array($reslinha)) {
                            echo '<tr id="linha', $linha, '">';
                            echo '<td>';
                            echo '<i class="fa fa fa-floppy-o pointer" onclick="salvarLinha(', $linha, ')" aria-hidden="true"></i> ';
                            echo '<i class="fa fa-trash pointer" onclick="excluirLinha(', $linha, ')" aria-hidden="true"></i> ';
                            echo '</td>';
                            echo '<td>';
                            echo '<input class="form-control" codigo="', $pessoa["codpessoa"], '" type="text" name="nome" id="nome', $linha, '" maxlength="150" minlength="3" required value="', $pessoa["nome"], '">';
                            echo '</td>';
                            echo '<td>';
                            echo '<input class="form-control" codigo="', $pessoa["codpessoa"], '" type="email" name="email" id="email', $linha, '" maxlength="150" minlength="5" required value="', $pessoa["email"], '">';
                            echo '</td>';
                            echo '<td>';
                            echo '<input class="form-control" codigo="', $pessoa["codpessoa"], '" type="date" name="dtnascimento" max="', date("Y-m-d"), '" id="dtnascimento', $linha, '" maxlength="10" minlength="10" required value="', $pessoa["dtnascimento"], '">';
                            echo '</td>';
                            echo '</tr>';
                            $linha++;
                        }
                    }
                    ?>                    
                </tbody>
            </table>
        </div>
        <link rel="stylesheet" href="css/sweetalert.min.css" />
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/sweetalert.min.js"></script>
        <script>
            function salvarLinha(prox) {
                if ($("#nome" + prox).prop("required") && $("#nome" + prox).val() === "") {
                    swal('Atenção', "Por favor preencher nome!", "info");
                } else if ($("#email" + prox).prop("required") && $("#email" + prox).val() === "") {
                    swal('Atenção', "Por favor preencher email!", "info");
                } else if ($("#dtnascimento" + prox).prop("required") && $("#dtnascimento" + prox).val() === "") {
                    swal('Atenção', "Por favor preencher dt. nascimento!", "info");
                } else {
                    var codigo = $("#nome" + prox).attr("codigo");
                    $.ajax({
                        url: "SalvarPessoa.php",
                        type: "POST",
                        data: {nome: $("#nome" + prox).val(), email: $("#email" + prox).val(),
                            dtnascimento: $("#dtnascimento" + prox).val(), codpessoa: codigo},
                        dataType: 'json',
                        success: function (data, textStatus, jqXHR) {
                            if (data.situacao == true) {
                                swal('Cadastro', data.mensagem, "success");
                            } else {
                                swal("Erro", "Erro causado por:" + data.mensagem, "error");
                            }
                        }, error: function (jqXHR, textStatus, errorThrown) {
                            swal("Erro", "Erro causado por:" + errorThrown, "error");
                        }
                    });
                }
            }

            function excluirLinha(prox) {
                var codigo = $("#nome" + prox).attr("codigo");
                if (codigo != undefined && codigo != "") {
                    swal({
                        title: "Confirma exclusão?",
                        text: "Você não poderá mais visualizar as informações dessa linha!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Sim, exclua ela!",
                        cancelButtonText: "Não",
                        closeOnConfirm: false,
                        closeOnCancel: true
                    }, function (isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "ExcluirPessoa.php",
                                type: "POST",
                                data: {codpessoa: codigo},
                                dataType: 'json',
                                success: function (data, textStatus, jqXHR) {
                                    if (data.situacao == true) {
                                        swal('Exclusão', data.mensagem, "success");
                                        $("#linha" + prox).remove();
                                        qtdLinhas--;
                                        if (qtdLinhas < 0) {
                                            qtdLinhas = 0;
                                        }
                                    } else {
                                        swal("Erro", "Erro causado por:" + data.mensagem, "error");
                                    }
                                }, error: function (jqXHR, textStatus, errorThrown) {
                                    swal("Erro", "Erro causado por:" + errorThrown, "error");
                                }
                            });
                        }
                    });
                } else {
                    $("#linha" + prox).remove();
                    qtdLinhas--;
                    if (qtdLinhas < 0) {
                        qtdLinhas = 0;
                    }
                }
            }

            var qtdLinhas = <?= $qtdlinha ?>;
            var prox = qtdLinhas + 1;
            $("#adicionarLinhaTable").click(function () {
                prox = qtdLinhas + 1;
                var html = '';
                html += '<tr id="linha' + prox + '">';
                html += '<td>';
                html += '<i class="fa fa-floppy-o pointer" onclick="salvarLinha(' + prox + ')" aria-hidden="true"></i> ';
                html += '<i class="fa fa-times pointer" onclick="excluirLinha(' + prox + ')" aria-hidden="true"></i> ';
                html += '</td>';

                html += '<td>';
                html += '<input class="form-control" type="text" name="nome" id="nome' + prox + '" maxlength="150" minlength="3" required>';
                html += '</td>';

                html += '<td>';
                html += '<input class="form-control" type="email" name="email" id="email' + prox + '" maxlength="150" minlength="5" required>';
                html += '</td>';

                html += '<td>';
                html += '<input class="form-control" type="date" name="dtnascimento" max="<?= date("Y-m-d") ?>" id="dtnascimento' + prox + '" maxlength="10" minlength="10" required>';
                html += '</td>';
                html += '</tr>';
                $("#tabela_editada tbody").append(html);
                qtdLinhas++;
            });

        </script>
    </body>
</html>
<?php
mysqli_close($link);
