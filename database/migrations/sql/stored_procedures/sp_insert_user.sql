CREATE PROCEDURE sp_insert_user(
    IN p_document VARCHAR(255),
    IN p_name VARCHAR(255),
    IN p_last_name VARCHAR(255),
    IN p_email VARCHAR(255),
    IN p_password VARCHAR(255),
    IN p_production_departament_name VARCHAR(255)
)
BEGIN
    DECLARE v_production_departament_id BIGINT;
    DECLARE v_role_id BIGINT;
    DECLARE v_user_id BIGINT;
    
    SELECT id INTO v_production_departament_id
      FROM production_departaments
      WHERE name = p_production_departament_name
      LIMIT 1;
    
    INSERT INTO users (document, name, last_name, email, password, production_departament_id, created_at, updated_at)
      VALUES (p_document, p_name, p_last_name, p_email, p_password, v_production_departament_id, NOW(), NOW());
    
    SET v_user_id = LAST_INSERT_ID();
    
    SELECT id INTO v_role_id
      FROM roles
      WHERE name = 'employee'
      LIMIT 1;
    
    INSERT INTO model_has_roles (role_id, model_type, model_id)
      VALUES (v_role_id, 'App\\Models\\User', v_user_id);
END;