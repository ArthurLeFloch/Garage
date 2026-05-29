-- =====================
--   Database : Garage
-- =====================

\echo '================================='
\echo '  CREATE PROCEDURES FOR INSERTS'
\echo '================================='

CREATE OR REPLACE FUNCTION insert_part_type (
    insert_part_type_name VARCHAR(255)
)
RETURNS INT
LANGUAGE plpgsql
AS $$
DECLARE
    part_type_id_return INT;
BEGIN
    IF EXISTS (
        SELECT *
        FROM part_types
        WHERE part_type_name = insert_part_type_name
    ) THEN
        RAISE EXCEPTION 'Part type already exists: %', insert_part_type_name;
    END IF;

    INSERT INTO part_types (part_type_name)
    VALUES (insert_part_type_name)
    RETURNING part_type_id INTO part_type_id_return;

    RETURN part_type_id_return;
END;
$$;

CREATE OR REPLACE PROCEDURE insert_part (
	insert_part_type_id INT,
	insert_part_name VARCHAR(255),
	insert_part_reference VARCHAR(255),
	insert_unitary_price REAL
)
LANGUAGE plpgsql
AS $$
BEGIN

    IF EXISTS (
        SELECT *
        FROM parts
        WHERE part_type_id = insert_part_type_id
        AND part_name = insert_part_name
    ) THEN
        RAISE EXCEPTION 'Part already exists: %', insert_part_name || ' (' || insert_part_type_name || ')';
    END IF;

	INSERT INTO parts (part_type_id, part_name, part_reference, unitary_price)
	VALUES (insert_part_type_id, insert_part_name, insert_part_reference, insert_unitary_price);

END;
$$;

CREATE OR REPLACE PROCEDURE insert_part_simplified (
	insert_part_type_name VARCHAR(255),
	insert_part_name VARCHAR(255),
	insert_part_reference VARCHAR(255),
	insert_unitary_price REAL
)
LANGUAGE plpgsql
AS $$
DECLARE
	part_type_id_return INT;
BEGIN

	-- Get part_type_id based on part_type_name
	part_type_id_return := (
		SELECT part_type_id
		FROM part_types
		WHERE part_type_name = insert_part_type_name
	);

	IF part_type_id_return IS NULL THEN
		-- Create a new entry if part_type_id is not found
        SELECT insert_part_type(insert_part_type_name) INTO part_type_id_return;
	END IF;

    CALL insert_part(
        part_type_id_return,
        insert_part_name,
        insert_part_reference,
        insert_unitary_price
    );

END;
$$;

CREATE OR REPLACE PROCEDURE insert_vehicle (
	insert_vin_number VARCHAR(255),
	insert_plate_number VARCHAR(255),
	insert_registration_date DATE,
	insert_client_id INT,
	insert_model_id INT
)
LANGUAGE plpgsql
AS $$
BEGIN
	INSERT INTO vehicles (vin_number, plate_number, registration_date, client_id, model_id)
	VALUES (insert_vin_number, insert_plate_number, insert_registration_date, insert_client_id, insert_model_id);

	-- Return NULL (assuming it's a void function)
	-- RETURN;
END;
$$;

CREATE OR REPLACE PROCEDURE insert_client (
	insert_client_first_name VARCHAR(255),
	insert_client_last_name VARCHAR(255),
	insert_client_address VARCHAR(255),
	insert_client_email VARCHAR(255),
	insert_client_mobile VARCHAR(255)
)
LANGUAGE plpgsql
AS $$
BEGIN
	INSERT INTO clients (
		client_first_name,
		client_last_name,
		client_address,
		client_email,
		client_mobile
	)
	VALUES
	(
		insert_client_first_name,
		insert_client_last_name,
		insert_client_address,
		insert_client_email,
		insert_client_mobile
	);
END;
$$;

CREATE OR REPLACE PROCEDURE insert_employee (
	insert_employee_first_name VARCHAR(255),
	insert_employee_last_name VARCHAR(255),
	insert_employee_address VARCHAR(255),
	insert_employee_email VARCHAR(255),
	insert_employee_mobile VARCHAR(255)
)
LANGUAGE plpgsql
AS $$
BEGIN
	INSERT INTO employees (
		employee_first_name,
		employee_last_name,
		employee_address,
		employee_email,
		employee_mobile
	)
	VALUES
	(
		insert_employee_first_name,
		insert_employee_last_name,
		insert_employee_address,
		insert_employee_email,
		insert_employee_mobile
	);
END;
$$;

CREATE OR REPLACE PROCEDURE insert_manufacturer (
	insert_manufacturer_name VARCHAR(255)
)
LANGUAGE plpgsql
AS $$
BEGIN
    IF EXISTS (
        SELECT *
        FROM manufacturers
        WHERE manufacturer_name = insert_manufacturer_name
    ) THEN
        RAISE EXCEPTION 'Manufacturer already exists: %', insert_manufacturer_name;
    END IF;

	INSERT INTO manufacturers (
		manufacturer_name
	)
	VALUES
	(
		insert_manufacturer_name
	);
END;
$$;

CREATE OR REPLACE PROCEDURE insert_extern_garage (
	insert_extern_garage_name VARCHAR(255)
)
LANGUAGE plpgsql
AS $$
BEGIN
	INSERT INTO extern_garages (
		extern_garage_name
	)
	VALUES
	(
		insert_extern_garage_name
	);
END;
$$;

CREATE OR REPLACE PROCEDURE insert_extern_maintenance (
    insert_vehicle_id INT,
    insert_extern_garage_name VARCHAR(255),
    insert_start_date DATE,
    insert_end_date DATE,
    insert_note VARCHAR(255),
    insert_intervention_name VARCHAR(255)
)
LANGUAGE plpgsql
AS $$
DECLARE
    extern_garage_id_return INT;
    intervention_id_return INT;
    extern_maintenance_id_return INT;
BEGIN
    extern_garage_id_return := (
        SELECT extern_garage_id
        FROM extern_garages
        WHERE extern_garage_name = insert_extern_garage_name
    );

    IF extern_garage_id_return IS NULL THEN
        RAISE EXCEPTION 'Extern garage not found, wrong extern garage name: %', insert_extern_garage_name;
    END IF;

    intervention_id_return := (
        SELECT intervention_id
        FROM interventions
        WHERE intervention_name = insert_intervention_name
    );

    IF intervention_id_return IS NULL THEN
        RAISE EXCEPTION 'Intervention not found, wrong intervention name: %', insert_intervention_name;
    END IF;

    INSERT INTO extern_maintenances (
        vehicle_id,
        extern_garage_id,
        extern_start_date,
        extern_end_date,
        extern_maintenance_note
    )
    VALUES (
        insert_vehicle_id,
        extern_garage_id_return,
        insert_start_date,
        insert_end_date,
        insert_note
    )
    RETURNING extern_maintenance_id INTO extern_maintenance_id_return;

    INSERT INTO extern_maintenances_interventions (
        extern_maintenance_id,
        intervention_id
    )
    VALUES (
        extern_maintenance_id_return,
        intervention_id_return
    );
END;
$$;

CREATE OR REPLACE PROCEDURE insert_model_type (
	insert_model_type_name VARCHAR(255)
)
LANGUAGE plpgsql
AS $$
BEGIN
    IF EXISTS (
        SELECT *
        FROM model_types
        WHERE model_type_name = insert_model_type_name
    ) THEN
        RAISE EXCEPTION 'Model type already exists: %', insert_model_type_name;
    END IF;
	INSERT INTO model_types (
		model_type_name
	)
	VALUES (
		insert_model_type_name
	);
END;
$$;

-- ensures integrity rules
CREATE OR REPLACE FUNCTION insert_prescription(
    insert_vehicle_id INT,
    insert_employee_id INT,
    insert_date DATE,
    insert_to_do_before_date DATE,
    insert_intervention_id INT
)
RETURNS INT
LANGUAGE plpgsql
AS $$
DECLARE
    prescription_id_return INT;
BEGIN

    INSERT INTO prescriptions (
        prescription_date,
        to_do_before_date,
        employee_id,
        vehicle_id
    )
    VALUES (
        insert_date,
        insert_to_do_before_date,
        insert_employee_id,
        insert_vehicle_id
    )
    RETURNING prescription_id INTO prescription_id_return;

    INSERT INTO prescriptions_interventions (
        prescription_id,
        intervention_id
    )
    VALUES (
        prescription_id_return,
        insert_intervention_id
    );

    RETURN prescription_id_return;
END;
$$;

CREATE OR REPLACE PROCEDURE insert_prescription_simplified(
    insert_vehicle_id INT,
    insert_employee_id INT,
    insert_date DATE,
    insert_to_do_before_date DATE,
    insert_intervention_name VARCHAR(255)
)
LANGUAGE plpgsql
AS $$
DECLARE
    intervention_id_return INT;
BEGIN
    intervention_id_return := (
        SELECT intervention_id
        FROM interventions
        WHERE intervention_name = insert_intervention_name
    );

    IF intervention_id_return IS NULL THEN
        RAISE EXCEPTION 'Intervention not found, wrong intervention name: %', insert_intervention_name;
    END IF;


    PERFORM insert_prescription(
        insert_vehicle_id,
        insert_employee_id ,
        insert_date,
        insert_to_do_before_date ,
        intervention_id_return
    );
END;
$$;

-- ensures integrity rules
CREATE OR REPLACE FUNCTION insert_maintenance (
    insert_vehicle_id INT,
    insert_planner_id INT,
    insert_planned_start_date DATE,
    insert_time_needed INTERVAL,
    insert_was_canceled BOOLEAN,
    insert_mileage_vehicle INT,
    insert_total_price INT,
    insert_is_finished BOOLEAN,
    insert_maintenance_note VARCHAR(255),
    insert_intervention_id INT
)
RETURNS INT
LANGUAGE plpgsql
AS $$
DECLARE
    maintenance_id_return INT;
BEGIN
    IF insert_is_finished = TRUE THEN
       RAISE EXCEPTION 'Cannot set maintenance to finished at creation';
    END IF;

    -- check if not planned time does not overlap with another maintenance of
    -- the same vehicle
    IF EXISTS (
        SELECT *
        FROM maintenances
        WHERE vehicle_id = insert_vehicle_id    -- maintenance before
        AND planned_start_date + planned_time_needed > insert_planned_start_date
        AND planned_start_date < insert_planned_start_date + insert_time_needed
        OR vehicle_id = insert_vehicle_id       -- maintenance after
        AND insert_planned_start_date + insert_time_needed > planned_start_date
        AND insert_planned_start_date < planned_start_date
    ) THEN
        RAISE EXCEPTION 'Maintenance time overlaps with another maintenance of the same vehicle';
    END IF;

    INSERT INTO maintenances (
        vehicle_id,
        planner_id,
        planned_start_date,
        planned_time_needed,
        was_canceled,
        mileage_vehicle,
        total_price,
        is_finished,
        maintenance_note
    )
    VALUES (
        insert_vehicle_id,
        insert_planner_id,
        insert_planned_start_date,
        insert_time_needed,
        insert_was_canceled,
        insert_mileage_vehicle,
        insert_total_price,
        insert_is_finished,
        insert_maintenance_note
    )
    RETURNING maintenance_id INTO maintenance_id_return;

    INSERT INTO maintenances_interventions (
        maintenance_id,
        intervention_id
    )
    VALUES (
        maintenance_id_return,
        insert_intervention_id
    );

    -- Return the maintenance_id_return using SELECT
    RETURN maintenance_id_return;
END;
$$;

CREATE OR REPLACE PROCEDURE insert_maintenance_simplified (
	insert_vehicle_id INT,
	insert_planner_id INT,
	insert_planned_start_date DATE,
	insert_time_needed INTERVAL,
	insert_was_canceled BOOLEAN,
	insert_mileage_vehicle INT,
	insert_total_price INT,
	insert_is_finished BOOLEAN,
	insert_maintenance_note VARCHAR(255),
    insert_intervention_name VARCHAR(255)
)
LANGUAGE plpgsql
AS $$
DECLARE
intervention_id_return INT;
BEGIN

	intervention_id_return := (
		SELECT intervention_id
		FROM interventions
		WHERE intervention_name = insert_intervention_name
	);

	IF intervention_id_return IS NULL THEN
		RAISE EXCEPTION 'Intervention not found, wrong intervention name: %', insert_intervention_name;
	END IF;

    PERFORM insert_maintenance(
        insert_vehicle_id,
        insert_planner_id,
        insert_planned_start_date,
        insert_time_needed,
        insert_was_canceled,
        insert_mileage_vehicle,
        insert_total_price,
        insert_is_finished,
        insert_maintenance_note,
        intervention_id_return
    );
END;
$$;

-- CREATE OR REPLACE PROCEDURE insert_planned_maintenance (
-- 	insert_maintenance_vehicle_plate_number VARCHAR(255),
-- 	insert_maintenance_planner_id INT,
-- 	insert_maintenance_planned_start_date DATE,
-- 	insert_maintenance_time_needed INTERVAL
-- )
-- LANGUAGE plpgsql
-- AS $$
-- DECLARE
-- 	vehicle_id_return INT;
-- BEGIN
-- 	vehicle_id_return := (
-- 		SELECT vehicle_id
-- 		FROM vehicles
-- 		WHERE plate_number = insert_maintenance_vehicle_plate_number
-- 	);

-- 	-- weird to use plate number instead of vehicle_id
-- 	IF vehicle_id_return IS NULL THEN
-- 		 RAISE EXCEPTION 'Vehicle not found, wrong plate number: %', insert_maintenance_vehicle_plate_number;
-- 	END IF;

-- 	INSERT INTO maintenances (
-- 		vehicle_id,
-- 		planner_id,
-- 		planned_start_date,
-- 		planned_time_needed
-- 	)
-- 	VALUES (
-- 		vehicle_id_return,
-- 		insert_maintenance_planner_id,
-- 		insert_maintenance_planned_start_date,
-- 		insert_maintenance_time_needed,
-- 	);
-- END;
-- $$;

CREATE OR REPLACE PROCEDURE insert_model_type_intervention_price (
	insert_model_id INT,
	insert_intervention_name VARCHAR(255),
	insert_estimated_price INT
)
LANGUAGE plpgsql
AS $$
DECLARE
	intervention_id_return INT;
BEGIN
	intervention_id_return := (
		SELECT intervention_id
		FROM interventions
		WHERE intervention_name = insert_intervention_name
	);

	IF intervention_id_return IS NULL THEN
		RAISE EXCEPTION 'Intervention not found, wrong intervention name: %', insert_intervention_name;
	END IF;

	INSERT INTO model_types_interventions_prices (
		model_type_id,
		intervention_id,
		estimated_price
	)
	VALUES (
		insert_model_id,
		intervention_id_return,
		insert_estimated_price
	);
END;
$$;

CREATE OR REPLACE PROCEDURE insert_model_intervention_price (
	insert_model_id INT,
	insert_intervention_name VARCHAR(255),
	insert_estimated_price INT
)
LANGUAGE plpgsql
AS $$
DECLARE
	intervention_id_return INT;
BEGIN
	intervention_id_return := (
		SELECT intervention_id
		FROM interventions
		WHERE intervention_name = insert_intervention_name
	);

	IF intervention_id_return IS NULL THEN
		RAISE EXCEPTION 'Intervention not found, wrong intervention name: %', insert_intervention_name;
	END IF;

	INSERT INTO models_interventions_prices (
		model_id,
		intervention_id,
		estimated_price
	)
	VALUES (
		insert_model_id,
		intervention_id_return,
		insert_estimated_price
	);
END;
$$;

CREATE OR REPLACE PROCEDURE insert_maintenance_intervention (
	insert_maintenance_id INT,
	insert_intervention_id INT
)
LANGUAGE plpgsql
AS $$
BEGIN
	INSERT INTO maintenances_interventions (
		maintenance_id,
		intervention_id
	)
	VALUES (
		insert_maintenance_id,
		insert_intervention_id
	);
END;
$$;

CREATE OR REPLACE PROCEDURE insert_maintenance_intervention_simplified (
	insert_maintenance_id INT,
	insert_intervention_name VARCHAR(255)
)
LANGUAGE plpgsql
AS $$
DECLARE
	intervention_id_return INT;
BEGIN
	intervention_id_return := (
		SELECT intervention_id
		FROM interventions
		WHERE intervention_name = insert_intervention_name
	);

	IF intervention_id_return IS NULL THEN
		RAISE EXCEPTION 'Intervention not found, wrong intervention name: %', insert_intervention_name;
	END IF;

	CALL insert_maintenance_intervention(
        insert_maintenance_id,
        intervention_id_return
    );
END;
$$;

-- ensures integrity rules
CREATE OR REPLACE FUNCTION insert_recurrent_maintenance_model_by_mileage(
    insert_mileage_repeat INT,
    insert_model_id INT,
    insert_intervention_id INT
)
RETURNS INT
LANGUAGE plpgsql
AS $$
DECLARE
    recurrent_maintenance_id_return INT;
BEGIN
    recurrent_maintenance_id_return := (
        SELECT recurrent_maintenance_id
        FROM recurrent_maintenances
        WHERE mileage_repeat = insert_mileage_repeat
        AND model_id = insert_model_id
    );
    IF recurrent_maintenance_id_return IS NULL THEN
        INSERT INTO recurrent_maintenances (
            mileage_repeat,
            model_id
        )
        VALUES (
            insert_mileage_repeat,
            insert_model_id
        )
        RETURNING recurrent_maintenance_id INTO recurrent_maintenance_id_return;
    END IF;

    IF EXISTS (
        SELECT 1
        FROM recurrent_maintenances_interventions
        WHERE recurrent_maintenance_id = recurrent_maintenance_id_return
        AND intervention_id = insert_intervention_id
    ) THEN
        RAISE EXCEPTION 'Intervention already linked to this recurrent maintenance';
    END IF;

    INSERT INTO recurrent_maintenances_interventions (
        recurrent_maintenance_id,
        intervention_id
    )
    VALUES (
        recurrent_maintenance_id_return,
        insert_intervention_id
    );

    RETURN recurrent_maintenance_id_return;
END;
$$;

CREATE OR REPLACE PROCEDURE insert_recurrent_maintenance_model_by_mileage_simplified(
    insert_mileage_repeat INT,
    insert_model_id INT,
    insert_intervention_name VARCHAR(255)
)
LANGUAGE plpgsql
AS $$
DECLARE
    intervention_id_return INT;
BEGIN
    intervention_id_return := (
        SELECT intervention_id
        FROM interventions
        WHERE intervention_name = insert_intervention_name
    );

    IF intervention_id_return IS NULL THEN
        RAISE EXCEPTION 'Intervention not found, wrong intervention name: %', insert_intervention_name;
    END IF;

    PERFORM insert_recurrent_maintenance_model_by_mileage(
        insert_mileage_repeat,
        insert_model_id,
        intervention_id_return
    );
END;
$$;

-- ensures integrity rules
CREATE OR REPLACE FUNCTION insert_recurrent_maintenance_model_by_days(
    insert_days_after_last_maintenance INT,
    insert_model_id INT,
    insert_intervention_id INT
)
RETURNS INT
LANGUAGE plpgsql
AS $$
DECLARE
    recurrent_maintenance_id_return INT;
BEGIN
    recurrent_maintenance_id_return := (
        SELECT recurrent_maintenance_id
        FROM recurrent_maintenances
        WHERE days_after_last_maintenance = insert_days_after_last_maintenance
        AND model_id = insert_model_id
    );
    IF recurrent_maintenance_id_return IS NULL THEN
        INSERT INTO recurrent_maintenances (
            days_after_last_maintenance,
            model_id
        )
        VALUES (
            insert_days_after_last_maintenance,
            insert_model_id
        )
        RETURNING recurrent_maintenance_id INTO recurrent_maintenance_id_return;
    END IF;

    IF EXISTS (
        SELECT 1
        FROM recurrent_maintenances_interventions
        WHERE recurrent_maintenance_id = recurrent_maintenance_id_return
        AND intervention_id = insert_intervention_id
    ) THEN
        RAISE EXCEPTION 'Intervention already linked to this recurrent maintenance';
    END IF;

    INSERT INTO recurrent_maintenances_interventions (
        recurrent_maintenance_id,
        intervention_id
    )
    VALUES (
        recurrent_maintenance_id_return,
        insert_intervention_id
    );

    RETURN recurrent_maintenance_id_return;
END;
$$;

CREATE OR REPLACE PROCEDURE insert_recurrent_maintenance_model_by_days_simplified(
    insert_days_after_last_maintenance INT,
    insert_model_id INT,
    insert_intervention_name VARCHAR(255)
)
LANGUAGE plpgsql
AS $$
DECLARE
    intervention_id_return INT;
BEGIN
    intervention_id_return := (
        SELECT intervention_id
        FROM interventions
        WHERE intervention_name = insert_intervention_name
    );

    IF intervention_id_return IS NULL THEN
        RAISE EXCEPTION 'Intervention not found, wrong intervention name: %', insert_intervention_name;
    END IF;

    PERFORM insert_recurrent_maintenance_model_by_days(
        insert_days_after_last_maintenance,
        insert_model_id,
        intervention_id_return
    );
END;
$$;

-- ensures integrity rules
CREATE OR REPLACE FUNCTION insert_recurrent_maintenance_model_type_by_mileage(
    insert_mileage_repeat INT,
    insert_model_type_id INT,
    insert_intervention_id INT
)
RETURNS INT
LANGUAGE plpgsql
AS $$
DECLARE
    recurrent_maintenance_id_return INT;
BEGIN
    recurrent_maintenance_id_return := (
        SELECT recurrent_maintenance_id
        FROM recurrent_maintenances
        WHERE mileage_repeat = insert_mileage_repeat
        AND model_type_id = insert_model_type_id
    );
    IF recurrent_maintenance_id_return IS NULL THEN
        INSERT INTO recurrent_maintenances (
            mileage_repeat,
            model_type_id
        )
        VALUES (
            insert_mileage_repeat,
            insert_model_type_id
        )
        RETURNING recurrent_maintenance_id INTO recurrent_maintenance_id_return;
    END IF;

    IF EXISTS (
        SELECT 1
        FROM recurrent_maintenances_interventions
        WHERE recurrent_maintenance_id = recurrent_maintenance_id_return
        AND intervention_id = insert_intervention_id
    ) THEN
        RAISE EXCEPTION 'Intervention already linked to this recurrent maintenance';
    END IF;

    INSERT INTO recurrent_maintenances_interventions (
        recurrent_maintenance_id,
        intervention_id
    )
    VALUES (
        recurrent_maintenance_id_return,
        insert_intervention_id
    );

    RETURN recurrent_maintenance_id_return;
END;
$$;

CREATE OR REPLACE PROCEDURE insert_recurrent_maintenance_model_type_by_mileage_simplified(
    insert_mileage_repeat INT,
    insert_model_type_id INT,
    insert_intervention_name VARCHAR(255)
)
LANGUAGE plpgsql
AS $$
DECLARE
    intervention_id_return INT;
BEGIN
    intervention_id_return := (
        SELECT intervention_id
        FROM interventions
        WHERE intervention_name = insert_intervention_name
    );

    IF intervention_id_return IS NULL THEN
        RAISE EXCEPTION 'Intervention not found, wrong intervention name: %', insert_intervention_name;
    END IF;

    PERFORM insert_recurrent_maintenance_model_type_by_mileage(
        insert_mileage_repeat,
        insert_model_type_id,
        intervention_id_return
    );
END;
$$;

CREATE OR REPLACE FUNCTION insert_recurrent_maintenance_model_type_by_days(
    insert_days_after_last_maintenance INT,
    insert_model_type_id INT,
    insert_intervention_id INT
)
RETURNS INT
LANGUAGE plpgsql
AS $$
DECLARE
    recurrent_maintenance_id_return INT;
BEGIN
    recurrent_maintenance_id_return := (
        SELECT recurrent_maintenance_id
        FROM recurrent_maintenances
        WHERE days_after_last_maintenance = insert_days_after_last_maintenance
        AND model_type_id = insert_model_type_id
    );
    IF recurrent_maintenance_id_return IS NULL THEN
        INSERT INTO recurrent_maintenances (
            days_after_last_maintenance,
            model_type_id
        )
        VALUES (
            insert_days_after_last_maintenance,
            insert_model_type_id
        )
        RETURNING recurrent_maintenance_id INTO recurrent_maintenance_id_return;
    END IF;

    IF EXISTS (
        SELECT 1
        FROM recurrent_maintenances_interventions
        WHERE recurrent_maintenance_id = recurrent_maintenance_id_return
        AND intervention_id = insert_intervention_id
    ) THEN
        RAISE EXCEPTION 'Intervention already linked to this recurrent maintenance';
    END IF;

    INSERT INTO recurrent_maintenances_interventions (
        recurrent_maintenance_id,
        intervention_id
    )
    VALUES (
        recurrent_maintenance_id_return,
        insert_intervention_id
    );

    RETURN recurrent_maintenance_id_return;
END;
$$;

CREATE OR REPLACE PROCEDURE insert_recurrent_maintenance_model_type_by_days_simplified(
    insert_days_after_last_maintenance INT,
    insert_model_type_id INT,
    insert_intervention_name VARCHAR(255)
)
LANGUAGE plpgsql
AS $$
DECLARE
    intervention_id_return INT;
BEGIN
    intervention_id_return := (
        SELECT intervention_id
        FROM interventions
        WHERE intervention_name = insert_intervention_name
    );

    IF intervention_id_return IS NULL THEN
        RAISE EXCEPTION 'Intervention not found, wrong intervention name: %', insert_intervention_name;
    END IF;

    PERFORM insert_recurrent_maintenance_model_type_by_days(
        insert_days_after_last_maintenance,
        insert_model_type_id,
        intervention_id_return
    );
END;
$$;


CREATE OR REPLACE PROCEDURE insert_recurrent_maintenance_intervention (
	insert_recurrent_maintenance_id INT,
	insert_intervention_id INT
)
LANGUAGE plpgsql
AS $$
BEGIN
	INSERT INTO recurrent_maintenances_interventions (
		recurrent_maintenance_id,
		intervention_id
	)
	VALUES (
		insert_recurrent_maintenance_id,
		insert_intervention_id
	);
END;
$$;

CREATE OR REPLACE PROCEDURE insert_recurrent_maintenance_intervention_simplified (
	insert_recurrent_maintenance_id INT,
	insert_intervention_name VARCHAR(255)
)
LANGUAGE plpgsql
AS $$
DECLARE
	intervention_id_return INT;
BEGIN
	intervention_id_return := (
		SELECT intervention_id
		FROM interventions
		WHERE intervention_name = insert_intervention_name
	);

	IF intervention_id_return IS NULL THEN
		RAISE EXCEPTION 'Intervention not found, wrong intervention name: %', insert_intervention_name;
	END IF;

	CALL insert_recurrent_maintenance_intervention(
        insert_recurrent_maintenance_id,
        intervention_id_return
    );
END;
$$;

CREATE OR REPLACE PROCEDURE insert_recurrent_maintenance_part (
	insert_recurrent_maintenance_part_maintenance_id INT,
	insert_recurrent_maintenance_part_type_name VARCHAR(255),
	insert_recurrent_maintenance_part_part_name VARCHAR(255),
	insert_recurrent_maintenance_part_nb INT
)
LANGUAGE plpgsql
AS $$
DECLARE
	part_type_id_return INT;
	part_id_return INT;
BEGIN
	part_type_id_return := (
		SELECT part_type_id
		FROM part_types
		WHERE part_type_name = insert_recurrent_maintenance_part_type_name
	);

	IF part_type_id_return IS NULL THEN
        SELECT insert_part_type(insert_recurrent_maintenance_part_type_name) INTO part_type_id_return;
	END IF;

	part_id_return := (
		SELECT p.part_id
		FROM parts p
		INNER JOIN part_types t USING (part_type_id)
		WHERE t.part_type_name = insert_recurrent_maintenance_part_type_name
		AND p.part_name = insert_recurrent_maintenance_part_part_name
	);

	IF part_id_return IS NULL THEN
        -- test if part_type_name + part_name already exists
        IF EXISTS (
            SELECT *
            FROM parts p
            INNER JOIN part_types t USING (part_type_id)
            WHERE t.part_type_name = insert_recurrent_maintenance_part_type_name
            AND p.part_name = insert_recurrent_maintenance_part_part_name
        ) THEN
            RAISE EXCEPTION 'Part already exists: % %', insert_recurrent_maintenance_part_type_name, insert_recurrent_maintenance_part_part_name;
        END IF;

		INSERT INTO parts (part_type_name, part_name)
		VALUES (
			insert_recurrent_maintenance_part_type_name,
			insert_recurrent_maintenance_part_part_name
		)
		RETURNING part_id INTO part_id_return;
	END IF;

	INSERT INTO recurrent_maintenances_parts (
		recurrent_maintenance_id,
		part_id,
		number_of_parts
	)
	VALUES (
		insert_recurrent_maintenance_part_maintenance_id,
		part_id_return,
		insert_recurrent_maintenance_part_nb
	);
END;
$$;

-- DROP PROCEDURE IF EXISTS insert_timeslot_ym;
CREATE OR REPLACE PROCEDURE insert_timeslot_ym (
	insert_timeslot_ym_year_number INT,
	insert_timeslot_ym_month_number INT
)
LANGUAGE plpgsql
AS $$
BEGIN
    -- check if month_number is between 1 and 12
    IF insert_timeslot_ym_month_number < 1 OR insert_timeslot_ym_month_number > 12 THEN
        RAISE EXCEPTION 'Month number must be between 1 and 12, wrong month number: %', insert_timeslot_ym_month_number;
    END IF;

	INSERT INTO timeslots_ym (
		year_number,
		month_number
	)
	VALUES (
		insert_timeslot_ym_year_number,
		insert_timeslot_ym_month_number
	);
END;
$$;

CREATE OR REPLACE PROCEDURE insert_timeslot_dh (
	insert_timeslot_dh_day_of_month INT,
	insert_timeslot_dh_start_hour TIME
)
LANGUAGE plpgsql
AS $$
BEGIN
    -- check if day_of_month is between 1 and 31
    IF insert_timeslot_dh_day_of_month < 1 OR insert_timeslot_dh_day_of_month > 31 THEN
        RAISE EXCEPTION 'Day of month must be between 1 and 31, wrong day of month: %', insert_timeslot_dh_day_of_month;
    END IF;

    -- check if start_hour is between 0 and 23
    IF EXTRACT(HOUR FROM insert_timeslot_dh_start_hour) < 0 OR EXTRACT(HOUR FROM insert_timeslot_dh_start_hour) > 23 THEN
        RAISE EXCEPTION 'Start hour must be between 0 and 23, wrong start hour: %', insert_timeslot_dh_start_hour;
    END IF;

	INSERT INTO timeslots_dh (
		day_of_month,
		start_hour
	)
	VALUES (
		insert_timeslot_dh_day_of_month,
		insert_timeslot_dh_start_hour
	);
END;
$$;

CREATE OR REPLACE PROCEDURE insert_work_duration (
	insert_work_duration_wd INTERVAL
)
LANGUAGE plpgsql
AS $$
BEGIN
	INSERT INTO work_durations (
		work_duration
	)
	VALUES (
		insert_work_duration_wd
	);
END;
$$;

--  employee_is_free (
--     var_employee_id         INT,
--     var_work_date           DATE,
--     var_start_time          TIME,
--     var_end_time            TIME
-- )


-- DROP PROCEDURE IF EXISTS insert_employee_work_history(integer,integer,integer,integer,integer,time without time zone,interval);
CREATE OR REPLACE PROCEDURE insert_employee_work_history (
	insert_maintenance_id INT,
	insert_employee_id INT,
	insert_year_number INT,
	insert_month_number INT,
	insert_day_of_month INT,
	insert_start_hour TIME,
	insert_work_duration INTERVAL
)
LANGUAGE plpgsql
AS $$
DECLARE
	timeslot_ym_id_return INT;
	timeslot_dh_id_return INT;
	work_duration_id_return INT;
BEGIN
    /* IF insert_year_number > EXTRACT(YEAR FROM NOW()) THEN
        RAISE EXCEPTION 'Year number cannot be in the future, wrong year number: %', insert_year_number;
    END IF;
    IF insert_month_number > EXTRACT(MONTH FROM NOW()) THEN
        RAISE EXCEPTION 'Month number cannot be in the future, wrong month number: %', insert_month_number;
    END IF;
    IF insert_day_of_month > EXTRACT(DAY FROM NOW()) AND insert_month_number = EXTRACT(MONTH FROM NOW()) THEN
        RAISE EXCEPTION 'Day number cannot be in the future, wrong day number: %', insert_day_of_month;
    END IF; */

    -- check if month_number is between 1 and 12
    IF insert_month_number < 1 OR insert_month_number > 12 THEN
        RAISE EXCEPTION 'Month number must be between 1 and 12, wrong month number: %', insert_month_number;
    END IF;

    -- check if day_of_month is between 1 and 31
    IF insert_month_number < 1 OR insert_month_number > 31 THEN
        RAISE EXCEPTION 'Day of month must be between 1 and 31, wrong day of month: %', insert_month_number;
    END IF;

    -- check if start_hour is between 0 and 23
    IF EXTRACT(HOUR FROM insert_start_hour) < 0 OR EXTRACT(HOUR FROM insert_start_hour) > 23 THEN
        RAISE EXCEPTION 'Start hour must be between 0 and 23, wrong start hour: %', insert_start_hour;
    END IF;

    IF NOT employee_is_free(insert_employee_id,
        MAKE_DATE(insert_year_number, insert_month_number, insert_day_of_month),
        insert_start_hour,
        insert_start_hour + insert_work_duration
    ) THEN
        RAISE EXCEPTION 'Employee is already busy at this time';
    END IF;

	timeslot_ym_id_return := (
		SELECT timeslot_ym_id
		FROM timeslots_ym
		WHERE year_number = insert_year_number
		AND month_number = insert_month_number
	);

	IF timeslot_ym_id_return IS NULL THEN
		INSERT INTO timeslots_ym (year_number, month_number)
		VALUES (
			insert_year_number,
			insert_month_number
		)
		RETURNING timeslot_ym_id INTO timeslot_ym_id_return;
	END IF;

	timeslot_dh_id_return := (
		SELECT timeslot_dh_id
		FROM timeslots_dh
		WHERE day_of_month = insert_day_of_month
		AND start_hour = insert_start_hour
	);

	IF timeslot_dh_id_return IS NULL THEN
		INSERT INTO timeslots_dh (day_of_month, start_hour)
		VALUES (
			insert_day_of_month,
			insert_start_hour
		)
		RETURNING timeslot_dh_id INTO timeslot_dh_id_return;
	END IF;

	work_duration_id_return := (
		SELECT work_duration_id
		FROM work_durations
		WHERE work_duration = insert_work_duration
	);

	IF work_duration_id_return IS NULL THEN
		INSERT INTO work_durations (work_duration)
		VALUES (
			insert_work_duration
		)
		RETURNING work_duration_id INTO work_duration_id_return;
	END IF;

	INSERT INTO employees_work_histories (
		maintenance_id,
		employee_id,
		timeslot_ym_id,
		timeslot_dh_id,
		work_duration_id
	)
	VALUES (
		insert_maintenance_id,
		insert_employee_id,
		timeslot_ym_id_return,
		timeslot_dh_id_return,
		work_duration_id_return
	);
END;
$$;

CREATE OR REPLACE PROCEDURE insert_employee_work_history (
    maintenance_id      INT,
    employee_id         INT,
    work_date           DATE,
    start_time          TIME,
    end_time            TIME
)
LANGUAGE plpgsql
AS $$
BEGIN
    -- check if employee is free
    IF NOT employee_is_free(employee_id, work_date, start_time, end_time) THEN
        RAISE EXCEPTION 'Employee is already busy at this time';
    END IF;

    CALL insert_employee_work_history(
        maintenance_id,
        employee_id,
        CAST(EXTRACT(YEAR FROM work_date) AS INT),
        CAST(EXTRACT(MONTH FROM work_date) AS INT),
        CAST(EXTRACT(DAY FROM work_date) AS INT),
        start_time,
        end_time - start_time
    );
END;
$$;



CREATE OR REPLACE PROCEDURE insert_model (
	insert_model_name VARCHAR(255),
	insert_model_version VARCHAR(255),
	insert_model_fuel_id INT,
	insert_model_coolant_id INT,
	insert_model_suspension_id INT,
	insert_model_wheel_id INT,
	insert_model_oil_id INT,
	insert_model_manufacturer_id INT,
	insert_model_model_type_id INT
)
LANGUAGE plpgsql
AS $$
BEGIN
    -- check if model already exists
    IF EXISTS (
        SELECT *
        FROM models
        WHERE model_name = insert_model_name
        AND model_version = insert_model_version
        AND fuel_id = insert_model_fuel_id
    ) THEN
        RAISE EXCEPTION 'Model already exists: % %', insert_model_name, insert_model_version;
    END IF;

    INSERT INTO models (
        model_name,
        model_version,
        coolant_id,
        suspension_id,
        wheel_id,
        oil_id,
        fuel_id,
        manufacturer_id,
        model_type_id
    )
    VALUES (
        insert_model_name,
        insert_model_version,
        insert_model_coolant_id,
        insert_model_suspension_id,
        insert_model_wheel_id,
        insert_model_oil_id,
        insert_model_fuel_id,
        insert_model_manufacturer_id,
        insert_model_model_type_id
    );
END;
$$;

CREATE OR REPLACE PROCEDURE insert_model_simplified (
	insert_model_name VARCHAR(255),
	insert_model_version VARCHAR(255),
	insert_model_fuel_name VARCHAR(255),
	insert_model_coolant_name VARCHAR(255),
	insert_model_suspension_name VARCHAR(255),
	insert_model_wheel_name VARCHAR(255),
	insert_model_oil_name VARCHAR(255),
	insert_model_manufacturer_name VARCHAR(255),
	insert_model_model_type_name VARCHAR(255)
)
LANGUAGE plpgsql
AS $$
DECLARE
	part_fuel_id_return INT;
	part_coolant_id_return INT;
	part_suspension_id_return INT;
	part_wheel_id_return INT;
	part_oil_id_return INT;
	manufacturer_id_return INT;
	model_type_id_return INT;
BEGIN
	part_fuel_id_return := (
		SELECT p.part_id
		FROM parts p
		INNER JOIN part_types t ON p.part_type_id = t.part_type_id
		WHERE t.part_type_name = 'Carburant'
		AND p.part_name = insert_model_fuel_name
	);

	IF part_fuel_id_return IS NULL THEN
		RAISE EXCEPTION 'Fuel part not found, wrong fuel name: %', insert_model_fuel_name;
	END IF;

	part_coolant_id_return := (
		SELECT p.part_id
		FROM parts p
		INNER JOIN part_types t ON p.part_type_id = t.part_type_id
		WHERE t.part_type_name = 'Liquide de refroidissement'
		AND p.part_name = insert_model_coolant_name
	);

	IF part_coolant_id_return IS NULL THEN
		RAISE EXCEPTION 'Coolant part not found, wrong coolant name: %', insert_model_coolant_name;
	END IF;

	part_suspension_id_return := (
		SELECT p.part_id
		FROM parts p
		INNER JOIN part_types t ON p.part_type_id = t.part_type_id
		WHERE t.part_type_name = 'Suspension'
		AND p.part_name = insert_model_suspension_name
	);

	IF part_suspension_id_return IS NULL THEN
		RAISE EXCEPTION 'Suspension part not found, wrong suspension name: %', insert_model_suspension_name;
	END IF;

	part_wheel_id_return := (
		SELECT p.part_id
		FROM parts p
		INNER JOIN part_types t ON p.part_type_id = t.part_type_id
		WHERE t.part_type_name = 'Roue'
		AND p.part_name = insert_model_wheel_name
	);

	IF part_wheel_id_return IS NULL THEN
		RAISE EXCEPTION 'Wheel part not found, wrong wheel name: %', insert_model_wheel_name;
	END IF;

	part_oil_id_return := (
		SELECT p.part_id
		FROM parts p
		INNER JOIN part_types t ON p.part_type_id = t.part_type_id
		WHERE t.part_type_name = 'Huile'
		AND p.part_name = insert_model_oil_name
	);

	IF part_oil_id_return IS NULL THEN
		RAISE EXCEPTION 'Oil part not found, wrong oil name: %', insert_model_oil_name;
	END IF;

	manufacturer_id_return := (
		SELECT manufacturer_id
		FROM manufacturers
		WHERE manufacturer_name = insert_model_manufacturer_name
	);

	IF manufacturer_id_return IS NULL THEN
		INSERT INTO manufacturers (manufacturer_name)
		VALUES (insert_model_manufacturer_name)
		RETURNING manufacturer_id INTO manufacturer_id_return;
	END IF;

	model_type_id_return := (
		SELECT model_type_id
		FROM model_types
		WHERE model_type_name = insert_model_model_type_name
	);

	IF model_type_id_return IS NULL THEN
		INSERT INTO model_types (model_type_name)
		VALUES (
			insert_model_type_name
		)
		RETURNING model_type_id INTO model_type_id_return;
	END IF;

    CALL insert_model(
        insert_model_name,
        insert_model_version,
        part_fuel_id_return,
        part_coolant_id_return,
        part_suspension_id_return,
        part_wheel_id_return,
        part_oil_id_return,
        manufacturer_id_return,
        model_type_id_return
    );

END;
$$;



CREATE OR REPLACE PROCEDURE insert_intervention (
	insert_intervention_name VARCHAR(255)
)
LANGUAGE plpgsql
AS $$

BEGIN
    -- check if intervention already exists
    IF EXISTS (
        SELECT *
        FROM interventions
        WHERE intervention_name = insert_intervention_name
    ) THEN
        RAISE EXCEPTION 'Intervention already exists: %', insert_intervention_name;
    END IF;

	INSERT INTO interventions (
		intervention_name
	)
	VALUES
	(
		insert_intervention_name
	);
END;
$$;