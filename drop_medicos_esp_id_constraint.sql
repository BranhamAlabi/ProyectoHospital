-- First, check what foreign key constraints exist on the medicos table
SHOW CREATE TABLE medicos;

-- Drop the foreign key constraint (try different possible names)
ALTER TABLE medicos DROP FOREIGN KEY fk_medicos_esp_id;

-- If the above doesn't work, try this alternative:
-- ALTER TABLE medicos DROP FOREIGN KEY medicos_esp_id_foreign;

-- Drop the index
ALTER TABLE medicos DROP INDEX fk_medicos_esp_id;

-- Finally, drop the column
ALTER TABLE medicos DROP COLUMN esp_id;
