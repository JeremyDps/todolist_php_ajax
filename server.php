<?php
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=todo-list;charset=utf8', 'root', '');
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    //Si action est dans l'url alors $action prend sa valeur, sinon il prend une chaîne vide
    $action = isset($_GET['action']) ? $_GET['action'] : "";

    $callback = isset($_GET['callback']) ? $_GET['callback'] : "";

    if($action === 'saveTask') {
        if($callback === "callAddTask") {
            $name = $_POST['name'];
            $date = $_POST['deadline'];

            $query = $pdo->prepare("INSERT INTO tasks VALUES (default, ?, ?)");

            $query->bindParam(1, $date);
            $query->bindParam(2, $name);

            $query->execute();

        } else {
            echo "erreur dans l'url";
        }


    // modifier une tâche avec suppression des membres
    } else if($action === "UpdateTask") {
        if($callback === "callUpdateTask") {
            $name = $_POST['name'];
            $date = $_POST['deadline'];
            $id = $_POST['id'];

            $query = $pdo->prepare("UPDATE tasks SET date = ?, name = ? WHERE id = ?");

            $delete_users = $pdo->prepare("DELETE FROM users_tasks WHERE task_id = ?");

            $query->bindParam(1, $date);
            $query->bindParam(2, $name);
            $query->bindParam(3, $id);

            $delete_users->bindParam(1, $id);

            $query->execute();
            $delete_users->execute();
        } else {
            echo "Erreur dans l'url";
        }

    // 2e étape de la modification : ajouter les membres cochés
    } else if ($action === "user_task_update") {
        if($callback === "callUpdateTaskUser") {

            $task_user = $pdo->prepare("INSERT INTO users_tasks VALUES (?,?)");
            $task_user->bindParam(1, $_POST['task']);
            $task_user->bindParam(2, $_POST['user']);

            $task_user->execute();
            
        }

        // suppression de tâche
    } else if($action === "deleteTask") {
        if($callback === "callDeleteTask") {

            $task_id = $_GET['task'];

            $query = $pdo->prepare("DELETE FROM users_tasks WHERE task_id = ?");
            $query2 = $pdo->prepare("DELETE FROM tasks WHERE id = ?");

            $query->bindParam(1, $task_id);
            $query2->bindParam(1, $task_id);

            $query->execute();
            $query2->execute();
        } else {
            echo "Erreur dans l'url";
        }

        // enregistrer les membres d'une tâche lors de sa création
        // il faut récupérer d'abord l'id de la tâche précédemment créee
    } else if($action === "user_task") {
        if($callback === "callSaveTask") {
            $task_id = $pdo->query("SELECT MAX(id) from tasks");
            $task_id->execute();
            $data = $task_id->fetch();

            $task_user = $pdo->prepare("INSERT INTO users_tasks VALUES (?,?)");
            $task_user->bindParam(1, $data['MAX(id)']);
            $task_user->bindParam(2, $_POST['user']);

            $task_user->execute();
        } else {
            echo "Erreur dans l'url";
        }


        // supprimer une personne
    } else if($action === "deleteUser") {
        if ($callback === "callDeleteUser") {
            $user_id = $_GET['user'];

            $query = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $user_task = $pdo->prepare("DELETE FROM users_tasks WHERE user_id = ?");

            $query->bindParam(1, $user_id);
            $user_task->bindParam(1, $user_id);
            $query->execute();
            $user_task->execute();
        } else {
            echo "erreur dans l'URL";
        }

        // ajouter une nouvelle personne
    } else if($action === "addUser") {
        if($callback === "callSaveUser") {
            $firstname = $_GET['firstname'];
            $lastname = $_GET['lastname'];

            $query = $pdo->prepare("INSERT INTO users VALUES (default, ?, ?)");

            $query->bindParam(1, $lastname);
            $query->bindParam(2, $firstname);

            $query->execute();
        } else {
            echo "erreur dans l'URL";
        }
    }        

?>
