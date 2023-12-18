<!-- Required GET parameters: intervention_id -->

<?php
include "../utils/query.php";
include "../utils/parse.php";
include "../utils/hydration.php";
include "../fragments/utils.php";
include "../utils/field_names.php";

loadJS('./pages/intervention_update.js');

cardHeader("Modification du type d'intervention");
?>

<div class="alert alert-primary" role="alert">
    Si le modèle est renseigné, <b>les données sur un modèle précis prévalent</b> sur les données du type de modèle.
</div>

<form class="row g-3">
    <div class="col-md-6">
        <label for="intervention_name" class="form-label">Nom d'intervention</label>
        <?php
        $intervention_id = parseGet('intervention_id');
        $request = "SELECT intervention_name FROM interventions WHERE intervention_id = $1;";
        $res = query($request, array($intervention_id));
        $intervention_name = pg_fetch_array($res)[0];
        echo "<input class='form-control' type='text' id='intervention_name' value='" . htmlspecialchars($intervention_name) . "'>";
        ?>
    </div>
    <?php

    $request = "SELECT models.model_id, manufacturer_name, model_name, model_version, part_name AS \"fuel_name\", estimated_price FROM models
                INNER JOIN model_types USING (model_type_id)
                INNER JOIN parts ON (fuel_id = parts.part_id)
                INNER JOIN manufacturers USING (manufacturer_id)
                LEFT JOIN models_interventions_prices ON (models_interventions_prices.model_id = models.model_id AND intervention_id = $1)
                ORDER BY manufacturer_name, model_name, model_version;";
    $res = query($request, array($intervention_id));

    echo "<p>Grille des tarifs par modèle :</p>";

    echo "<table class='table table-striped table-bordered' id='m-list' style='overflow-x: auto; display: block;'>";

    echo "<thead><tr>";
    for ($i = 1; $i < pg_num_fields($res) - 1; $i++) {
        echo "<th scope='col'>" . convert(pg_field_name($res, $i)) . "</th>";
    }
    echo "<th scope='col'>Prix</th>";
    echo "</tr></thead>";

    echo "<tbody table-group-divider>";
    while ($interventions = pg_fetch_array($res)) {
        for ($i = 1; $i < pg_num_fields($res) - 1; $i++) {
            echo '<td>' . htmlspecialchars($interventions[$i]) . '</td>';
        }
        echo '<td><input class="form-control" type="number" min="0" step="1" id="m-' . htmlspecialchars($interventions[0]) . '" value="' . htmlspecialchars($interventions[5]) . '"></td>';
        echo '</tr>' . "\n";
    }
    echo "</tbody>";
    echo "</table>";


    $request = "SELECT model_types.model_type_id, model_type_name, estimated_price FROM model_types
                LEFT JOIN model_types_interventions_prices ON (model_types_interventions_prices.model_type_id = model_types.model_type_id AND intervention_id = $1)
                ORDER BY model_type_name;";
    $res = query($request, array($intervention_id));

    echo "<p>Grille des tarifs par types de modèle :</p>";

    echo "<table class='table table-striped table-bordered' id='mtype-list' style='overflow-x: auto; display: block;'>";

    echo "<thead><tr>";
    for ($i = 1; $i < pg_num_fields($res) - 1; $i++) {
        echo "<th scope='col'>" . convert(pg_field_name($res, $i)) . "</th>";
    }
    echo "<th scope='col'>Prix</th>";
    echo "</tr></thead>";

    echo "<tbody table-group-divider>";
    while ($interventions = pg_fetch_array($res)) {
        for ($i = 1; $i < pg_num_fields($res) - 1; $i++) {
            echo '<td>' . htmlspecialchars($interventions[$i]) . '</td>';
        }
        echo '<td><input class="form-control" type="number" min="0" step="1" id="mtype-' . htmlspecialchars($interventions[0]) . '" value="' . htmlspecialchars($interventions[2]) . '"></td>';
        echo '</tr>' . "\n";
    }
    echo "</tbody>";
    echo "</table>";
    ?>
    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="updateIntervention">Valider</button>
    </div>
</form>

<?php
cardFooter();
?>