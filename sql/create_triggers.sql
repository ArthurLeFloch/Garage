-- =====================
--   Database : Garage
-- =====================

\echo '==================='
\echo '  CREATE TRIGGERS'
\echo '==================='


-- FONCTIONS FOR TRIGGERS

-- enforce rule: A `vehicle` cannot have 2 or more planned `maintenance` with
-- overlapping  (planned_start_date, planned_time_needed).
CREATE OR REPLACE FUNCTION check_no_overlapping_maintenance_trigger()
RETURNS TRIGGER AS $$
BEGIN
	IF EXISTS (
		SELECT 1
		FROM maintenances
		WHERE NEW.vehicle_id = maintenances.vehicle_id
		  AND NEW.maintenance_id <> maintenances.maintenance_id
		  AND (
			  NEW.planned_start_date, NEW.planned_start_date + NEW.planned_time_needed
		  ) OVERLAPS (
			  maintenances.planned_start_date, maintenances.planned_start_date + maintenances.planned_time_needed
		  )
	) THEN
		RAISE EXCEPTION 'Overlapping maintenance schedule for maintenance_id: %', NEW.maintenance_id;
	END IF;
	RETURN NEW;
END;
$$ LANGUAGE plpgsql;


-- ACTUAL TRIGGERS

CREATE TRIGGER maintenance_overlap_check
BEFORE INSERT OR UPDATE
ON maintenances
FOR EACH ROW
EXECUTE FUNCTION check_no_overlapping_maintenance_trigger();
