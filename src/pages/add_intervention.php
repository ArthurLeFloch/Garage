<?php
include "../utils/query.php";
include "../utils/parse.php";
include "../utils/hydration.php";
include "../utils/field_names.php";
include "../fragments/utils.php";

loadJS("./pages/add_intervention.js");

cardHeader("Ajout d'un type d'intervention");
?>

<div class="alert alert-primary" role="alert">
    Si le modèle est renseigné, <b>les données sur un modèle précis prévalent</b> sur les données du type de modèle.
</div>

<form class="row g-3">
    <div class="col-md-6">
        <label for="intervention_name" class="form-label">Nom d'intervention</label>
        <input class="form-control" type="text" id="intervention_name">
    </div>
    <?php
    $request = "SELECT model_id, manufacturer_name, model_name, model_version, part_name AS \"fuel_name\" FROM models
                INNER JOIN manufacturers USING (manufacturer_id)
                JOIN parts ON (fuel_id = parts.part_id)
                ORDER BY manufacturer_name, model_name, model_version;";
    $res = query($request);

    echo "<p>Grille des tarifs par modèle :</p>";

    echo "<table class='table table-striped table-bordered' id='m-list' style='overflow-x: auto; display: block;'>";

    echo "<thead><tr>";
    for ($i = 1; $i < pg_num_fields($res); $i++) {
        echo "<th scope='col'>" . convert(pg_field_name($res, $i)) . "</th>";
    }
    echo "<th scope='col'>Prix</th>";
    echo "</tr></thead>";

    echo "<tbody table-group-divider>";
    while ($interventions = pg_fetch_array($res)) {
        for ($i = 1; $i < pg_num_fields($res); $i++) {
            echo '<td>' . htmlspecialchars($interventions[$i]) . '</td>';
        }
        echo '<td><input class="form-control" type="number" min="0" step="1" id="m-' . htmlspecialchars($interventions[0]) . '"></td>';
        echo '</tr>' . "\n";
    }
    echo "</tbody>";
    echo "</table>";


    $request = "SELECT model_type_id, model_type_name FROM model_types
                ORDER BY model_type_name;";
    $res = query($request);

    echo "<p>Grille des tarifs par types de modèle :</p>";

    echo "<table class='table table-striped table-bordered' id='mtype-list' style='overflow-x: auto; display: block;'>";

    echo "<thead><tr>";
    for ($i = 1; $i < pg_num_fields($res); $i++) {
        echo "<th scope='col'>" . convert(pg_field_name($res, $i)) . "</th>";
    }
    echo "<th scope='col'>Prix</th>";
    echo "</tr></thead>";

    echo "<tbody table-group-divider>";
    while ($interventions = pg_fetch_array($res)) {
        for ($i = 1; $i < pg_num_fields($res); $i++) {
            echo '<td>' . htmlspecialchars($interventions[$i]) . '</td>';
        }
        echo '<td><input class="form-control" type="number" min="0" step="1" id="mtype-' . htmlspecialchars($interventions[0]) . '"></td>';
        echo '</tr>' . "\n";
    }
    echo "</tbody>";
    echo "</table>";
    ?>
    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="addIntervention">Valider</button>
    </div>
</form>

<?php
cardFooter();
?>