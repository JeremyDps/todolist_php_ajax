<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=todo-list;charset=utf8', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$query = $pdo->query("SELECT * FROM tasks ORDER BY date");
$query->execute();

$name = "";

?>

<div class="wrapper">
    <br>
    <div class="message-task">
        <div>
            <span id="message-task"></span>
        </div><br>
    </div>
    <div class="wrapper-line">

    <?php
        while($datas = $query->fetch()) {
            $name = $datas['name'];

    ?>
        <!-- Liste des tâches avec leurs membres
             2 requêtes => recupérer les tâches
                        => recupérer leurs mebres -->

        <div class="task-desc">
            <h2><?php echo $datas['name']; ?></h2>
            <p>Dead Line : <?php echo $datas['date']; ?></p>

            <p> Avec 
            <?php 

                $users = $pdo->prepare("SELECT users.firstname, users.lastname FROM users 
                                        INNER JOIN users_tasks ON users_tasks.user_id = users.id
                                        WHERE users_tasks.task_id = ? ORDER BY users.lastname");

                $users->bindParam(1, $datas['id']);
                $users->execute();

                

                while($datas_user = $users->fetch()) {
                    echo $datas_user['firstname']. " " . $datas_user['lastname'] . '<br>';
                }  ?>

            </p>
            
            <div class="task-buttons">
                <button onclick="loadUpdate(<?php echo $datas['id']; ?>)" type="button" id="update-task" name="<?php echo $datas['id']; ?>">Modifier</button>
                <button onclick="deleteTask(<?php echo $datas['id']; ?>, '<?php echo $name; ?>')" type="button" id="delete-task" name="delete-task">Supprimer</button>
            </div>           
        </div>

        <?php  }  ?>

        
    </div>
</div>


