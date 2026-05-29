-- =====================
--   Database : Garage
-- =====================

\echo '==============='
\echo '  DROP TABLES'
\echo '==============='

DROP TABLE IF EXISTS    clients,
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
						recurrent_maintenances_parts,
						timeslots_dh,
						timeslots_ym,
						work_durations,
						vehicles
						CASCADE;

-- \echo '================================='
-- \echo '  DROP PROCEDURES AND FUNCTIONS'
-- \echo '================================='


-- Too dangerous to run in production
-- and not necessary because "CREATE OR REPLACE" is used
-- DO
-- $do$
-- DECLARE
--    _sql text;
-- BEGIN
-- 	SELECT INTO _sql
-- 		string_agg(format('DROP %s %s;'
-- 							, CASE prokind
-- 								WHEN 'f' THEN 'FUNCTION'
-- 								WHEN 'a' THEN 'AGGREGATE'
-- 								WHEN 'p' THEN 'PROCEDURE'
-- 								WHEN 'w' THEN 'FUNCTION'  -- window function (rarely applicable)
-- 								-- EL zsSE NULL              -- not possible in pg 11
-- 								END
-- 							, oid::regprocedure)
-- 					, E'\n')
-- 	FROM   pg_proc
-- 	WHERE  pronamespace = 'public'::regnamespace  -- schema name here!
-- 	-- AND    prokind = ANY ('{f,a,p,w}')         -- optionally filter kinds
-- 	;

-- 	IF _sql IS NOT NULL AND _sql <> '_schema' THEN
-- 		--   RAISE NOTICE '%', _sql;  -- debug / check first
-- 		EXECUTE _sql;         -- uncomment payload once you are sure
-- 	ELSE
-- 		RAISE NOTICE 'No fuctions found in schema %', quote_ident(_schema);
-- 	END IF;
-- END
-- $do$;

