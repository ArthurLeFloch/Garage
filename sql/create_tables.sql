-- =====================
--   Database : Garage
-- =====================

\echo '================='
\echo '  CREATE TABLES'
\echo '================='

-- =====================================
--   Creation of tables for identities
-- =====================================

CREATE TABLE clients
(
	client_id 						SERIAL			NOT NULL,
	client_first_name				VARCHAR(255)	NOT NULL,
	client_last_name				VARCHAR(255)	NOT NULL,
	client_address					VARCHAR(255)	,
	client_email					VARCHAR(255)	NOT NULL,
	client_mobile					VARCHAR(255)	,
	CONSTRAINT pk_clients PRIMARY KEY (client_id)
);

CREATE TABLE employees
(
	employee_id						SERIAL			NOT NULL,
	employee_first_name				VARCHAR(255)	NOT NULL,
	employee_last_name				VARCHAR(255)	NOT NULL,
	employee_address				VARCHAR(255)	,
	employee_email					VARCHAR(255)	NOT NULL,
	employee_mobile					VARCHAR(255)	,
	CONSTRAINT pk_employees PRIMARY KEY (employee_id)
);

CREATE TABLE part_types 
(
	part_type_id					SERIAL			NOT NULL,
	part_type_name					VARCHAR(255)	NOT NULL,
	CONSTRAINT pk_part_types  PRIMARY KEY (part_type_id)
);

CREATE TABLE parts
(
	part_id							SERIAL			NOT NULL,
	part_type_id					INT			    NOT NULL,
	part_name						VARCHAR(255)	NOT NULL,
	part_reference					VARCHAR(255)	,
	unitary_price					REAL			NOT NULL,
	CONSTRAINT pk_parts PRIMARY KEY (part_id)
);

CREATE TABLE vehicles
(
	vehicle_id						SERIAL			NOT NULL,
	model_id						INT			    NOT NULL,
	client_id						INT			    NOT NULL,
	vin_number						VARCHAR(255)	NOT NULL,
	plate_number					VARCHAR(255)	NOT NULL,
	registration_date				DATE			NOT NULL,
	CONSTRAINT pk_vehicles PRIMARY KEY (vehicle_id)
);

CREATE TABLE manufacturers
(
	manufacturer_id					SERIAL			NOT NULL,
	manufacturer_name				VARCHAR(255)	NOT NULL,
	CONSTRAINT pk_manufacturers PRIMARY KEY (manufacturer_id)
);

CREATE TABLE model_types
(
	model_type_id					SERIAL			NOT NULL,
	model_type_name					VARCHAR(255)	NOT NULL,
	CONSTRAINT pk_model_types PRIMARY KEY (model_type_id)
);

CREATE TABLE models
(
	model_id						SERIAL			NOT NULL,
	manufacturer_id					INT			    NOT NULL,
	model_type_id					INT			    NOT NULL,
	model_name						VARCHAR(255)	NOT NULL,
	model_version					VARCHAR(255)	NOT NULL,
	coolant_id						INT			    NOT NULL,
	suspension_id					INT			    NOT NULL,
	wheel_id						INT			    NOT NULL,
	oil_id							INT			    NOT NULL,
	fuel_id							INT			    NOT NULL,
	CONSTRAINT pk_models PRIMARY KEY (model_id)
);

CREATE TABLE timeslots_ym
(
	timeslot_ym_id					SERIAL			NOT NULL,
	year_number						INT			    NOT NULL,
	month_number					INT			    NOT NULL,
	CONSTRAINT pk_timeslots_ym PRIMARY KEY (timeslot_ym_id)

);

CREATE TABLE timeslots_dh
(
	timeslot_dh_id					SERIAL			NOT NULL,
	day_of_month					INT			    NOT NULL,
	start_hour						TIME			NOT NULL,
	CONSTRAINT pk_timeslots_dh PRIMARY KEY (timeslot_dh_id)
);

CREATE TABLE work_durations
(
	work_duration_id				SERIAL			NOT NULL,
	work_duration					INTERVAL		NOT NULL,
	CONSTRAINT pk_work_durations PRIMARY KEY (work_duration_id)
);


CREATE TABLE extern_garages
(
	extern_garage_id				SERIAL			NOT NULL,
	extern_garage_name				VARCHAR(255)	NOT NULL,
	CONSTRAINT pk_extern_garages PRIMARY KEY (extern_garage_id)
);

CREATE TABLE maintenances
(
	maintenance_id					SERIAL			NOT NULL,
	vehicle_id						INT			    NOT NULL,
	planner_id						INT			    NOT NULL,
	planned_start_date				DATE			NOT NULL,
	planned_time_needed				INTERVAL		NOT NULL,
	was_canceled					BOOLEAN			DEFAULT FALSE NOT NULL,
	mileage_vehicle					INT				NOT NULL,
	total_price						INT				NOT NULL,
	is_finished						BOOLEAN			DEFAULT FALSE NOT NULL,
	maintenance_note				VARCHAR(255)	,
	CONSTRAINT pk_maintenances PRIMARY KEY (maintenance_id)
);

CREATE TABLE extern_maintenances
(
	extern_maintenance_id			            SERIAL			NOT NULL,
	vehicle_id				                    INT			    NOT NULL,
	extern_garage_id				            INT			    ,
	extern_start_date				            DATE			NOT NULL,
	extern_end_date					            DATE			NOT NULL,
	extern_maintenance_note                     VARCHAR(255)	,
    CONSTRAINT pk_extern_maintenances PRIMARY KEY (extern_maintenance_id)
);

CREATE TABLE recurrent_maintenances
(
	recurrent_maintenance_id	    SERIAL			NOT NULL,
	mileage_repeat					INT			    ,
	days_after_last_maintenance	    INT			    ,
    model_id                        INT             ,
    model_type_id                   INT             ,
	CONSTRAINT pk_recurrent_maintenances_model PRIMARY KEY (recurrent_maintenance_id)
);


CREATE TABLE prescriptions
(
	prescription_id					SERIAL			NOT NULL,
	employee_id						INT			    NOT NULL,
	vehicle_id						INT			    NOT NULL,
	prescription_date				DATE			DEFAULT NOW() NOT NULL,
	to_do_before_date				DATE			NOT NULL,
	CONSTRAINT pk_prescriptions PRIMARY KEY (prescription_id)
);

CREATE TABLE interventions
(
	intervention_id					SERIAL			NOT NULL,
	intervention_name				VARCHAR(255)	NOT NULL,
	CONSTRAINT pk_interventions PRIMARY KEY (intervention_id)
);

-- ========================================
--   Creation of tables for relationships
-- ========================================

CREATE TABLE employees_work_histories
(
	maintenance_id					INT			NOT NULL,
	employee_id						INT			NOT NULL,
	timeslot_ym_id					INT			NOT NULL,
	timeslot_dh_id					INT			NOT NULL,
	work_duration_id				INT			NOT NULL,
	CONSTRAINT pk_employees_work_histories PRIMARY KEY (
		employee_id, timeslot_ym_id, timeslot_dh_id, maintenance_id,
		work_duration_id
	)
);


CREATE TABLE models_interventions_prices
(
	model_id						INT			NOT NULL,
	intervention_id					INT			NOT NULL,
	estimated_price					INT			NOT NULL,
	CONSTRAINT pk_models_interventions_prices PRIMARY KEY (
		model_id, intervention_id
	)
);

CREATE TABLE model_types_interventions_prices
(
	model_type_id					INT			NOT NULL,
	intervention_id					INT			NOT NULL,
	estimated_price					INT			NOT NULL,
	CONSTRAINT pk_model_types_interventions_prices PRIMARY KEY (
		model_type_id, intervention_id
	)
);

CREATE TABLE recurrent_maintenances_interventions
(
	recurrent_maintenance_id		INT			NOT NULL,
	intervention_id					INT			NOT NULL,
	CONSTRAINT pk_recurrent_maintenances_interventions PRIMARY KEY (
		recurrent_maintenance_id, intervention_id
	)
);

CREATE TABLE maintenances_interventions
(
	maintenance_id					INT			NOT NULL,
	intervention_id					INT			NOT NULL,
	CONSTRAINT pk_maintenances_interventions PRIMARY KEY (
		maintenance_id, intervention_id
	)
);

CREATE TABLE extern_maintenances_interventions
(
	extern_maintenance_id			INT			NOT NULL,
	intervention_id					INT			NOT NULL,
	CONSTRAINT pk_extern_maintenances_interventions PRIMARY KEY (
		extern_maintenance_id, intervention_id
	)
);

CREATE TABLE prescriptions_interventions
(
	prescription_id					INT			NOT NULL,
	intervention_id					INT			NOT NULL,
	CONSTRAINT pk_prescriptions_interventions PRIMARY KEY (
		prescription_id, intervention_id
	)
);

CREATE TABLE recurrent_maintenances_parts
(
	recurrent_maintenance_id		INT			NOT NULL,
	part_id							INT			NOT NULL,
	number_of_parts					INT			NOT NULL,
	CONSTRAINT pk_recurrent_maintenances_parts PRIMARY KEY (
		recurrent_maintenance_id, part_id
	)
);

CREATE TABLE maintenances_parts
(
	maintenance_id					INT			NOT NULL,
	part_id							INT			NOT NULL,
	number_of_parts					INT			NOT NULL,
	CONSTRAINT pk_maintenances_parts PRIMARY KEY (
		maintenance_id, part_id
	)
);

-- ===============================
--   Foreign keys for identities
-- ===============================

--   Foreign keys for vehicles

ALTER TABLE vehicles
	ADD CONSTRAINT fk_vehicles_model_id
	FOREIGN KEY (model_id)
	REFERENCES models (model_id) ON DELETE CASCADE;

ALTER TABLE vehicles
	ADD CONSTRAINT fk_vehicles_client_id
	FOREIGN KEY (client_id)
	REFERENCES clients (client_id) ON DELETE CASCADE;

ALTER TABLE models
	ADD CONSTRAINT fk_models_manufacturer_id
	FOREIGN KEY (manufacturer_id)
	REFERENCES manufacturers (manufacturer_id) ON DELETE CASCADE;

ALTER TABLE models
	ADD CONSTRAINT fk_models_model_type_id
	FOREIGN KEY (model_type_id)
	REFERENCES model_types (model_type_id) ON DELETE CASCADE;

ALTER TABLE models
	ADD CONSTRAINT fk_models_coolant_id
	FOREIGN KEY (coolant_id)
	REFERENCES parts (part_id) ON DELETE CASCADE;

ALTER TABLE models
	ADD CONSTRAINT fk_models_suspension_id
	FOREIGN KEY (suspension_id)
	REFERENCES parts (part_id) ON DELETE CASCADE;

ALTER TABLE models
	ADD CONSTRAINT fk_models_wheels_id
	FOREIGN KEY (wheel_id)
	REFERENCES parts (part_id) ON DELETE CASCADE;

ALTER TABLE models
	ADD CONSTRAINT fk_models_oil_id
	FOREIGN KEY (oil_id)
	REFERENCES parts (part_id) ON DELETE CASCADE;

ALTER TABLE models
	ADD CONSTRAINT fk_models_fuel_id
	FOREIGN KEY (fuel_id)
	REFERENCES parts (part_id) ON DELETE CASCADE;

--   Foreign keys for maintenances

ALTER TABLE maintenances
	ADD CONSTRAINT fk_maintenances_vehicle_id
	FOREIGN KEY (vehicle_id)
	REFERENCES vehicles (vehicle_id) ON DELETE CASCADE;

ALTER TABLE maintenances
	ADD CONSTRAINT fk_maintenances_planner_id
	FOREIGN KEY (planner_id)
	REFERENCES employees (employee_id) ON DELETE CASCADE;

ALTER TABLE extern_maintenances
	ADD CONSTRAINT fk_extern_maintenances_extern_garage_id
	FOREIGN KEY (extern_garage_id)
	REFERENCES extern_garages (extern_garage_id) ON DELETE CASCADE;

ALTER TABLE extern_maintenances
	ADD CONSTRAINT fk_extern_maintenances_vehicle_id
	FOREIGN KEY (vehicle_id)
	REFERENCES vehicles (vehicle_id) ON DELETE CASCADE;

--   Foreign keys for prescriptions

ALTER TABLE prescriptions
	ADD CONSTRAINT fk_prescriptions_employee_id
	FOREIGN KEY (employee_id)
	REFERENCES employees (employee_id) ON DELETE CASCADE;

ALTER TABLE prescriptions
	ADD CONSTRAINT fk_prescriptions_vehicle_id
	FOREIGN KEY (vehicle_id)
	REFERENCES vehicles (vehicle_id) ON DELETE CASCADE;

ALTER TABLE parts
	ADD CONSTRAINT fk_models_fuel_id
	FOREIGN KEY (part_type_id)
	REFERENCES part_types (part_type_id) ON DELETE CASCADE;


-- ==================================
--   Foreign keys for relationships
-- ==================================

--   Foreign keys for working sessions

ALTER TABLE employees_work_histories
	ADD CONSTRAINT fk_employees_work_histories_maintenance_id
	FOREIGN KEY (maintenance_id)
	REFERENCES maintenances (maintenance_id) ON DELETE CASCADE;

ALTER TABLE employees_work_histories
	ADD CONSTRAINT fk_employees_work_histories_timeslot_dh_id
	FOREIGN KEY (timeslot_dh_id)
	REFERENCES timeslots_dh (timeslot_dh_id) ON DELETE CASCADE;

ALTER TABLE employees_work_histories
	ADD CONSTRAINT fk_employees_work_histories_employee_id
	FOREIGN KEY (employee_id)
	REFERENCES employees (employee_id) ON DELETE CASCADE;

ALTER TABLE employees_work_histories
	ADD CONSTRAINT fk_employees_work_histories_timeslot_ym_id
	FOREIGN KEY (timeslot_ym_id)
	REFERENCES timeslots_ym (timeslot_ym_id) ON DELETE CASCADE;

ALTER TABLE employees_work_histories
	ADD CONSTRAINT fk_employees_work_histories_work_duration_id
	FOREIGN KEY (work_duration_id)
	REFERENCES work_durations (work_duration_id) ON DELETE CASCADE;

--   Foreign keys for maintenances

ALTER TABLE recurrent_maintenances
	ADD CONSTRAINT fk_recurrent_maintenances_model_id
	FOREIGN KEY (model_id)
	REFERENCES models (model_id) ON DELETE CASCADE;

ALTER TABLE recurrent_maintenances
	ADD CONSTRAINT fk_recurrent_maintenances_model_type_id
	FOREIGN KEY (model_type_id)
	REFERENCES model_types (model_type_id) ON DELETE CASCADE;

--   Foreign keys for prescriptions

ALTER TABLE prescriptions_interventions
	ADD CONSTRAINT fk_prescriptions_interventions_prescription_id
	FOREIGN KEY (prescription_id)
	REFERENCES prescriptions (prescription_id) ON DELETE CASCADE;

ALTER TABLE prescriptions_interventions
	ADD CONSTRAINT fk_prescriptions_interventions_intervention_id
	FOREIGN KEY (intervention_id)
	REFERENCES interventions (intervention_id) ON DELETE CASCADE;

--   Foreign keys for interventions

ALTER TABLE extern_maintenances_interventions
	ADD CONSTRAINT fk_extern_maintenances_interventions_extern_maintenance_id
	FOREIGN KEY (extern_maintenance_id)
	REFERENCES extern_maintenances (extern_maintenance_id) ON DELETE CASCADE;

ALTER TABLE extern_maintenances_interventions
	ADD CONSTRAINT fk_extern_maintenances_interventions_intervention_id
	FOREIGN KEY (intervention_id)
	REFERENCES interventions (intervention_id) ON DELETE CASCADE;

ALTER TABLE maintenances_interventions
	ADD CONSTRAINT fk_maintenances_interventions_maintenance_id
	FOREIGN KEY (maintenance_id)
	REFERENCES maintenances (maintenance_id) ON DELETE CASCADE;

ALTER TABLE maintenances_interventions
	ADD CONSTRAINT fk_maintenances_interventions_intervention_id
	FOREIGN KEY (intervention_id)
	REFERENCES interventions (intervention_id) ON DELETE CASCADE;

ALTER TABLE recurrent_maintenances_interventions
	ADD CONSTRAINT fk_recurrent_maint_interventions_recurrent_maint_id
	FOREIGN KEY (recurrent_maintenance_id)
	REFERENCES recurrent_maintenances (recurrent_maintenance_id) ON DELETE CASCADE;

ALTER TABLE recurrent_maintenances_interventions
	ADD CONSTRAINT fk_recurrent_maintenances_interventions_intervention_id
	FOREIGN KEY (intervention_id)
	REFERENCES interventions (intervention_id) ON DELETE CASCADE;

ALTER TABLE model_types_interventions_prices
	ADD CONSTRAINT fk_model_types_interventions_prices_model_type_id
	FOREIGN KEY (model_type_id)
	REFERENCES model_types (model_type_id) ON DELETE CASCADE;

ALTER TABLE model_types_interventions_prices
	ADD CONSTRAINT fk_model_types_interventions_prices_intervention_id
	FOREIGN KEY (intervention_id)
	REFERENCES interventions (intervention_id) ON DELETE CASCADE;

ALTER TABLE models_interventions_prices
	ADD CONSTRAINT fk_models_interventions_prices_model_id
	FOREIGN KEY (model_id)
	REFERENCES models (model_id) ON DELETE CASCADE;

ALTER TABLE models_interventions_prices
	ADD CONSTRAINT fk_models_interventions_prices_intervention_id
	FOREIGN KEY (intervention_id)
	REFERENCES interventions (intervention_id) ON DELETE CASCADE;

--   Foreign keys for parts

ALTER TABLE maintenances_parts
	ADD CONSTRAINT fk_maintenances_parts_maintenance_id
	FOREIGN KEY (maintenance_id)
	REFERENCES maintenances (maintenance_id) ON DELETE CASCADE;

ALTER TABLE maintenances_parts
	ADD CONSTRAINT fk_maintenances_parts_part_id
	FOREIGN KEY (part_id)
	REFERENCES parts (part_id) ON DELETE CASCADE;

ALTER TABLE recurrent_maintenances_parts
	ADD CONSTRAINT fk_recurrent_maintenances_parts_recurrent_maintenance_id
	FOREIGN KEY (recurrent_maintenance_id)
	REFERENCES recurrent_maintenances (recurrent_maintenance_id) ON DELETE CASCADE;

ALTER TABLE recurrent_maintenances_parts
	ADD CONSTRAINT fk_recurrent_maintenances_parts_part_id
	FOREIGN KEY (part_id)
	REFERENCES parts (part_id) ON DELETE CASCADE;
