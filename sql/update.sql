-- =====================
--   Database : Garage
-- =====================

\echo '================================='
\echo '  CREATE PROCEDURES FOR UPDATES'
\echo '================================='

CREATE OR REPLACE PROCEDURE update_client (
	update_client_id INT,
	update_client_first_name VARCHAR(255),
	update_client_last_name VARCHAR(255),
	update_client_address VARCHAR(255),
	update_client_email VARCHAR(255),
	update_client_mobile VARCHAR(255)
)
LANGUAGE plpgsql
AS $$
BEGIN
	UPDATE clients
	SET client_first_name = update_client_first_name,
		client_last_name = update_client_last_name,
		client_address = update_client_address,
		client_email = update_client_email,
		client_mobile = update_client_mobile
	WHERE client_id = update_client_id;
END;
$$;

CREATE OR REPLACE PROCEDURE update_vehicle (
	update_vehicle_id INT,
	update_vin_number VARCHAR(255),
	update_plate_number VARCHAR(255),
	update_registration_date DATE,
	update_model_id INT
)
LANGUAGE plpgsql
AS $$
BEGIN
	UPDATE vehicles
	SET vin_number = update_vin_number,
		plate_number = update_plate_number,
		registration_date = update_registration_date,
		model_id = update_model_id
	WHERE vehicle_id = update_vehicle_id;
END;
$$;

CREATE OR REPLACE PROCEDURE update_maintenance(
    update_maintenance_id INT,
    update_vehicle_id INT,
    update_planner_id INT,
    update_planned_start_date DATE,
    update_planned_time_needed INTERVAL,
    update_was_canceled BOOLEAN,
    update_mileage_vehicle INT,
    update_total_price INT,
    update_is_finished BOOLEAN,
    update_maintenance_note VARCHAR(255)
)
LANGUAGE plpgsql
AS $$
DECLARE 
    number_entries_work_history INT;
BEGIN
    IF update_is_finished = TRUE THEN
        number_entries_work_history := (
            SELECT COUNT(*) 
            FROM employees_work_histories
            WHERE maintenance_id = update_maintenance_id
        );
        IF number_entries_work_history = 0 THEN
            RAISE EXCEPTION 'Cannot set maintenance to finished if no work history entry has been added';
        END IF;
    END IF;

    -- if updates values are null do not insert
    IF update_vehicle_id IS NULL THEN
        update_vehicle_id := (
            SELECT vehicle_id
            FROM maintenances
            WHERE maintenance_id = update_maintenance_id
        );
    END IF;
    IF update_planner_id IS NULL THEN
        update_planner_id := (
            SELECT planner_id
            FROM maintenances
            WHERE maintenance_id = update_maintenance_id
        );
    END IF;
    IF update_planned_start_date IS NULL THEN
        update_planned_start_date := (
            SELECT planned_start_date
            FROM maintenances
            WHERE maintenance_id = update_maintenance_id
        );
    END IF;
    IF update_planned_time_needed IS NULL THEN
        update_planned_time_needed := (
            SELECT planned_time_needed
            FROM maintenances
            WHERE maintenance_id = update_maintenance_id
        );
    END IF;
    IF update_was_canceled IS NULL THEN
        update_was_canceled := (
            SELECT was_canceled
            FROM maintenances
            WHERE maintenance_id = update_maintenance_id
        );
    END IF;
    IF update_mileage_vehicle IS NULL THEN
        update_mileage_vehicle := (
            SELECT mileage_vehicle
            FROM maintenances
            WHERE maintenance_id = update_maintenance_id
        );
    END IF;
    IF update_total_price IS NULL THEN
        update_total_price := (
            SELECT total_price
            FROM maintenances
            WHERE maintenance_id = update_maintenance_id
        );
    END IF;
    IF update_is_finished IS NULL THEN
        update_is_finished := (
            SELECT is_finished
            FROM maintenances
            WHERE maintenance_id = update_maintenance_id
        );
    END IF;
    IF update_maintenance_note IS NULL THEN
        update_maintenance_note := (
            SELECT maintenance_note
            FROM maintenances
            WHERE maintenance_id = update_maintenance_id
        );
    END IF;
    
    UPDATE maintenances
    SET vehicle_id = update_vehicle_id,
        planner_id = update_planner_id,
        planned_start_date = update_planned_start_date,
        planned_time_needed = update_planned_time_needed,
        was_canceled = update_was_canceled,
        mileage_vehicle = update_mileage_vehicle,
        total_price = update_total_price,
        is_finished = update_is_finished,
        maintenance_note = update_maintenance_note
    WHERE maintenance_id = update_maintenance_id;
END;
$$;

CREATE OR REPLACE PROCEDURE update_recurrent_maintenance (
	update_recurrent_maintenance_id INT,
    update_mileage_repeat INT,
    update_days_after_last_maintenance INT,
    update_model_id INT
)
LANGUAGE plpgsql
AS $$
BEGIN
    UPDATE recurrent_maintenances
	SET mileage_repeat = update_mileage_repeat,
        days_after_last_maintenance = update_days_after_last_maintenance,
        model_id = update_model_id
	WHERE recurrent_maintenance_id = update_recurrent_maintenance_id;
END;
$$;

CREATE OR REPLACE PROCEDURE update_recurrent_maintenance_model_type (
	update_recurrent_maintenance_id INT,
    update_mileage_repeat INT,
    update_days_after_last_maintenance INT,
    update_model_type_id INT
)
LANGUAGE plpgsql
AS $$
BEGIN
	UPDATE recurrent_maintenances
	SET mileage_repeat = update_mileage_repeat,
        days_after_last_maintenance = update_days_after_last_maintenance,
        model_type_id = update_model_type_id
	WHERE recurrent_maintenance_id = update_recurrent_maintenance_id;
END;
$$;
