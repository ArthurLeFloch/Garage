-- =====================
--   Database : Garage
-- =====================

\echo '================================='
\echo '  CREATE PROCEDURES FOR DELETE'
\echo '================================='

CREATE OR REPLACE PROCEDURE delete_client (
	delete_client_id INT
)
LANGUAGE plpgsql
AS $$
BEGIN
	DELETE FROM clients WHERE client_id = delete_client_id;
END;
$$;


CREATE OR REPLACE PROCEDURE delete_vehicle (
	delete_vehicle_id INT
)
LANGUAGE plpgsql
AS $$
BEGIN
	DELETE FROM vehicles WHERE vehicle_id = delete_vehicle_id;
END;
$$;
