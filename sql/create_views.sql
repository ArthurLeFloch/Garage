-- =====================
--   Database : Garage
-- =====================

\echo '================'
\echo '  CREATE VIEWS'
\echo '================'

-- To use as intern tables interfaces

-- ...

-- To simplify some specific usages

CREATE VIEW models_view AS
SELECT
	m.model_id,
	m.model_name,
	m.model_version,
	p1.part_name AS coolant_name,
	p2.part_name AS suspension_name,
	p3.part_name AS wheel_name,
	p4.part_name AS oil_name,
	p5.part_name AS fuel_name,
	m.manufacturer_id,
	m.model_type_id
FROM models m
LEFT JOIN parts p1 ON m.coolant_id = p1.part_id
LEFT JOIN parts p2 ON m.suspension_id = p2.part_id
LEFT JOIN parts p3 ON m.wheel_id = p3.part_id
LEFT JOIN parts p4 ON m.oil_id = p4.part_id
LEFT JOIN parts p5 ON m.fuel_id = p5.part_id;

CREATE VIEW model_types_view AS
SELECT 	model_type_id,
		model_type_name
FROM model_types;

CREATE VIEW maintenances_view AS
SELECT
    maintenances.maintenance_id,
    vehicle_id,
    planner_id,
    planned_start_date,
    planned_time_needed,
    maintenance_time_spent,
    maintenance_start_date,
    CASE WHEN is_finished THEN maintenance_end_date ELSE NULL END AS maintenance_end_date,
    maintenance_start_hour,
    CASE WHEN is_finished THEN maintenance_end_hour ELSE NULL END AS maintenance_end_hour,
    was_canceled,
    mileage_vehicle,
    total_price,
    is_finished,
    maintenance_note
FROM maintenances
LEFT JOIN (
	SELECT
		maintenance_id,
		MIN(TO_DATE(CAST (timeslots_dh.day_of_month AS VARCHAR(2))
					|| '/'
					|| CAST (timeslots_ym.month_number AS VARCHAR(2))
					|| '/'
					|| CAST (timeslots_ym.year_number AS VARCHAR(255)),
					'DD/MM/YYYY')
        ) AS maintenance_start_date,
		MAX(TO_DATE(CAST (timeslots_dh.day_of_month AS VARCHAR(2))
					|| '/'
					|| CAST (timeslots_ym.month_number AS VARCHAR(2))
					|| '/'
					|| CAST (timeslots_ym.year_number AS VARCHAR(255)),
					'DD/MM/YYYY')
        ) AS maintenance_end_date
	FROM employees_work_histories
	JOIN timeslots_ym USING (timeslot_ym_id)
	JOIN timeslots_dh USING (timeslot_dh_id)
	GROUP BY maintenance_id
) AS work_histories_dates
ON maintenances.maintenance_id = work_histories_dates.maintenance_id
LEFT JOIN (
	SELECT
        maintenance_id,
        MIN((SELECT start_hour
            FROM timeslots_dh
            WHERE timeslots_dh.timeslot_dh_id = employees_work_histories.timeslot_dh_id
            LIMIT 1
        )) AS maintenance_start_hour,
        MAX((SELECT start_hour + work_duration AS end_hour
            FROM timeslots_dh
            JOIN employees_work_histories e USING (timeslot_dh_id)
            JOIN work_durations USING (work_duration_id)
            WHERE timeslots_dh.timeslot_dh_id = employees_work_histories.timeslot_dh_id
            LIMIT 1
        )) AS maintenance_end_hour
    FROM employees_work_histories
    JOIN timeslots_ym USING (timeslot_ym_id)
    JOIN timeslots_dh USING (timeslot_dh_id)
    GROUP BY maintenance_id
) AS work_histories_hours
ON maintenances.maintenance_id = work_histories_hours.maintenance_id
LEFT JOIN (
    SELECT
        maintenance_id,
        SUM(work_duration) AS maintenance_time_spent
    FROM employees_work_histories
    JOIN work_durations USING (work_duration_id)
    GROUP BY maintenance_id
) AS work_histories_time_spent
ON maintenances.maintenance_id = work_histories_time_spent.maintenance_id;

CREATE VIEW prescriptions_view AS
SELECT
    prescription_id,
    vehicle_id,
    client_id,
    CONCAT(employee_first_name, ' ', employee_last_name) AS planner_name,
    STRING_AGG(intervention_name, ', ') AS "intervention_name(s)",
    to_do_before_date,
    model_name,
    CONCAT(client_first_name, ' ', client_last_name) AS client_name
FROM prescriptions
JOIN vehicles USING (vehicle_id)
JOIN clients USING (client_id)
JOIN models USING (model_id)
JOIN employees USING (employee_id)
JOIN (
    SELECT
        prescription_id,
        intervention_name
    FROM prescriptions_interventions
    JOIN interventions USING (intervention_id)
) AS interventions
USING (prescription_id)
GROUP BY prescription_id, vehicle_id, prescription_date, to_do_before_date, planner_name, model_name, client_name, client_id;


-- To give warnings about databases (1,n) rules not respected

-- to check for maintenances with is_finished set to true but that are not
-- linked to any employees_work_histories

CREATE VIEW warnings_finished_maintenances AS
SELECT maintenance_id
FROM maintenances
WHERE is_finished = TRUE
AND NOT EXISTS (
	SELECT 1
	FROM employees_work_histories
	WHERE maintenances.maintenance_id = employees_work_histories.maintenance_id
);

-- to check for clients that are not linked to any vehicles (not enforced anymore)

-- CREATE VIEW warnings_clients AS
-- SELECT client_id
-- FROM clients
-- WHERE NOT EXISTS (
-- 	SELECT 1
-- 	FROM vehicles
-- 	WHERE clients.client_id = vehicles.client_id
-- );

-- to check for recurrent_maintenances that are not linked to any
-- recurrent_maintenances_interventions

CREATE VIEW warnings_recurrent_maintenances_interventions AS
SELECT recurrent_maintenance_id
FROM recurrent_maintenances
WHERE NOT EXISTS (
	SELECT 1
	FROM recurrent_maintenances_interventions
	WHERE recurrent_maintenances.recurrent_maintenance_id = recurrent_maintenances_interventions.recurrent_maintenance_id
);

-- to check for extern_maintenances that are not linked to any
-- extern_maintenances_interventions

CREATE VIEW warnings_extern_maintenances_interventions AS
SELECT extern_maintenance_id
FROM extern_maintenances
WHERE NOT EXISTS (
	SELECT 1
	FROM extern_maintenances_interventions
	WHERE extern_maintenances.extern_maintenance_id = extern_maintenances_interventions.extern_maintenance_id
);


-- to check for maintenances that are not linked to any
-- maintenances_interventions

CREATE VIEW warnings_maintenances_interventions AS
SELECT maintenance_id
FROM maintenances
WHERE NOT EXISTS (
	SELECT 1
	FROM maintenances_interventions
	WHERE maintenances.maintenance_id = maintenances_interventions.maintenance_id
);

-- to check for prescriptions that are not linked to any
-- prescriptions_interventions

CREATE VIEW warnings_prescriptions_interventions AS
SELECT prescription_id
FROM prescriptions
WHERE NOT EXISTS (
	SELECT 1
	FROM prescriptions_interventions
	WHERE prescriptions.prescription_id = prescriptions_interventions.prescription_id
);

-- to check for recurrent_maintenances not linked to either of a model or a
-- model_type

-- CREATE VIEW warnings_recurrent_maintenances_models_model_types AS
-- SELECT recurrent_maintenance_id
-- FROM recurrent_maintenances
-- WHERE NOT EXISTS (
-- 	SELECT 1
-- 	FROM recurrent_maintenances_models
-- 	WHERE recurrent_maintenances.recurrent_maintenance_id = recurrent_maintenances_models.recurrent_maintenance_id
-- )
-- AND NOT EXISTS (
-- 	SELECT 1
-- 	FROM recurrent_maintenances_model_types
-- 	WHERE recurrent_maintenances.recurrent_maintenance_id = recurrent_maintenances_model_types.recurrent_maintenance_id
-- );

-- -- Check for overlapping maintenances on the same vehicle
-- CREATE VIEW overlapping_maintenances AS
-- SELECT
--     m1.maintenance_id AS maintenance_id_1,
--     m2.maintenance_id AS maintenance_id_2,
--     m1.vehicle_id AS vehicle_id,
--     m1.maintenance_start_date AS start_date_1,
--     m1.maintenance_start_hour AS start_hour_1,
--     m2.maintenance_start_date AS start_date_2,
--     m2.maintenance_start_hour AS start_hour_2,
--     m1.maintenance_end_date AS end_date_1,
--     m1.maintenance_end_hour AS end_hour_1,
--     m2.maintenance_end_date AS end_date_2,
--     m2.maintenance_end_hour AS end_hour_2
-- FROM maintenances_view m1
-- JOIN maintenances_view m2
-- ON m1.vehicle_id = m2.vehicle_id
-- AND m1.maintenance_id <> m2.maintenance_id
-- WHERE (
--     (m1.maintenance_start_date < m2.maintenance_start_date AND m1.maintenance_end_date > m2.maintenance_start_date)
--     OR (m1.maintenance_start_date = m2.maintenance_start_date AND m1.maintenance_start_hour < m2.maintenance_start_hour AND m1.maintenance_end_date > m2.maintenance_start_date)
--     OR (m1.maintenance_start_date = m2.maintenance_start_date AND m1.maintenance_start_hour = m2.maintenance_start_hour)
-- )
-- OR (
--     (m1.maintenance_start_date > m2.maintenance_start_date AND m1.maintenance_start_date < m2.maintenance_end_date)
--     OR (m1.maintenance_start_date = m2.maintenance_end_date AND m1.maintenance_start_hour < m2.maintenance_end_hour)
-- );


-- compile all those warnings into one comprehensive view 1 column per warning

CREATE VIEW warnings AS
SELECT
	'Finished maintenances without any work history' AS warning_type,
	'maintenance_id 1 and 2' AS field_id,
	maintenance_id AS id
FROM warnings_finished_maintenances
-- UNION
-- SELECT
-- 	'Client without any vehicle' AS warning_type,
-- 	'client_id' AS field_id,
-- 	client_id AS id
-- FROM warnings_clients
-- UNION
-- SELECT
-- 	'Overlapping maintenances real times' AS warning_type,
-- 	'maintenance_id' AS field_id,
-- 	CONCAT(maintenance_id_1::text, ' and ', maintenance_id_2::text) AS id
-- FROM overlapping_maintenances
UNION
SELECT
	'recurrent maintenance without any interventions' AS warning_type,
	'recurrent_maintenance_id' AS field_id,
	recurrent_maintenance_id AS id
FROM warnings_recurrent_maintenances_interventions
UNION
SELECT
	'Extern maintenance without any interventions' AS warning_type,
	'extern_maintenance_id' AS field_id,
	extern_maintenance_id AS id
FROM warnings_extern_maintenances_interventions
UNION
SELECT
	'Maintenance without any interventions' AS warning_type,
	'maintenance_id' AS field_id,
	maintenance_id AS id
FROM warnings_maintenances_interventions
UNION
SELECT
	'Prescription without any interventions' AS warning_type,
	'prescription_id' AS field_id,
	prescription_id AS id
FROM warnings_prescriptions_interventions
-- UNION
-- SELECT
-- 	'recurrent maintenance not linked to any model or model_type' AS warning_type,
-- 	'recurrent_maintenance_id' AS field_id,
-- 	recurrent_maintenance_id AS id
-- FROM warnings_recurrent_maintenances_models_model_types
ORDER BY field_id, id;

