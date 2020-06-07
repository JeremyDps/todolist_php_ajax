<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=todo-list;charset=utf8', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

function isInTab($tab, $id) {
    for($i = 0; $i < sizeof($tab); $i++) {
        if ($tab[$i][0] == $id) {
            return true;
        }
    }

    return false;
}

$date = "";

$task = $pdo->prepare("SELECT * FROM tasks WHERE id=?");
$task_persons = $pdo->prepare("SELECT users.id from users 
                          INNER JOIN users_tasks on users_tasks.user_id = users.id
                          WHERE users_tasks.task_id = ?");
$persons = $pdo->query("SELECT * FROM users ORDER BY lastname");

$task->bindParam(1, $_GET['task']);
$task->execute();

$task_persons->bindParam(1, $_GET['task']);
$task_persons->execute();

$task_persons_datas = $task_persons->fetchAll();

$persons->execute();


$task_datas = $task->fetch();

$date = $task_datas['date'];
$date = explode(" ", $date);

$new_date = $date[0] . "T" . $date[1];


?>

<!--  forumulaire de modification d'une tâche
      identique que celui pour ajouter 
      sauf qu'ici on récupère les données de la tâche -->

<div class="form-add">
    <div><span id="message-update"></span></div><br>
    <h2>Modifier une tâche</h2>
    <form>
        <div class="form-add-header">
            <div>
                <label for="name">Nom de la tâche :</label> 
                <input type="text" id="update-name" name="name" value="<?php echo $task_datas['name']; ?>">
            </div>

            <div>
                <label for="deadline">DeadLine :</label> 
                <input type="datetime-local" id="update-deadline" name="deadline" value="<?php echo $new_date; ?>">
            </div>            
        </div>

        <div class="form-add-checkbox">
            <h3>Personnes concernées</h3>
            <div class="form-add-checkbox-container">

            <?php

                while($persons_datas = $persons->fetch()) {

            ?>
                <div>
                    <input type="checkbox" id="<?php echo $persons_datas['id']; ?>" name="check" 
                        <?php
                            if(isInTab($task_persons_datas, $persons_datas['id'])) {
                                echo "checked";
                            }
                        ?> 
                    >
                    <label for="check"><?php echo $persons_datas['firstname'] . " " . $persons_datas['lastname']; ?></label>
                </div> 
                 

                <?php  }  ?>
            </div>
              
        </div>

        <button onclick="updateTask(<?php echo $task_datas['id']; ?>)" type="button" name="create-task" id="create-task">Valider les modifications</button>
        
    </form>
</div>