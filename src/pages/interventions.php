<?php
include "../utils/query.php";
include "../utils/hydration.php";
include "../fragments/utils.php";
include "../fragments/table.php";

loadJS('./pages/interventions.js');


cardHeader("Interventions");

echo "<p>Table des interventions :</p>";

$request = "SELECT intervention_id, intervention_name FROM interventions
			ORDER BY intervention_id;";
$res = query($request);

$table = new Table("intervention-list");

$table->set_hidden_fields(array(0));

$table->add_button("intervention-details", "Détails", array(0));
$table->add_button("intervention-update", "Modifier", array(0));
$table->add_button("intervention-delete", "Supprimer", array(0), false);

$table->show($res);

?>

<a href="#" class="btn btn-primary" id="newIntervention">
	Nouveau type d'intervention
</a>

<?php
cardFooter();
?>