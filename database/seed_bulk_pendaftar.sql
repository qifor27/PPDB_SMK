-- =====================================================
-- SEED DATA: 50 Pendaftar per SMK (Total 750 siswa)
-- Status: submitted
-- Generated: 2026-01-04
-- =====================================================

SET FOREIGN_KEY_CHECKS=0;

-- =====================================================
-- GENERATE 750 SISWA (50 per sekolah x 15 sekolah)
-- =====================================================

-- Helper variables
SET @start_nisn = 1000000000;
SET @tahun_ajaran = '2025/2026';

-- Daftar nama depan
SET @nama_depan_l = 'Adi,Agus,Ahmad,Aldi,Andi,Arifin,Bagas,Bagus,Bima,Dani,Dika,Dimas,Eko,Fajar,Farhan,Fauzi,Galih,Gilang,Hafiz,Hendra,Ilham,Irfan,Joko,Kevin,Lutfi,Maulana,Naufal,Oki,Putra,Rafi,Rama,Rangga,Rendi,Rizki,Satria,Teguh,Umar,Vino,Wahyu,Yoga,Yuda,Zaki,Arif,Budi,Cahyo,Dwi,Erik,Firman,Guntur,Haris';
SET @nama_depan_p = 'Aini,Aisyah,Amanda,Amelia,Anisa,Aulia,Bunga,Citra,Devi,Diana,Dinda,Dwi,Elsa,Erna,Fitri,Gita,Hana,Indah,Intan,Jasmine,Kartika,Laila,Lestari,Maya,Mega,Nadia,Nia,Nova,Nur,Okta,Putri,Qori,Rani,Ratna,Rina,Rosa,Sari,Sinta,Sri,Tia,Uli,Vina,Wati,Winda,Yani,Yuli,Zahra,Zara,Bella,Cantik';

-- Daftar nama belakang
SET @nama_belakang = 'Pratama,Saputra,Wijaya,Kusuma,Putra,Wibowo,Setiawan,Hidayat,Ramadhan,Nugraha,Kurniawan,Santoso,Firmansyah,Hakim,Maulana,Rahman,Hasan,Akbar,Syahputra,Aditya,Permana,Utama,Prabowo,Suryadi,Mahendra';

-- =====================================================
-- INSERT SISWA DAN PENDAFTARAN UNTUK SETIAP SMK
-- =====================================================

DELIMITER //

DROP PROCEDURE IF EXISTS generate_dummy_data//

CREATE PROCEDURE generate_dummy_data()
BEGIN
    DECLARE i INT DEFAULT 1;
    DECLARE j INT DEFAULT 1;
    DECLARE smk_id INT;
    DECLARE nisn_val VARCHAR(20);
    DECLARE username_val VARCHAR(50);
    DECLARE nama_val VARCHAR(150);
    DECLARE gender_val CHAR(1);
    DECLARE lat_val DECIMAL(10,8);
    DECLARE lng_val DECIMAL(11,8);
    DECLARE jarak_val DECIMAL(10,2);
    DECLARE nilai_val DECIMAL(5,2);
    DECLARE nomor_daftar VARCHAR(30);
    DECLARE siswa_id INT;
    DECLARE kejuruan1 INT;
    DECLARE kejuruan2 INT;
    DECLARE jalur_id INT;
    
    -- Array of SMK IDs
    DECLARE smk_ids VARCHAR(100) DEFAULT '1,2,3,4,6,7,8,9,10,11,12,13,14,15';
    
    -- Loop through each SMK
    SET j = 1;
    WHILE j <= 14 DO
        -- Get SMK ID
        SET smk_id = CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(smk_ids, ',', j), ',', -1) AS UNSIGNED);
        
        -- Generate 50 students per SMK
        SET i = 1;
        WHILE i <= 50 DO
            -- Generate unique values
            SET nisn_val = CONCAT('20260', LPAD(smk_id, 2, '0'), LPAD(i, 4, '0'));
            SET username_val = CONCAT('siswa_smk', smk_id, '_', i);
            
            -- Random gender
            SET gender_val = IF(RAND() > 0.5, 'L', 'P');
            
            -- Generate name based on gender
            IF gender_val = 'L' THEN
                SET nama_val = CONCAT(
                    ELT(FLOOR(1 + RAND() * 50), 'Adi','Agus','Ahmad','Aldi','Andi','Arifin','Bagas','Bagus','Bima','Dani','Dika','Dimas','Eko','Fajar','Farhan','Fauzi','Galih','Gilang','Hafiz','Hendra','Ilham','Irfan','Joko','Kevin','Lutfi','Maulana','Naufal','Oki','Putra','Rafi','Rama','Rangga','Rendi','Rizki','Satria','Teguh','Umar','Vino','Wahyu','Yoga','Yuda','Zaki','Arif','Budi','Cahyo','Dwi','Erik','Firman','Guntur','Haris'),
                    ' ',
                    ELT(FLOOR(1 + RAND() * 25), 'Pratama','Saputra','Wijaya','Kusuma','Putra','Wibowo','Setiawan','Hidayat','Ramadhan','Nugraha','Kurniawan','Santoso','Firmansyah','Hakim','Maulana','Rahman','Hasan','Akbar','Syahputra','Aditya','Permana','Utama','Prabowo','Suryadi','Mahendra')
                );
            ELSE
                SET nama_val = CONCAT(
                    ELT(FLOOR(1 + RAND() * 50), 'Aini','Aisyah','Amanda','Amelia','Anisa','Aulia','Bunga','Citra','Devi','Diana','Dinda','Dwi','Elsa','Erna','Fitri','Gita','Hana','Indah','Intan','Jasmine','Kartika','Laila','Lestari','Maya','Mega','Nadia','Nia','Nova','Nur','Okta','Putri','Qori','Rani','Ratna','Rina','Rosa','Sari','Sinta','Sri','Tia','Uli','Vina','Wati','Winda','Yani','Yuli','Zahra','Zara','Bella','Cantik'),
                    ' ',
                    ELT(FLOOR(1 + RAND() * 25), 'Pratama','Saputra','Wijaya','Kusuma','Putri','Wibowo','Setiawan','Hidayat','Ramadhan','Nugraha','Kurniawan','Santoso','Firmansyah','Hakim','Maharani','Rahman','Hasan','Safira','Syahputri','Aditya','Permata','Utami','Lestari','Suryani','Anggraini')
                );
            END IF;
            
            -- Random coordinates around Padang
            SET lat_val = -0.85 - (RAND() * 0.15);
            SET lng_val = 100.30 + (RAND() * 0.18);
            
            -- Random distance 0.5-10 km
            SET jarak_val = 0.5 + (RAND() * 9.5);
            
            -- Random nilai 70-95
            SET nilai_val = 70 + (RAND() * 25);
            
            -- Random jalur (1-4)
            SET jalur_id = FLOOR(1 + RAND() * 4);
            
            -- Insert siswa
            INSERT INTO tb_siswa (
                nisn, username, password, email, nama_lengkap,
                jenis_kelamin, tempat_lahir, tanggal_lahir, agama,
                alamat, kelurahan, kecamatan, kota, provinsi,
                latitude, longitude, no_hp,
                nama_ayah, pekerjaan_ayah, nama_ibu, pekerjaan_ibu,
                asal_sekolah, tahun_lulus
            ) VALUES (
                nisn_val, username_val, MD5('siswa123'), 
                CONCAT(username_val, '@gmail.com'), nama_val,
                gender_val, 'Padang', DATE_SUB(CURDATE(), INTERVAL (15 + FLOOR(RAND()*2)) YEAR), 'Islam',
                CONCAT('Jl. Dummy No. ', i), 'Kelurahan Dummy', 'Kecamatan Dummy', 'Padang', 'Sumatera Barat',
                lat_val, lng_val, CONCAT('08', FLOOR(1000000000 + RAND() * 9000000000)),
                CONCAT('Bapak ', nama_val), 'Wiraswasta', CONCAT('Ibu ', nama_val), 'Ibu Rumah Tangga',
                CONCAT('SMP Negeri ', FLOOR(1 + RAND() * 30), ' Padang'), '2025'
            );
            
            SET siswa_id = LAST_INSERT_ID();
            
            -- Get random kejuruan for this SMK
            SELECT id_program INTO kejuruan1 FROM tb_kejuruan WHERE id_smk = smk_id ORDER BY RAND() LIMIT 1;
            SELECT id_program INTO kejuruan2 FROM tb_kejuruan WHERE id_smk = smk_id AND id_program != IFNULL(kejuruan1, 0) ORDER BY RAND() LIMIT 1;
            
            -- Generate nomor pendaftaran
            SET nomor_daftar = CONCAT('PPDB2026-S', LPAD(smk_id, 2, '0'), '-', LPAD(i, 4, '0'));
            
            -- Insert pendaftaran
            INSERT INTO tb_pendaftaran (
                nomor_pendaftaran, id_siswa, id_smk_pilihan1, id_smk_pilihan2,
                id_kejuruan_pilihan1, id_kejuruan_pilihan2, id_jalur,
                jarak_ke_sekolah, nilai_rata_rata, status, tahun_ajaran
            ) VALUES (
                nomor_daftar, siswa_id, smk_id, NULL,
                kejuruan1, kejuruan2, jalur_id,
                jarak_val, nilai_val, 'submitted', '2025/2026'
            );
            
            SET i = i + 1;
        END WHILE;
        
        SET j = j + 1;
    END WHILE;
END//

DELIMITER ;

-- Execute the procedure
CALL generate_dummy_data();

-- Clean up
DROP PROCEDURE IF EXISTS generate_dummy_data;

SET FOREIGN_KEY_CHECKS=1;

-- =====================================================
-- VERIFIKASI DATA
-- =====================================================
SELECT 'Summary per SMK:' as Info;
SELECT 
    s.nama_sekolah,
    COUNT(p.id_pendaftaran) as total_pendaftar
FROM tb_smk s
LEFT JOIN tb_pendaftaran p ON s.id_smk = p.id_smk_pilihan1 AND p.status = 'submitted'
GROUP BY s.id_smk, s.nama_sekolah
ORDER BY s.id_smk;

SELECT 'Total Data:' as Info;
SELECT COUNT(*) as total_siswa FROM tb_siswa;
SELECT COUNT(*) as total_pendaftaran FROM tb_pendaftaran WHERE status = 'submitted';
