<?php
include "../utils/query.php";
include "../fragments/table.php";
include "../utils/hydration.php";
include "../fragments/utils.php";

loadJS('./pages/management.js');

cardHeader("Gestion du garage");

function createCard($title, $content, $button_id)
{
    echo "
    <div class='card' style='width: 18rem; margin: 10px;'>
        <div class='card-body'>
            <h5 class='card-title'>$title</h5>
            <p class='card-text'>$content</p>
            <a href='#' class='btn btn-primary' id='$button_id'>Ouvrir</a>
        </div>
    </div>
    ";
}
?>

<p>Choix de la section :</p>

<div style="display: flex; flex-direction: row; flex-wrap: wrap; justify-content: space-around;">
    <?php
    createCard("Statistiques", "Accès aux statistiques", "loadStats");
    createCard("Employés", "Gestion des employés du garage", "loadEmployees");
    createCard("Types de modèle", "Gestion des types de modèle de véhicule", "loadModelTypes");
    createCard("Interventions", "Gestion des interventions réalisables", "loadInterventions");
    createCard("Constructeurs", "Gestion des constructeurs", "loadManufacturers");
    createCard("Pièces", "Gestion des pièces disponibles du garage", "loadParts");
    ?>
</div>

<?php
cardFooter();
?>