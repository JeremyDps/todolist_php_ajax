<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=todo-list;charset=utf8', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$all_users = $pdo->query("SELECT * FROM users ORDER BY lastname");

$all_users->execute();

$firstname = '';
$lastname = '';

?>

<!-- Liste des personnes en base avec possbilités de les supprimer et d'en rrajouter d'autres -->

<div class="bloc-persons">
    <div><span class="message-person"></span></div><br>
    <h2>Personnes enregistrées</h2>
    <div class="list-persons">

<?php

while($datas = $all_users->fetch()){

    $firstname = $datas['firstname'];
    $lastname = $datas['lastname'];

?>

<div>
    <p><?php echo $datas['firstname'] . " " . $datas['lastname']; ?></p>
    <button onclick="deleteUser(<?php echo $datas['id']; ?>, '<?php echo $firstname; ?>', '<?php echo $lastname; ?>')" type="button" name="delete-person-1" id="delete-person-1">Supprimer</button>
</div>

<?php  }  ?>

    </div>
    <button type="button" id="add-person" onclick="savePerson()">Ajouter une nouvelle personne</button> 
</div>