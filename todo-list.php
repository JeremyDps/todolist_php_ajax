<?php session_start() ?>
<!DOCTYPE html>
    <html>
        <title>todo-list</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="todo-list.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
        <!--<script src='https://kit.fontawesome.com/a076d05399.js'></script>-->
        <script src="https://api.jquery.com/jquery.ajax/"></script>
        <script type="text/javascript" src="todo-list.js"></script>
    </html>
    <body>

        <div id="container">
            <?php

            try {
                $pdo = new PDO('mysql:host=localhost;dbname=todo-list;charset=utf8', 'root', '');
            } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }

            if(isset($_GET["action"]) && isset($_GET['name'])) {

                if($_GET['action'] === 'add') {

                    $add = $pdo->prepare('INSERT INTO tasks (id, date, name) VALUES (default, :date, :nom)');

                    if(isset($_POST['date'])) {
                        echo 'formulaire envoyé';
                        $date = $_POST['date'];
                        $name = $_POST['name'];

                        $add->bindParam(':date', $date);
                        $add->bindParam(':nom', $name);
                    }else {
                        $date = date("Y-m-d H:i:s");


                        $add->bindParam(':date', $date);
                        $add->bindParam(':nom', $_GET['name']);
                    }

                    if($add->execute()) {
                        $_SESSION['msg'] = "Votre nouvelle tâche a été ajoutée avec succès";
                        $_SESSION['class_msg'] = "green";
                    } else {
                        $_SESSION['msg'] = "Une erreur est survenue dans la création de la nouvelle tâche";
                        $_SESSION['class_msg'] = "red";
                    }

                    $add->closeCursor();

                } else {
                    echo 'Paramètres de l\'URL invalides wesh';
                }

            } else if(isset($_GET['action']) && isset($_GET['id'])) {

                if ($_GET['action'] === 'delete') {
                    $del = $pdo->prepare("delete from tasks where id = :id");
                    $del->bindParam(':id', $_GET['id']);
                    $del->execute();

                    if($del->rowCount() === 1) {
                        $_SESSION['msg'] = "La tâche a été supprimée avec succès";
                        $_SESSION['class_msg'] = "green";
                    } else {
                        $_SESSION['msg'] = "Une erreur est survenue lors de la suppression de la tâche";
                        $_SESSION['class_msg'] = "red";
                    }
                    $del->closeCursor();

                } else if ($_GET['action'] === 'update') {
                    if(isset($_POST['date']) && isset($_POST['name'])) {
                        $date = $_POST['date'];
                        $name = $_POST['name'];

                        $put = $pdo->prepare("update tasks set date = :date, name = :name where id = :id");
                        $put->bindParam(':date', $date);
                        $put->bindParam(':name', $name);
                    }else{
                        $name = "Nouveau nom";
                        $put = $pdo->prepare("update tasks set name = :name where id = :id");
                    }


                    $put->bindParam(':name', $name);
                    $put->bindParam(':id', $_GET['id']);

                    $put->execute();

                    if($put->rowCount() === 1) {
                        $_SESSION['msg'] = "La tâche a été modifiée avec succès";
                        $_SESSION['class_msg'] = "green";
                    }else{
                        $_SESSION['msg'] = "Une erreur est survenue lors de la modification de la tâche";
                        $_SESSION['class_msg'] = "red";
                    }

                } else {
                    $_SESSION['msg'] = 'Paramètres de l\'URL invalides';
                    $_SESSION['class_msg'] = "red";
                }

            } else {

                /*$allDatas = $pdo->query("SELECT * FROM tasks order by id asc");

                $allDatas->execute();

                $json = json_encode($allDatas->fetchAll());

                $allDatas->closeCursor();*/
                ?>

                <h1>TODO-LIST</h1>
                <form id="creer_tache" method="post" action="todo-list.php?action=add&name=add_task">
                    <input id="form_date" type="datetime-local" name="date" required>
                    <input id="form_name" type="text" name="name" placeholder="tâche" required>
                    <input id="form_add" type="button" name="add" value="Ajouter la tâche">
                </form>

                <div class="msg">
                    <span id="success" class="alert alert-success alert-dismissible" role="alert" style="display: none;">

                    </span>
                    <span id="danger" class="alert alert-danger" role="alert" style="display: none;">

                    </span>
                </div>


                <div class="bloc_info"></div>

                <div id="table">

                </div>


                <?php
            }
            ?>



            <script>
                $(function() {
                    $('#table').load('table.php');

                    $("#form_add").on('click', function() {
                        let date = $('#form_date').val();
                        alert(date);
                        let name = $('#form_name').val();
                        if(name != '' && date != '') {
                            $.ajax({
                                url: "todo-list.php?action=add&name=" + name,
                                type: "POST",
                                data: {
                                    date: date,
                                    name: name
                                },
                                cache: false,
                                success: function() {
                                    $('#table').load('table.php');
                                    $("#success").show();
                                    $('#success').html("La tâche a été enregistrée avec succès");

                                },
                                error: function() {
                                    $('#danger').show();
                                    $('#danger').html("Une erreur est survenue lors de la création de la tâche");
                                }
                            });
                        } else {
                            $('#danger').html("Vous devez remplir tous les champs du formulaire");
                            $('#danger').show();
                        }
                    });
                });
            </script>
        </div>


</body>
</html>
