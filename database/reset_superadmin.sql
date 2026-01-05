-- Reset Superadmin Account
-- Username: super
-- Password: super123 (MD5 hash)

-- Delete all existing superadmin accounts
DELETE FROM tb_superadmin;

-- Insert new superadmin account
-- MD5 hash of 'super123' = f35364bc808b079853de5a1e343e7159
INSERT INTO tb_superadmin (username, password, nama_lengkap, email, created_at) VALUES
('super', 'f35364bc808b079853de5a1e343e7159', 'Super Administrator', 'super@ppdb-smk.id', NOW());

-- Verify
SELECT * FROM tb_superadmin;
