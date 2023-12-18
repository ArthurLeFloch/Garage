-- =====================
--   Database : Garage
-- =====================

\echo '========================'
\echo '  REMOVE PREVIOUS DATA'
\echo '========================'

TRUNCATE    clients,
			employees,
			employees_work_histories,
			extern_garages,
			extern_maintenances,
			extern_maintenances_interventions,
			interventions,
			maintenances,
			maintenances_interventions,
			maintenances_parts,
			manufacturers,
			model_types,
			model_types_interventions_prices,
			models,
			models_interventions_prices,
			part_types,
			parts,
			prescriptions,
			prescriptions_interventions,
			recurrent_maintenances,
			recurrent_maintenances_interventions,
			-- recurrent_maintenances_model_types,
			-- recurrent_maintenances_models,
			recurrent_maintenances_parts,
			timeslots_dh,
			timeslots_ym,
			work_durations,
			vehicles
			RESTART IDENTITY
			CASCADE;

-- ================
--   Add new data
-- ================

\echo '==============='
\echo '  INSERT DATA'
\echo '==============='

------------------------------
--   filling identities data
------------------------------

-- people data

-- CALL insert_client (
-- 	insert_client_first_name VARCHAR(255),
-- 	insert_client_last_name VARCHAR(255),
-- 	insert_client_address VARCHAR(255),
-- 	insert_client_email VARCHAR(255),
-- 	insert_client_mobile VARCHAR(255)
-- )

CALL insert_client (
	'Michel',
	'de Montaigne',
	'25 Rue de la Liberté, Bordeaux',
	'michel.montaigne@gmail.com',
	'331 23 45 67 89'
);
CALL insert_client (
	'Simone',
	'de Beauvoir',
	'10 Avenue des Vignes, Pessac',
	'simone.debeauvoir@orange.fr',
	'334 56 78 90 12'
);
CALL insert_client (
	'Michel',
	'Foucault',
	'15 Chemin des Chênes, Talence',
	'michel.foucault@free.fr',
	'333 45 67 89 01'
);
CALL insert_client (
	'George',
	'Sand',
	'10 Rue Victor Hugo, Bordeaux',
	'george.sand@gmail.com',
	'335 67 89 01 23'
);
CALL insert_client (
	'Jacob',
	'Grimm',
	'21 Cours Alsace-et-Lorraine, Bordeaux',
	'jacob.grimm@web.de',
	'335 12 89 43 65'
);
CALL insert_client (
	'Wilhelm',
	'Grimm',
	'12 Route de Berlin, Bruges',
	'wilhelm.grimm@web.de',
	'335 08 17 01 00'
);

-- CALL insert_employee (
-- 	insert_employee_first_name VARCHAR(255),
-- 	insert_employee_last_name VARCHAR(255),
-- 	insert_employee_address VARCHAR(255),
-- 	insert_employee_email VARCHAR(255),
-- 	insert_employee_mobile VARCHAR(255)
-- )

CALL insert_employee (
	'Victor',
	'Hugo',
	'8 Place du Marché, Saint-Médard-en-Jalles',
	'victor.hugo@gmail.com',
	'336 11 22 33 44'
);
CALL insert_employee (
	'Jean-Paul',
	'Sartre',
	'7 Allée des Roses, Gradignan',
	'jp.sartre@ens.fr',
	'336 22 33 44 55'
);
CALL insert_employee (
	'Emile',
	'Zola',
	'3 Rue de la Gare, Mérignac',
	'emile.zola@gmail.com',
	'333 33 44 55 66'
);

-- extern garages

-- CALL insert_extern_garage (
-- 	insert_extern_garage_name VARCHAR(255)
-- )

CALL insert_extern_garage ('Garage Génial');
CALL insert_extern_garage ('ReparAuto');

-- vehicles data

-- CALL insert_manufacturer (
-- 	insert_manufacturer_name VARCHAR(255)
-- )

CALL insert_manufacturer ('Renault');
CALL insert_manufacturer ('Peugeot');
CALL insert_manufacturer ('Citroen');

-- CALL insert_part_simplified (
--     insert_part_type_name VARCHAR(255),
--     insert_part_name VARCHAR(255),
--     insert_part_reference VARCHAR(255),
--     insert_unitary_price REAL
-- )

CALL insert_part_simplified ( 'Carburant',					'Essence',		NULL, 	1.8 );
CALL insert_part_simplified ( 'Carburant',					'Diesel',		NULL, 	1.7 );
CALL insert_part_simplified ( 'Liquide de refroidissement',	'Model 1',		NULL, 	3 );
CALL insert_part_simplified ( 'Liquide de refroidissement',	'Model 2',		NULL, 	7 );
CALL insert_part_simplified ( 'Suspension',					'Model 1',		NULL, 	3 );
CALL insert_part_simplified ( 'Suspension',					'Model 2', 		NULL, 	3 );
CALL insert_part_simplified ( 'Roue',						'195/55R16',	NULL, 	30 );
CALL insert_part_simplified ( 'Roue',						'205/55R17',	NULL, 	40 );
CALL insert_part_simplified ( 'Huile',						'Model 1',		NULL, 	30 );
CALL insert_part_simplified ('Huile',	                    'Model 2',  	NULL, 	30);

-- CALL insert_model_type (
-- 	    insert_model_type_name VARCHAR(255)
-- )

CALL insert_model_type ('Coupé');
CALL insert_model_type ('Berline');
CALL insert_model_type ('Familiale');
CALL insert_model_type ('SUV');

-- CALL insert_model_simplified (
-- 	    insert_model_name VARCHAR(255),
-- 	    insert_model_version VARCHAR(255),
-- 	    insert_model_fuel_name VARCHAR(255),
-- 	    insert_model_coolant_name VARCHAR(255),
-- 	    insert_model_suspension_name VARCHAR(255),
-- 	    insert_model_wheel_name VARCHAR(255),
-- 	    insert_model_oil_name VARCHAR(255),
-- 	    insert_model_manufacturer_name VARCHAR(255),
-- 	    insert_model_model_type_name VARCHAR(255)
-- )

CALL insert_model_simplified (
	'Scénic',
	'Annee 2020',
	'Essence',
	'Model 1',
	'Model 1',
	'195/55R16',
	'Model 1',
	'Renault',
	'Familiale'
);
CALL insert_model_simplified (
	'Twingo',
	'Annee 2020',
	'Essence',
	'Model 1',
	'Model 2',
	'195/55R16',
	'Model 1',
	'Renault',
	'Coupé'
);
CALL insert_model_simplified (
	'Capture',
	'Annee 2020',
	'Essence',
	'Model 1',
	'Model 1',
	'205/55R17',
	'Model 1',
	'Renault',
	'SUV'
);
CALL insert_model_simplified (
	'Capture',
	'Annee 2021',
	'Diesel',
	'Model 1',
	'Model 1',
	'205/55R17',
	'Model 1',
	'Renault',
	'SUV'
);
CALL insert_model_simplified (
	'208',
	'Annee 2019',
	'Diesel',
	'Model 1',
	'Model 1',
	'205/55R17',
	'Model 1',
	'Peugeot',
	'Berline'
);
CALL insert_model_simplified (
	'3008',
	'Annee 2019',
	'Essence',
	'Model 1',
	'Model 2',
	'205/55R17',
	'Model 1',
	'Peugeot',
	'SUV'
);
CALL insert_model_simplified (
	'3008',
	'Annee 2019',
	'Diesel',
	'Model 1',
	'Model 2',
	'205/55R17',
	'Model 1',
	'Peugeot',
	'SUV'
);
CALL insert_model_simplified (
	'3008',
	'Annee 2020',
	'Diesel',
	'Model 1',
	'Model 2',
	'205/55R17',
	'Model 1',
	'Peugeot',
	'SUV'
);
CALL insert_model_simplified (
	'3008',
	'Annee 2020',
	'Essence',
	'Model 1',
	'Model 2',
	'205/55R17',
	'Model 1',
	'Peugeot',
	'SUV'
);
CALL insert_model_simplified (
	'C4',
	'Annee 2022',
	'Essence',
	'Model 2',
	'Model 2',
	'195/55R16',
	'Model 2',
	'Citroen',
	'Berline'
);

-- CALL insert_vehicle (
--     insert_vin_number VARCHAR(255),
--     insert_plate_number VARCHAR(255),
--     insert_registration_date DATE,
--     insert_client_id INT,
--     insert_model_id INT
-- )

CALL insert_vehicle (
	'2023AB96305',
	'AB283ZQ',
	'12-JAN-22',
	1,
	10
);

CALL insert_vehicle (
	'2020AA25387',
	'AC102DC',
	'21-DEC-22',
	2,
	2
);

CALL insert_vehicle (
	'2017NV45236',
	'FE842EZ',
	'04-SEP-23',
	3,
	3
);

CALL insert_vehicle (
	'2015PG82870',
	'DQ221NF',
	'30-JUN-22',
	4,
	4
);

CALL insert_vehicle (
	'2012QS64203',
	'CD895WW',
	'14-JAN-21',
	5,
	5
);

CALL insert_vehicle (
	'2007RF02587',
	'LS021MR',
	'14-APR-20',
	6,
	6
);

CALL insert_vehicle (
	'2007RF56788',
	'MV343GB',
	'21-JAN-21',
	1,
	7
);

CALL insert_vehicle (
	'2000ZS14569',
	'LN020MF',
	'12-MAR-21',
	2,
	1
);

CALL insert_vehicle (
	'2001ND84791',
	'AZ991CN',
	'25-FEB-23',
	3,
	9
);


-- intervention / maintenance data

-- CALL insert_intervention (
-- 	    insert_intervention_name VARCHAR(255)
-- )

CALL insert_intervention ('Vidange huile moteur');
CALL insert_intervention ('Équilibrage des pneus');
CALL insert_intervention ('Vidange du liquide de refroidissement');
CALL insert_intervention ('Vidange du liquide de frein');
CALL insert_intervention ('Remplacement du filtre à carburant');
CALL insert_intervention ('Réparation de la suspension');
CALL insert_intervention ('Remplacement des bougies d''allumage');
CALL insert_intervention ('Remplacement capot');
CALL insert_intervention ('Remplacement des pare-chocs avant');

-- CALL insert_timeslot_ym (
-- 	insert_timeslot_ym_year_number INT,
-- 	insert_timeslot_ym_month_number INT
-- )

CALL insert_timeslot_ym (
	2023,
	8
);
CALL insert_timeslot_ym (
	2023,
	9
);
CALL insert_timeslot_ym (
	2023,
	10
);
CALL insert_timeslot_ym (
	2023,
	11
);


-- CALL insert_timeslot_dh (
-- 	    insert_timeslot_dh_day_of_month INT,
-- 	    insert_timeslot_dh_start_hour TIME
-- )

CALL insert_timeslot_dh (
	1,
	TIME '07:00:00'
);
CALL insert_timeslot_dh (
	1,
	TIME '08:00:00'
);
CALL insert_timeslot_dh (
	1,
	TIME '09:00:00'
);
CALL insert_timeslot_dh (
	1,
	TIME '10:00:00'
);
CALL insert_timeslot_dh (
	1,
	TIME '11:00:00'
);
CALL insert_timeslot_dh (
	1,
	TIME '12:00:00'
);
CALL insert_timeslot_dh (
	1,
	TIME '13:00:00'
);
CALL insert_timeslot_dh (
	1,
	TIME '14:00:00'
);
CALL insert_timeslot_dh (
	1,
	TIME '15:00:00'
);
CALL insert_timeslot_dh (
	1,
	TIME '16:00:00'
);
CALL insert_timeslot_dh (
	1,
	TIME '17:00:00'
);
CALL insert_timeslot_dh (
	2,
	TIME '07:00:00'
);
CALL insert_timeslot_dh (
	2,
	TIME '08:00:00'
);
CALL insert_timeslot_dh (
	2,
	TIME '09:00:00'
);

-- CALL insert_work_duration (
-- 	insert_work_duration_wd INTERVAL
-- )

CALL insert_work_duration (INTERVAL '15 minutes');
CALL insert_work_duration (INTERVAL '30 minutes');
CALL insert_work_duration (INTERVAL '1 hour');
CALL insert_work_duration (INTERVAL '2 hour');
CALL insert_work_duration (INTERVAL '3 hour');
CALL insert_work_duration (INTERVAL '4 hour');

-- finished maintenances

-- TODO: Add attribute maintenance_planed_date to check if work history
-- doesn't start before the maintenance was planned

-- CALL insert_maintenance_simplified (
-- 	    insert_vehicle_id INT,
-- 	    insert_planner_id INT,
-- 	    insert_planned_start_date VARCHAR(255),
-- 	    insert_time_needed INTERVAL,
-- 	    insert_was_canceled BOOLEAN,
-- 	    insert_mileage_vehicle INT,
-- 	    insert_total_price INT,
-- 	    insert_is_finished BOOLEAN,
-- 	    insert_maintenance_note VARCHAR(255),
-- 	    insert_maintenance_name VARCHAR(255)
-- )
CALL insert_maintenance_simplified (
	1,
	1,
	TO_DATE('10/10/2023', 'DD/MM/YYYY'),
	INTERVAL '3 hours',
	FALSE,
	200000,
	400,
	FALSE,
	'RAS',
    'Remplacement du filtre à carburant'
);
CALL insert_maintenance_simplified (
	2,
	1,
	TO_DATE('20/10/2023', 'DD/MM/YYYY'),
	INTERVAL '1 hours',
	FALSE,
	150000,
	100,
	FALSE,
	'RAS',
    'Remplacement capot'
);
CALL insert_maintenance_simplified (
	3,
	3,
	TO_DATE('25/10/2023', 'DD/MM/YYYY'),
	INTERVAL '2 hours',
	FALSE,
	175000,
	200,
	FALSE,
	'RAS',
    'Remplacement capot'
);


-- ongoing maintenances

-- CALL insert_maintenance_simplified (
-- 	    insert_vehicle_id INT,
-- 	    insert_planner_id INT,
-- 	    insert_planned_start_date VARCHAR(255),
-- 	    insert_time_needed INTERVAL,
-- 	    insert_was_canceled BOOLEAN,
-- 	    insert_mileage_vehicle INT,
-- 	    insert_total_price INT,
-- 	    insert_is_finished BOOLEAN,
-- 	    insert_maintenance_note VARCHAR(255),
-- 	    insert_maintenance_name VARCHAR(255)
-- )

CALL insert_maintenance_simplified (
	4,
	1,
	TO_DATE('10/11/2023', 'DD/MM/YYYY'),
	INTERVAL '3 hours',
	FALSE,
	200000,
	400,
	FALSE,
	'',
    'Remplacement capot'
);

-- planned maintenances

-- CALL insert_maintenance_simplified (
-- 	    insert_vehicle_id INT,
-- 	    insert_planner_id INT,
-- 	    insert_planned_start_date VARCHAR(255),
-- 	    insert_time_needed INTERVAL,
-- 	    insert_was_canceled BOOLEAN,
-- 	    insert_mileage_vehicle INT,
-- 	    insert_total_price INT,
-- 	    insert_is_finished BOOLEAN,
-- 	    insert_maintenance_note VARCHAR(255),
-- 	    insert_maintenance_name VARCHAR(255)
-- )
CALL insert_maintenance_simplified (
	3,
	2,
	TO_DATE('10/01/2024', 'DD/MM,YYYY'),
	INTERVAL '5 hours',
	FALSE,
	200000,
	3200,
	FALSE,
	'',
	'Remplacement capot'
);

CALL insert_maintenance_simplified (
	3,
	2,
	TO_DATE('10/04/2024', 'DD/MM,YYYY'),
	INTERVAL '5 hours',
	FALSE,
	200000,
	3200,
	FALSE,
	'',
	'Remplacement des pare-chocs avant'
);

-- external maintenances

-- CALL insert_extern_maintenance (
--     insert_vehicle_id INT,
--     insert_extern_garage_name VARCHAR(255),
--     insert_start_date DATE,
--     insert_end_date DATE,
--     insert_note VARCHAR(255),
--     insert_intervention_name VARCHAR(255)
-- )

CALL insert_extern_maintenance (
    1,
    'Garage Génial',
    TO_DATE('10/10/2023', 'DD/MM/YYYY'),
    TO_DATE('10/10/2023', 'DD/MM/YYYY'),
    'Capuchon remplacé par le garage externe',
    'Vidange huile moteur'
);


-- keep that as an example that 1 wrong prevent all from being inserted
-- raise trigger exeption

-- CALL insert_prescription_simplified (
-- 	    insert_vehicle_id VARCHAR(255)
-- 	    insert_employee_id INT,
-- 	    insert_date DATE,
-- 	    insert_to_do_before_date DATE,
--      insert_intervention_name VARCHAR(255)
-- )

CALL insert_prescription_simplified (
	1,
	1,
    '2023-08-01',
	'2023-12-31',
    'Vidange huile moteur'
);
CALL insert_prescription_simplified (
	2,
	2,
    '2023-09-01',
	'2023-12-31',
	'Équilibrage des pneus'
);
CALL insert_prescription_simplified (
    5,
    2,
    '2023-11-01',
    '2023-12-31',
    'Remplacement du filtre à carburant'
);



-------------------------------------
--   filling n-n relationships data
-------------------------------------


-- CALL insert_model_intervention_price (
-- 	    insert_model_intervention_price_model_id INT,
-- 	    insert_model_intervention_price_intervention_name VARCHAR(255),
-- 	    insert_model_intervention_price_estimated_price INT,
-- )



CALL insert_model_intervention_price ( 1, 'Vidange du liquide de frein', 400 );
CALL insert_model_intervention_price ( 1, 'Remplacement du filtre à carburant', 500 );
CALL insert_model_intervention_price ( 1, 'Réparation de la suspension', 600 );
CALL insert_model_intervention_price ( 1, 'Remplacement des bougies d''allumage', 700 );
CALL insert_model_intervention_price ( 1, 'Remplacement capot', 800 );
CALL insert_model_intervention_price ( 1, 'Remplacement des pare-chocs avant', 900 );
CALL insert_model_intervention_price ( 2, 'Vidange huile moteur', 150 );
CALL insert_model_intervention_price ( 2, 'Vidange du liquide de frein', 400 );
CALL insert_model_intervention_price ( 2, 'Remplacement du filtre à carburant', 500 );
CALL insert_model_intervention_price ( 2, 'Réparation de la suspension', 600 );
CALL insert_model_intervention_price ( 2, 'Remplacement des bougies d''allumage', 700 );
CALL insert_model_intervention_price ( 2, 'Remplacement capot', 800 );
CALL insert_model_intervention_price ( 2, 'Remplacement des pare-chocs avant', 900 );
CALL insert_model_intervention_price ( 3, 'Vidange du liquide de frein', 400 );
CALL insert_model_intervention_price ( 3, 'Remplacement du filtre à carburant', 500 );
CALL insert_model_intervention_price ( 3, 'Réparation de la suspension', 600 );
CALL insert_model_intervention_price ( 3, 'Remplacement des bougies d''allumage', 700 );
CALL insert_model_intervention_price ( 3, 'Remplacement capot', 800 );
CALL insert_model_intervention_price ( 3, 'Remplacement des pare-chocs avant', 900 );
CALL insert_model_intervention_price ( 4, 'Équilibrage des pneus', 200 );
CALL insert_model_intervention_price ( 4, 'Vidange du liquide de frein', 400 );
CALL insert_model_intervention_price ( 4, 'Remplacement du filtre à carburant', 500 );
CALL insert_model_intervention_price ( 4, 'Réparation de la suspension', 600 );
CALL insert_model_intervention_price ( 4, 'Remplacement des bougies d''allumage', 700 );
CALL insert_model_intervention_price ( 4, 'Remplacement capot', 800 );
CALL insert_model_intervention_price ( 4, 'Remplacement des pare-chocs avant', 900 );

-- CALL insert_model_type_intervention_price (
-- 	    insert_model_type_intervention_price_model_id INT,
-- 	    insert_model_type_intervention_price_intervention_name VARCHAR(255),
-- 	    insert_model_type_intervention_price_estimated_price INT
-- )

CALL insert_model_type_intervention_price ( 1, 'Vidange huile moteur', 60 );
CALL insert_model_type_intervention_price ( 1, 'Équilibrage des pneus', 150 );
CALL insert_model_type_intervention_price ( 1, 'Vidange du liquide de refroidissement', 70 );
CALL insert_model_type_intervention_price ( 1, 'Vidange du liquide de frein', 70 );
CALL insert_model_type_intervention_price ( 2, 'Vidange huile moteur', 60 );
CALL insert_model_type_intervention_price ( 2, 'Équilibrage des pneus', 150 );
CALL insert_model_type_intervention_price ( 2, 'Vidange du liquide de refroidissement', 80 );
CALL insert_model_type_intervention_price ( 2, 'Vidange du liquide de frein', 80 );
CALL insert_model_type_intervention_price ( 3, 'Vidange huile moteur', 70 );
CALL insert_model_type_intervention_price ( 3, 'Vidange du liquide de refroidissement', 80 );
CALL insert_model_type_intervention_price ( 3, 'Équilibrage des pneus', 170 );
CALL insert_model_type_intervention_price ( 4, 'Vidange huile moteur', 70 );
CALL insert_model_type_intervention_price ( 4, 'Équilibrage des pneus', 170 );
CALL insert_model_type_intervention_price ( 4, 'Vidange du liquide de refroidissement', 90 );


-- adding interventions to existing maintenances
-- CALL insert_maintenance_intervention (
--      insert_maintenance_intervention_maintenance_id INT,
--      insert_maintenance_intervention_intervention_name VARCHAR(255)
-- )

CALL insert_maintenance_intervention_simplified ( 1, 'Remplacement capot' );


-- CALL insert_employee_work_history (
-- 	    insert_employee_work_history_maintenance_id INT,
-- 	    insert_employee_work_history_employee_id INT,
-- 	    insert_employee_work_history_year_number INT,
-- 	    insert_employee_work_history_month_number INT,
-- 	    insert_employee_work_history_day_of_month INT,
-- 	    insert_employee_work_history_start_hour TIME,
-- 	    insert_employee_work_history_work_duration INTERVAL
-- )

CALL insert_employee_work_history (
	1,
	3,
	2023,
	10,
	10,
	TIME '07:00:00',
	INTERVAL '30 minutes'
);
-- CALL insert_employee_work_history (
-- 	1,
-- 	3,
-- 	2023,
-- 	10,
-- 	10,
-- 	TIME '06:00:00',
-- 	INTERVAL '90 minutes'
-- );
CALL insert_employee_work_history (
	1,
	2,
	2023,
	10,
	11,
	TIME '07:00:00',
	INTERVAL '1 hour'
);
CALL insert_employee_work_history (
	2,
	3,
	2023,
	10,
	21,
	TIME '08:00:00',
	INTERVAL '30 minutes'
);
CALL insert_employee_work_history (
	3,
	3,
	2023,
	10,
	25,
	TIME '07:00:00',
	INTERVAL '30 minutes'
);
CALL insert_employee_work_history (
	4,
	2,
	2023,
	11,
	25,
	TIME '10:00:00',
	INTERVAL '30 minutes'
);

-- CALL insert_employee_work_history (
--     maintenance_id      INT,
--     employee_id         INT,
--     work_date           DATE,
--     start_time          TIME,
--     end_time            TIME
-- )

CALL insert_employee_work_history (
    1,
    2,
    '2023-10-10',
    TIME '07:00:00',
    TIME '07:30:00'
);

-- update_maintenance(
--     update_maintenance_id INT,
--     update_vehicle_id INT,
--     update_planner_id INT,
--     update_planned_start_date DATE,
--     update_planned_time_needed INTERVAL,
--     update_was_canceled BOOLEAN,
--     update_mileage_vehicle INT,
--     update_total_price INT,
--     update_is_finished BOOLEAN,
--     update_maintenance_note VARCHAR(255)
-- )

CALL update_maintenance (
    1,
	NULL,
	NULL,
	NULL,
	NULL,
	NULL,
	NULL,
	NULL,
	TRUE,
	NULL
);
CALL update_maintenance (
    2,
	NULL,
	NULL,
	NULL,
	NULL,
	NULL,
	NULL,
	NULL,
	TRUE,
	NULL
);
CALL update_maintenance (
    3,
	NULL,
	NULL,
	NULL,
	NULL,
	NULL,
	NULL,
	NULL,
	TRUE,
	NULL
);
-- CALL insert_maintenance_simplified (
--     2,
-- 	2,
-- 	1,
-- 	TO_DATE('20/10/2023', 'DD/MM/YYYY'),
-- 	INTERVAL '1 hours',
-- 	FALSE,
-- 	150000,
-- 	100,
-- 	FALSE,
-- 	'RAS'
-- );
-- CALL insert_maintenance_simplified (
--     3,
-- 	3,
-- 	3,
-- 	TO_DATE('25/10/2023', 'DD/MM/YYYY'),
-- 	INTERVAL '2 hours',
-- 	FALSE,
-- 	175000,
-- 	200,
-- 	FALSE,
-- 	'RAS',
--     'Remplacement capot'
-- );

-- recurrents

-- CALL insert_recurrent_maintenance_model_by_mileage_simplified (
-- 	    insert_milage_reapeat INT,
--      insert_model_id INT,
--      insert_intervention_name VARCHAR(255)
-- )

CALL insert_recurrent_maintenance_model_by_mileage_simplified( 15000, 1, 'Remplacement des bougies d''allumage' );
CALL insert_recurrent_maintenance_model_by_mileage_simplified( 20000, 2, 'Remplacement du filtre à carburant' );
CALL insert_recurrent_maintenance_model_by_mileage_simplified( 15000, 3, 'Vidange huile moteur' );
CALL insert_recurrent_maintenance_model_by_mileage_simplified( 20000, 4, 'Vidange du liquide de frein' );
CALL insert_recurrent_maintenance_model_by_mileage_simplified( 20000, 3, 'Vidange du liquide de frein' );
CALL insert_recurrent_maintenance_model_by_mileage_simplified( 5000, 5, 'Vidange huile moteur' );

-- CALL insert_recurrent_maintenance_model_type_by_mileage_simplified (
-- 	    insert_milage_reapeat INT,
--      insert_model_type_id INT,
--      insert_intervention_name VARCHAR(255)
-- )
CALL insert_recurrent_maintenance_model_type_by_mileage_simplified( 10000, 1, 'Vidange huile moteur' );
CALL insert_recurrent_maintenance_model_type_by_mileage_simplified( 10000, 3, 'Vidange huile moteur' );
CALL insert_recurrent_maintenance_model_type_by_mileage_simplified( 10000, 4, 'Vidange huile moteur' );
CALL insert_recurrent_maintenance_model_type_by_mileage_simplified( 30000, 1, 'Équilibrage des pneus' );
CALL insert_recurrent_maintenance_model_type_by_mileage_simplified( 30000, 3, 'Équilibrage des pneus' );
CALL insert_recurrent_maintenance_model_type_by_mileage_simplified( 30000, 4, 'Équilibrage des pneus' );
CALL insert_recurrent_maintenance_model_type_by_mileage_simplified( 30000, 2, 'Équilibrage des pneus' );


-- CALL insert_recurrent_maintenance_model_by_days_simplified (
-- 	    insert_days_after_last_maintenance INT,
--      insert_model_id INT,
--      insert_intervention_name VARCHAR(255)
-- )

CALL insert_recurrent_maintenance_model_by_days_simplified( 300, 1, 'Vidange du liquide de frein' );
CALL insert_recurrent_maintenance_model_by_days_simplified( 200, 2, 'Vidange du liquide de frein' );
CALL insert_recurrent_maintenance_model_by_days_simplified( 500, 5, 'Vidange du liquide de frein' );
CALL insert_recurrent_maintenance_model_by_days_simplified( 150, 1, 'Vidange du liquide de frein' );
CALL insert_recurrent_maintenance_model_by_days_simplified( 300, 4, 'Vidange du liquide de frein' );

-- CALL insert_recurrent_maintenance_model_type_by_days_simplified (
-- 	    insert_days_after_last_maintenance INT,
--      insert_model_type_id INT,
--      insert_intervention_name VARCHAR(255)
-- )

CALL insert_recurrent_maintenance_model_type_by_days_simplified( 400, 2, 'Remplacement du filtre à carburant' );
CALL insert_recurrent_maintenance_model_type_by_days_simplified( 400, 1, 'Remplacement du filtre à carburant' );
CALL insert_recurrent_maintenance_model_type_by_days_simplified( 400, 4, 'Remplacement du filtre à carburant' );
CALL insert_recurrent_maintenance_model_type_by_days_simplified( 400, 3, 'Remplacement du filtre à carburant' );

-- adding interventions to existing recurrent maintenances
-- CALL insert_recurrent_maintenance_intervention_simplified (
-- 	insert_recurrent_maintenance_id INT,
-- 	insert_intervention_name VARCHAR(255)
-- )
CALL insert_recurrent_maintenance_intervention_simplified ( 4, 'Réparation de la suspension' );
CALL insert_recurrent_maintenance_intervention_simplified ( 9, 'Réparation de la suspension' );
CALL insert_recurrent_maintenance_intervention_simplified ( 12, 'Réparation de la suspension' );
CALL insert_recurrent_maintenance_intervention_simplified ( 16, 'Réparation de la suspension' );

-- CALL insert_recurrent_maintenance_part (
-- 	    insert_recurrent_maintenance_part_maintenance_id INT,
-- 	    insert_recurrent_maintenance_part_type_name VARCHAR(255),
-- 	    insert_recurrent_maintenance_part_part_name VARCHAR(255),
-- 	    insert_recurrent_maintenance_part_nb INT
-- )

CALL insert_recurrent_maintenance_part (
	1,
	'Carburant',
	'Essence',
	100
);
CALL insert_recurrent_maintenance_part (
	1,
	'Carburant',
	'Diesel',
	115
);
CALL insert_recurrent_maintenance_part (
	8,
	'Liquide de refroidissement',
	'Model 1',
	75
);
CALL insert_recurrent_maintenance_part (
	8,
	'Liquide de refroidissement',
	'Model 2',
	80
);
CALL insert_recurrent_maintenance_part (
	5,
	'Suspension',
	'Model 1',
	1000
);
CALL insert_recurrent_maintenance_part (
	5,
	'Suspension',
	'Model 2',
	1200
);
CALL insert_recurrent_maintenance_part (
	6,
	'Roue',
	'195/55R16',
	400
);
CALL insert_recurrent_maintenance_part (
	7,
	'Roue',
	'205/55R17',
	500
);
CALL insert_recurrent_maintenance_part (
	6,
	'Huile',
	'Model 1',
	35
);
CALL insert_recurrent_maintenance_part (
	7,
	'Huile',
	'Model 2',
	62
);

-- =========================
--   Insertion Validations
-- =========================

SELECT COUNT(*), '= 6 ?' as expected, 'clients' as "table" FROM clients
UNION
SELECT COUNT(*),'= 3 ?', 'employees' FROM employees
UNION
SELECT COUNT(*),'= 3 ?', 'manufacturers' FROM manufacturers
UNION
SELECT COUNT(*),'= 5 ?', 'part_types' FROM part_types
UNION
SELECT COUNT(*),'= 10 ?', 'parts' FROM parts
UNION
SELECT COUNT(*),'= 4 ?', 'model_types' FROM model_types
UNION
SELECT COUNT(*),'= 10 ?', 'models' FROM models
UNION
SELECT COUNT(*),'= 9 ?', 'vehicles' FROM vehicles
UNION
SELECT COUNT(*),'= 9 ?', 'interventions' FROM interventions
UNION
SELECT COUNT(*),'= 3 ?', 'prescriptions' FROM prescriptions
UNION
SELECT COUNT(*),'= 23 ?', 'recurrent_maintenances' FROM recurrent_maintenances
UNION
SELECT COUNT(*),'= 3 ?', 'prescriptions_interventions' FROM prescriptions_interventions
UNION
SELECT COUNT(*),'= 26 ?', 'models_interventions_prices' FROM models_interventions_prices
UNION
SELECT COUNT(*),'= 14 ?', 'model_types_interventions_prices' FROM model_types_interventions_prices
UNION
SELECT COUNT(*),'= 10 ?', 'recurrent_maintenances_parts' FROM recurrent_maintenances_parts
UNION
SELECT COUNT(*),'= 6 ?', 'employees_work_histories' FROM employees_work_histories
UNION
SELECT COUNT(*),'= 5 ?', 'maintenances' FROM maintenances
UNION
SELECT COUNT(*),'= 27 ?', 'recurrent_maintenances_interventions' FROM recurrent_maintenances_interventions
UNION
SELECT COUNT(*),'= 2 ?', 'extern_garages' FROM extern_garages
UNION
SELECT COUNT(*),'= 1 ?', 'extern_maintenances' FROM extern_maintenances
ORDER BY "table";
