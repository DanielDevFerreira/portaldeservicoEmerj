<?php
include_once './conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Celke</title>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    </head>
    <body>
        <h1>Pesquisar</h1>

            <label>Email: </label>
            <input type="email" name="email" size="60" id="email" onBlur="javascript:location.href='?email='+document.getElementById('email').value;" placeholder="Pesquisar pelo email2">


        <br><br>
        <?php
        $SendPesqMsg = filter_input(INPUT_POST, 'SendPesqMsg', FILTER_SANITIZE_STRING);
        if ($SendPesqMsg) {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);

            //SQL para selecionar os registros
            $result_msg_cont = "SELECT * FROM tb_usuario WHERE email LIKE '%" . $email . "%' ORDER BY email ASC LIMIT 7";
            $resultado_msg_cont = $conn->prepare($result_msg_cont);
            $resultado_msg_cont->execute();

            while ($row_msg_cont = $resultado_msg_cont->fetch(PDO::FETCH_ASSOC)) {
                echo "ID: " . $row_msg_cont['usuario_id'] . "<br>";
                echo "Nome: " . $row_msg_cont['usuario_nm'] . "<br>";
                echo "email: " . $row_msg_cont['email'] . "<br>";
                echo "Matricula: " . $row_msg_cont['matricula'] . "<br><hr>";
            }
        }
        ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

        <script>
            $(function () {
                $("#email").autocomplete({
                    source: 'proc_pesq_msg.php'
                });
            });
        </script>
    </body>
</html>
