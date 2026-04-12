DELIMITER $$

DROP PROCEDURE IF EXISTS sync_absensi_schema $$
CREATE PROCEDURE sync_absensi_schema()
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'nisn'
    ) THEN
        ALTER TABLE siswa ADD COLUMN nisn VARCHAR(50) NULL AFTER nama_siswa;
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'kelas'
    ) THEN
        ALTER TABLE siswa ADD COLUMN kelas INT NULL AFTER id_kelas;
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'password'
    ) THEN
        ALTER TABLE siswa ADD COLUMN password VARCHAR(255) NULL AFTER nisn;
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'status'
    ) THEN
        ALTER TABLE siswa ADD COLUMN status ENUM('Aktif','Nonaktif') DEFAULT 'Aktif' AFTER password;
    END IF;

    IF EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'nis'
    ) THEN
        UPDATE siswa
        SET nisn = nis
        WHERE (nisn IS NULL OR nisn = '')
          AND nis IS NOT NULL;
    END IF;

    IF EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'id_kelas'
    ) THEN
        UPDATE siswa
        SET kelas = id_kelas
        WHERE kelas IS NULL
          AND id_kelas IS NOT NULL;
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'jadwal' AND COLUMN_NAME = 'mata_pelajaran'
    ) THEN
        ALTER TABLE jadwal ADD COLUMN mata_pelajaran VARCHAR(100) NULL AFTER id_kelas;
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'jadwal' AND COLUMN_NAME = 'nama_guru'
    ) THEN
        ALTER TABLE jadwal ADD COLUMN nama_guru VARCHAR(100) NULL AFTER mata_pelajaran;
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'jadwal' AND COLUMN_NAME = 'jam_mulai'
    ) THEN
        ALTER TABLE jadwal ADD COLUMN jam_mulai TIME NULL AFTER hari;
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'jadwal' AND COLUMN_NAME = 'jam_selesai'
    ) THEN
        ALTER TABLE jadwal ADD COLUMN jam_selesai TIME NULL AFTER jam_mulai;
    END IF;

    IF EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'jadwal' AND COLUMN_NAME = 'jam_masuk'
    ) AND EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'jadwal' AND COLUMN_NAME = 'jam_pulang'
    ) THEN
        UPDATE jadwal
        SET jam_mulai = COALESCE(jam_mulai, jam_masuk),
            jam_selesai = COALESCE(jam_selesai, jam_pulang);
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'absensi' AND COLUMN_NAME = 'status_kehadiran'
    ) THEN
        ALTER TABLE absensi ADD COLUMN status_kehadiran ENUM('Hadir','Terlambat','Alfa') NULL AFTER waktu_scan;
    END IF;

    IF EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'absensi' AND COLUMN_NAME = 'status'
    ) THEN
        UPDATE absensi
        SET status_kehadiran = CASE status
            WHEN 'hadir' THEN 'Hadir'
            WHEN 'terlambat' THEN 'Terlambat'
            WHEN 'tidak_hadir' THEN 'Alfa'
            ELSE status_kehadiran
        END
        WHERE status_kehadiran IS NULL;
    END IF;
END $$

CALL sync_absensi_schema() $$
DROP PROCEDURE sync_absensi_schema $$

DELIMITER ;
