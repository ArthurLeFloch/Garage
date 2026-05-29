<?php

$fields = [
    "planned_start_date" => 'Date de début prévue',
    'manufacturer_name' => 'Constructeur',
    'model_name' => 'Modèle',
    'model_version' => 'Version',
    'model_type_name' => 'Type de modèle',
    'most_frequent_intervention' => 'Intervention la plus fréquente',
    'amount' => 'Nombre',
    'total_price' => 'Prix total',
    'estimated_price' => 'Prix estimé',
    'work_date' => 'Date de travail',
    'start_time' => 'Heure de début',
    'end_time' => 'Heure de fin',
    'month_year' => 'Mois et année',
    'total_hours' => 'Nombre d’heures',
    'client_name' => 'Nom du client',
    'total_amount_spent_in_maintenance' => 'Montant total dépensé en maintenances',
    'vehicle_name' => 'Nom du véhicule',
    'plate_number' => 'Plaque d’immatriculation',
    'planner_name' => 'Planificateur',
    'planned_time_needed' => 'Temps prévu',
    'was_canceled' => 'À été annulée',
    'maintenance_note' => 'Note du personnel',
    'client_first_name' => 'Prénom',
    'client_last_name' => 'Nom',
    'client_address' => 'Adresse',
    'client_email' => 'Email',
    'client_mobile' => 'Téléphone',
    'employee_first_name' => 'Prénom',
    'employee_last_name' => 'Nom',
    'employee_address' => 'Adresse',
    'employee_email' => 'Email',
    'employee_mobile' => 'Téléphone',
    'intervention_name' => 'Intervention',
    'part_reference' => 'Référence',
    'part_name' => 'Nom',
    'part_type_name' => 'Type de pièce',
    'unitary_price' => 'Prix unitaire',
    'number_of_parts' => 'Nombre de pièces',
    'intervention_name(s)' => 'Intervention(s)',
    'is_finished' => 'Est terminée',
    'mileage_repeat' => 'Kilomètres après dernière maintenance',
    'days_after_last_maintenance' => 'Jours après dernière maintenance',
    'fuel_name' => 'Carburant',
    'mileage_vehicle' => 'Kilométrage du véhicule',
    'maintenance_start_date' => 'Date de début',
    'maintenance_time_spent' => 'Temps passé',
    'maintenance_end_date' => 'Date de fin',
    'extern_garage_name' => 'Garage externe',
    'extern_start_date' => 'Date de début',
    'extern_end_date' => 'Date de fin',
    'to_do_before_date' => 'À faire avant le',
    'start_ownership_date' => 'Date de début de possession',
    'end_ownership_date' => 'Date de fin de possession connue',
    'prescription_date' => 'Prescrit le',

    // Additions for error messages
    'surname' => 'Nom',
    'name' => 'Prénom',
    'address' => 'Adresse',
    'email' => 'Email',
    'mobile' => 'Téléphone',
    'garage_name' => 'Nom du garage',
    'start_date' => 'Date de début',
    'end_date' => 'Date de fin',
    'mileage' => 'Kilométrage',
    'status' => 'Status',
    'notes' => 'Notes',
    'fuel_type' => 'Carburant',
    'coolant_type' => 'Liquide de refroidissement',
    'suspesion_type' => 'Suspension',
    'wheel_type' => 'Roue',
    'oil_type' => 'Huile',
    'planner' => 'Planificateur',
    'days' => 'Jours',
    'vin' => 'Numéro VIN',
    'circulation_date' => 'Date de mise en circulation',
    'duration' => 'Durée',
];

$fields;

function convert($field)
{
    global $fields;
    if (array_key_exists($field, $fields)) {
        return $fields[$field];
    }
    return $field;
}

?>