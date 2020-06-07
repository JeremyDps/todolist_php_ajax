/*function supp (id_tache) {
    console.log("valeur : " + id_tache);
    let modal = document.getElementById('supprimer');
    let id = document.getElementsByClassName('supp');

    for(let i = 0; i < id.length; i++) {
        let value = id[i].getAttribute('data-value');

        if(typeof parseInt(value) === "number" && parseInt(value) === id_tache) {

            modal.setAttribute('href', "todo-list.php?action=delete&id=" + value);
        }
    }
}

function put(id_tache) {
    let modal = document.getElementById('update_form');
    let id = document.getElementsByClassName('put');

    for(let i = 0; i < id.length; i++) {
        let value = id[i].getAttribute('data-value');

        if(typeof parseInt(value) === "number" && parseInt(value) === id_tache) {
            modal.setAttribute('action', 'todo-list.php?action=update&id=' + value);
        }
    }
}*/

