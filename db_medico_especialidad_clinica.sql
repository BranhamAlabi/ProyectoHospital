-- Create especialidad table
CREATE TABLE especialidad (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    especialidad VARCHAR(150) NOT NULL
);

-- Modify medicos table: drop especialidad and clinica_id columns, add esp_id foreign key
ALTER TABLE medicos
    DROP COLUMN especialidad,
    DROP COLUMN clinica_id,
    ADD COLUMN esp_id BIGINT UNSIGNED NOT NULL,
    ADD CONSTRAINT fk_medicos_esp_id FOREIGN KEY (esp_id) REFERENCES especialidad(id);

-- Create pivot table medico_clinica to allow multiple clinics per medico
CREATE TABLE medico_clinica (
    medico_id BIGINT UNSIGNED NOT NULL,
    clinica_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (medico_id, clinica_id),
    FOREIGN KEY (medico_id) REFERENCES medicos(id) ON DELETE CASCADE,
    FOREIGN KEY (clinica_id) REFERENCES clinicas(id) ON DELETE CASCADE
);
