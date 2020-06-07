<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=todo-list;charset=utf8', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$all_users = $pdo->query("SELECT * FROM users ORDER BY lastname");

$all_users->execute();

?>

<!-- Formulaire pour ajoutr une nouvelle tâche
     Il faut récupérer toutes les personnes enregistrées -->

<div class="form-add">
    <div><span id="add-task-message"></span></div><br>
    <h2>Ajouter une nouvelle tâche</h2>
    <form>
        <div class="form-add-header">
            <div>
                <label for="taskName">Nom de la tâche :</label> 
                <input type="text" id="taskName" name="taskName">
            </div>

            <div>
                <label for="deadline">DeadLine :</label> 
                <input type="datetime-local" id="deadline" name="deadline">
            </div>            
        </div>

        <div class="form-add-checkbox">
            <h3>Personnes concernées</h3>
            <div class="form-add-checkbox-container">
                <?php

                while($datas = $all_users->fetch()) {

                ?>
                <div>
                    <input type="checkbox" id="<?php echo $datas['id']; ?>" name="check">
                    <label for="check"><?php echo $datas['firstname'] . " " . $datas['lastname']; ?></label>
                </div> 
                
                <?php  }  ?>

            </div>
              
        </div>

        <button type="button" onclick="saveData()" name="create-task" id="create-task">Ajouter</button>
        
    </form>
</div>