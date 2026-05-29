CREATE OR REPLACE FUNCTION employee_is_free (
    var_employee_id         INT,
    var_work_date           DATE,
    var_start_time          TIME,
    var_end_time            TIME
)
RETURNS BOOLEAN
LANGUAGE plpgsql
AS
$$
DECLARE
    work_history_row RECORD;
BEGIN
    FOR work_history_row IN (
        SELECT *
        FROM employees_work_histories
        JOIN timeslots_ym USING (timeslot_ym_id)
        JOIN timeslots_dh USING (timeslot_dh_id)
        JOIN work_durations USING (work_duration_id)
        WHERE employees_work_histories.employee_id = var_employee_id
        AND (
            (var_work_date + var_start_time, var_work_date + var_end_time) OVERLAPS
            (
                MAKE_DATE(timeslots_ym.year_number, timeslots_ym.month_number, timeslots_dh.day_of_month)
                + start_hour::TIME,
                MAKE_DATE(timeslots_ym.year_number, timeslots_ym.month_number, timeslots_dh.day_of_month)
                + (start_hour + work_duration)::TIME
            )
        )
    ) LOOP
        -- Check for overlap with each entry
        IF true THEN
            -- You can add additional conditions here if needed
            RETURN FALSE;
        END IF;
    END LOOP;

    -- If no overlap is found, return true
    RETURN TRUE;
END;
$$;

CREATE OR REPLACE FUNCTION employee_is_free (
    var_maintenance_id          INT,
    var_employee_id         INT,
    var_work_date           DATE,
    var_start_time          TIME,
    var_end_time            TIME
)
RETURNS BOOLEAN
LANGUAGE plpgsql
AS
$$
DECLARE
    work_history_row RECORD;
BEGIN
    FOR work_history_row IN (
        SELECT *
        FROM employees_work_histories
        JOIN timeslots_ym USING (timeslot_ym_id)
        JOIN timeslots_dh USING (timeslot_dh_id)
        JOIN work_durations USING (work_duration_id)
        WHERE employees_work_histories.employee_id = var_employee_id
        AND employees_work_histories.maintenance_id <> var_maintenance_id
        AND (
            (var_work_date + var_start_time, var_work_date + var_end_time) OVERLAPS
            (
                MAKE_DATE(timeslots_ym.year_number, timeslots_ym.month_number, timeslots_dh.day_of_month)
                + start_hour::TIME,
                MAKE_DATE(timeslots_ym.year_number, timeslots_ym.month_number, timeslots_dh.day_of_month)
                + (start_hour + work_duration)::TIME
            )
        )
    ) LOOP
        -- Check for overlap with each entry
        IF true THEN
            -- You can add additional conditions here if needed
            RETURN FALSE;
        END IF;
    END LOOP;

    -- If no overlap is found, return true
    RETURN TRUE;
END;
$$;