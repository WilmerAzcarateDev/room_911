DROP PROCEDURE IF EXISTS sp_importar_datos;

CREATE PROCEDURE sp_importar_datos()
BEGIN
    -- Insertar los datos en la tabla users usando la tabla temporal 'temp_import'
    INSERT INTO users (document, name, last_name, email, password, production_departament_id, created_at, updated_at)
    SELECT 
       t.DOCUMENT,
       t.EMPLOYEE_NAME,
       t.EMPLOYEE_LAST_NAME,
       t.EMPLOYEE_EMAIL,
       '$2y$12$wN0pwIFyFvNLg6CwZqinJ.Hz3Eym4R3b7vlRkcM4B3QblLMLQPSOC' AS password,
       pd.id,
       NOW(),
       NOW()
    FROM temp_import t
    LEFT JOIN production_departaments pd 
       ON pd.name = t.PRODUCTION_DEPARTAMENT;

    -- Asignar el rol "employee" a los usuarios insertados
    INSERT INTO model_has_roles (role_id, model_type, model_id)
    SELECT 
      r.id,
      'App\\Models\\User',
      u.id
    FROM users u
    JOIN temp_import t ON u.document = t.DOCUMENT
    CROSS JOIN (SELECT id FROM roles WHERE name = 'employee' LIMIT 1) r;

    -- Eliminar la tabla temporal
    DROP TEMPORARY TABLE temp_import;
END;