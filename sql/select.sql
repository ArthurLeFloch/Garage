-- =====================
--   Database : Garage
-- =====================

\echo =====================
\echo ' DATA CONSULTATION  '
\echo =====================

-- List of maintenances in the upcoming period
-- usage: SELECT * FROM upcoming_maintenances('2 months');
CREATE OR REPLACE FUNCTION upcoming_maintenances(time_interval INTERVAL)
RETURNS TABLE (
    maintenance_id                  INT,
	planner_name					TEXT,
	vehicle_name					TEXT,
	planned_start_date				DATE,
	planned_time_needed				INTERVAL,
	was_canceled					BOOLEAN,
	maintenance_note				VARCHAR(255)
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT
        maintenances_view.maintenance_id,
        CONCAT(employees.employee_first_name, ' ', employees.employee_last_name) AS planner_name,
        CONCAT(manufacturers.manufacturer_name, ' ', models.model_name) AS vehicle_name,
        maintenances_view.planned_start_date,
        maintenances_view.planned_time_needed,
        maintenances_view.was_canceled,
        maintenances_view.maintenance_note
    FROM maintenances_view
    INNER JOIN vehicles USING (vehicle_id)
    INNER JOIN models USING (model_id)
    INNER JOIN manufacturers USING (manufacturer_id)
    INNER JOIN employees ON maintenances_view.planner_id = employees.employee_id
    WHERE maintenances_view.planned_start_date BETWEEN NOW() AND NOW() + time_interval
    AND maintenances_view.maintenance_start_date IS NULL;
END;
$$;


-- List of clients with the number of vehicles that is currently under maintenance by the garage
CREATE OR REPLACE FUNCTION vehicles_under_maintenance_currently()
RETURNS TABLE (
    maintenance_id                  INT,
    client_name                     TEXT,
    vehicle_name                    TEXT,
    plate_number            VARCHAR(255)
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT
        maintenances_view.maintenance_id,
        CONCAT(clients.client_first_name, ' ', clients.client_last_name) AS client_name,
        CONCAT(manufacturers.manufacturer_name, ' ', models.model_name) AS vehicle_name,
        vehicles.plate_number
    FROM clients
    INNER JOIN vehicles USING (client_id)
    INNER JOIN models USING (model_id)
    INNER JOIN manufacturers USING (manufacturer_id)
    INNER JOIN maintenances_view USING (vehicle_id)
    WHERE   maintenances_view.maintenance_start_date < NOW()
            AND maintenances_view.is_finished = FALSE
            AND maintenances_view.was_canceled = FALSE
    GROUP BY clients.client_id, manufacturers.manufacturer_name, models.model_name, models.model_version, vehicles.plate_number, maintenances_view.maintenance_id;
END;
$$;


-- List of clients with the number of vehicles that was under maintenance by the garage at some point in the past
CREATE OR REPLACE FUNCTION vehicles_under_maintenance_past()
RETURNS TABLE (
    client_first_name                   VARCHAR(255),
    client_last_name                    VARCHAR(255),
    number_of_vehicles_under_maintenance BIGINT
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT
        clients.client_first_name,
        clients.client_last_name,
        COUNT(maintenances_view.maintenance_id) AS number_of_vehicles_under_maintenance
    FROM clients
    INNER JOIN vehicles USING (client_id)
    LEFT JOIN maintenances_view ON (vehicles.vehicle_id = maintenances_view.vehicle_id
        AND maintenances_view.maintenance_start_date < NOW())
    GROUP BY clients.client_id;
END;
$$;

-- OLD VERSION
-- \echo List of clients with the number of vehicles given in maintenance.
-- SELECT CONCAT(client_first_name, ' ', client_last_name) AS client, COUNT(*) FROM clients
-- 	INNER JOIN vehicles USING (client_id)
-- 	INNER JOIN maintenances USING (vehicle_id)
-- 	GROUP BY client_id
-- 	HAVING COUNT(*) > 0
-- ;


-- List of vehicle models that were given in maintenance in the current year
CREATE OR REPLACE FUNCTION models_given_in_maintenance_current_year()
RETURNS TABLE (
    manufacturer_name                   VARCHAR(255),
    model_type_name                     VARCHAR(255),
    model_name                          VARCHAR(255),
    model_version                       VARCHAR(255)
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT  manufacturers.manufacturer_name,
            model_types.model_type_name,
            models_view.model_name,
            models_view.model_version
    FROM models_view
    INNER JOIN vehicles USING (model_id)
    INNER JOIN maintenances USING (vehicle_id)
    INNER JOIN model_types USING (model_type_id)
    INNER JOIN manufacturers USING (manufacturer_id)
    WHERE planned_start_date BETWEEN DATE_TRUNC('year', NOW()) AND NOW();
END;
$$;


\echo ===================
\echo '  DATA STATISTICS  '
\echo ===================

-- List of clients, with the total amount of money they spent in maintenance, (ordered by the amount of money spent)
CREATE OR REPLACE FUNCTION clients_total_amount_spent_in_maintenance()
RETURNS TABLE (
    client_name                         TEXT,
    total_amount_spent_in_maintenance   BIGINT
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT
        CONCAT(client_first_name, ' ', client_last_name) AS client_name,
        SUM(maintenances.total_price) AS total_amount_spent_in_maintenances
    FROM clients
    INNER JOIN vehicles USING (client_id)
    INNER JOIN maintenances USING (vehicle_id)
    GROUP BY clients.client_id
    HAVING COUNT(*) > 0
    ORDER BY SUM(total_price) DESC;
END;
$$;


-- Amount of hours of maintenance per month until given date.
CREATE OR REPLACE FUNCTION maintenance_hours_per_month()
RETURNS TABLE (
    month_year                          TEXT,
    total_hours                         INTERVAL
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT  CONCAT(timeslots_ym.month_number, '/', timeslots_ym.year_number) AS month_number,
            SUM(work_durations.work_duration) AS total
    FROM employees_work_histories
    INNER JOIN timeslots_ym USING (timeslot_ym_id)
    INNER JOIN work_durations USING (work_duration_id)
    GROUP BY timeslots_ym.month_number, timeslots_ym.year_number
    ORDER BY timeslots_ym.year_number DESC,
             timeslots_ym.month_number DESC,
                SUM(work_durations.work_duration) DESC;
END;
$$;


-- OLD VERSION
-- SELECT  SUM(planned_time_needed) AS total,
--         DATE_TRUNC('month', planned_start_date) AS month
-- FROM maintenances
-- WHERE planned_start_date BETWEEN DATE_TRUNC('year', NOW()) AND NOW()
-- GROUP BY DATE_TRUNC('month', planned_start_date)
-- ORDER BY SUM(planned_time_needed) DESC;



-- List of the most frequent interventions per model type.
CREATE OR REPLACE FUNCTION most_frequent_interventions_per_model_global()
RETURNS TABLE (
    model_type_name                          VARCHAR(255),
    model_name                          TEXT,
    model_version                       VARCHAR(255),
    most_frequent_intervention          TEXT,
    amount                              BIGINT
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT  model_types.model_type_name,
            CONCAT(manufacturers.manufacturer_name, ' ', models.model_name) AS model_name,
            models.model_version,
            MAX(interventions.intervention_name) AS most_frequent_intervention,
            COUNT(maintenances.maintenance_id) AS amount
    FROM model_types
    INNER JOIN models USING (model_type_id)
    INNER JOIN vehicles USING (model_id)
    INNER JOIN manufacturers USING (manufacturer_id)
    INNER JOIN maintenances USING (vehicle_id)
    INNER JOIN maintenances_interventions USING (maintenance_id)
    INNER JOIN interventions USING (intervention_id)
    GROUP BY models.model_version, models.model_name, manufacturers.manufacturer_name, interventions.intervention_name, model_types.model_type_name
    ORDER BY COUNT(maintenance_id) DESC;
END;
$$;

CREATE OR REPLACE FUNCTION most_frequent_interventions_per_model()
RETURNS TABLE (
    model_name                          TEXT,
    most_frequent_intervention          TEXT,
    amount                              BIGINT
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT  CONCAT(manufacturers.manufacturer_name, ' ', models.model_name) AS model_name,
            MAX(interventions.intervention_name) AS most_frequent_intervention,
            COUNT(maintenances.maintenance_id) AS amount
    FROM models
    INNER JOIN vehicles USING (model_id)
    INNER JOIN manufacturers USING (manufacturer_id)
    INNER JOIN maintenances USING (vehicle_id)
    INNER JOIN maintenances_interventions USING (maintenance_id)
    INNER JOIN interventions USING (intervention_id)
    GROUP BY models.model_name, manufacturers.manufacturer_name, interventions.intervention_name
    ORDER BY COUNT(maintenance_id) DESC;
END;
$$;

CREATE OR REPLACE FUNCTION most_frequent_interventions_per_model_version()
RETURNS TABLE (
    model_name_version                  TEXT,
    most_frequent_intervention          TEXT,
    amount                              BIGINT
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT  CONCAT(manufacturers.manufacturer_name, ' ', models.model_name) AS model_name_version,
            MAX(interventions.intervention_name) AS most_frequent_intervention,
            COUNT(maintenances.maintenance_id) AS amount FROM model_types_view
    INNER JOIN models USING (model_type_id)
    INNER JOIN vehicles USING (model_id)
    INNER JOIN manufacturers USING (manufacturer_id)
    INNER JOIN maintenances USING (vehicle_id)
    INNER JOIN maintenances_interventions USING (maintenance_id)
    INNER JOIN interventions USING (intervention_id)
    GROUP BY models.model_name, manufacturers.manufacturer_name, interventions.intervention_name
    ORDER BY COUNT(maintenance_id) DESC;
END;
$$;

CREATE OR REPLACE FUNCTION most_frequent_interventions_per_model_type()
RETURNS TABLE (
    model_type_name                          VARCHAR(255),
    most_frequent_intervention          TEXT,
    amount                              BIGINT
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT  model_types.model_type_name,
            MAX(interventions.intervention_name) AS most_frequent_intervention,
            COUNT(maintenances.maintenance_id) AS amount
    FROM model_types
    INNER JOIN models USING (model_type_id)
    INNER JOIN vehicles USING (model_id)
    INNER JOIN maintenances USING (vehicle_id)
    INNER JOIN maintenances_interventions USING (maintenance_id)
    INNER JOIN interventions USING (intervention_id)
    GROUP BY model_types.model_type_name, interventions.intervention_name
    ORDER BY COUNT(maintenance_id) DESC;
END;
$$;

-- Uncomment to see the difference between the 4 functions
-- SELECT * FROM most_frequent_interventions_per_model_global();
-- SELECT * FROM most_frequent_interventions_per_model_version();
-- SELECT * FROM most_frequent_interventions_per_model_type();
-- SELECT * FROM most_frequent_interventions_per_model();

-- SELECT * FROM maintenances;