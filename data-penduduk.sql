-- Data Sampel untuk Tabel Kartu Keluarga dan Penduduk
-- File ini berisi data contoh untuk keperluan pengujian sistem

-- Insert data ke tabel kartu_keluargas terlebih dahulu
-- Karena tabel penduduks memiliki foreign key ke no_kk

INSERT INTO kartu_keluargas (no_kk, kepala_keluarga, dusun, alamat, rt, rw, kode_pos, jenis_bangunan, pemakaian_air, jenis_bantuan, status_kesejahteraan, created_at, updated_at) VALUES
('3201011234560001', 'Budi Santoso', 'Dusun 1', 'Jl. Merdeka No. 10', '01', '01', '12345', 'Rumah Tinggal', 'PAM', 'BPNT', 'KS1', NOW(), NOW()),
('3201011234560002', 'Ahmad Wijaya', 'Dusun 1', 'Jl. Sudirman No. 25', '02', '01', '12345', 'Rumah Tinggal', 'Sumur Bor', 'PKH', 'KS2', NOW(), NOW()),
('3201011234560003', 'Siti Nurhaliza', 'Dusun 1', 'Jl. Gatotkaca No. 15', '03', '02', '12345', 'Rumah Tinggal', 'PAM', NULL, 'KS3', NOW(), NOW()),
('3201011234560004', 'Rudi Hermawan', 'Dusun 1', 'Jl. Pahlawan No. 7', '01', '02', '12345', 'Rumah Tinggal', 'Sumur Bor', 'BPNT', 'KS2', NOW(), NOW()),
('3201011234560005', 'Diana Putri', 'Dusun 2', 'Jl. Diponegoro No. 12', '02', '03', '12345', 'Rumah Tinggal', 'PAM', 'PKH', 'KS1', NOW(), NOW()),
('3201011234560006', 'Joko Prakoso', 'Dusun 2', 'Jl. Imam Bonjol No. 30', '04', '03', '12345', 'Rumah Kontrakan', 'Sumur', NULL, 'KS2', NOW(), NOW()),
('3201011234560007', 'Fitri Handayani', 'Dusun 2', 'Jl. Ahmad Yani No. 5', '01', '04', '12345', 'Rumah Tinggal', 'PAM', 'BPNT', 'KS3', NOW(), NOW()),
('3201011234560008', 'Andi Kusuma', 'Dusun 2', 'Jl. Sisingamangaraja No. 18', '03', '04', '12345', 'Rumah Tinggal', 'Sumur Bor', 'PKH', 'KS2', NOW(), NOW()),
('3201011234560009', 'Maya Sari', 'Dusun 3', 'Jl. Teuku Umar No. 22', '02', '05', '12345', 'Rumah Tinggal', 'PAM', NULL, 'KS1', NOW(), NOW()),
('3201011234560010', 'Hendra Wijaya', 'Dusun 3', 'Jl. Panglima Polim No. 8', '05', '05', '12345', 'Rumah Tinggal', 'Sumur', 'BPNT', 'KS3', NOW(), NOW());

-- Insert data ke tabel penduduks
-- Struktur akhir tabel penduduks berdasarkan migrasi:
-- - nik (primary key, 16 karakter)
-- - no_kk (foreign key ke tabel kartu_keluargas)
-- - nama_lengkap
-- - jenis_kelamin (L/P)
-- - tempat_lahir
-- - tanggal_lahir
-- - pendidikan_terakhir
-- - pekerjaan
-- - status_hubungan_dalam_keluarga
-- - jenis_bantuan (text, nullable)
-- - status_dasar (enum: HIDUP/MATI/PINDAH, default HIDUP)
-- - kewarganegaraan (string, default WNI)
-- - created_at
-- - updated_at

INSERT INTO penduduks (nik, no_kk, nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir, pendidikan_terakhir, pekerjaan, status_hubungan_dalam_keluarga, jenis_bantuan, status_dasar, kewarganegaraan, created_at, updated_at) VALUES
-- Keluarga 1 (Budi Santoso)
('3201011234560001', '3201011234560001', 'Budi Santoso', 'L', 'Jakarta', '1975-03-15', 'SMA', 'Pegawai Swasta', 'Kepala Keluarga', NULL, 'HIDUP', 'WNI', NOW(), NOW()),
('3201014567890001', '3201011234560001', 'Ratna Sari Dewi', 'P', 'Bandung', '1978-07-22', 'SMA', 'Ibu Rumah Tangga', 'Istri', 'BPNT', 'HIDUP', 'WNI', NOW(), NOW()),
('3201017890120001', '3201011234560001', 'Rizky Ahmad Pratama', 'L', 'Jakarta', '2005-01-10', 'SMA', 'Pelajar/Mahasiswa', 'Anak', 'BPNT', 'HIDUP', 'WNI', NOW(), NOW()),
('3201010123450001', '3201011234560001', 'Siti Nur Azizah', 'P', 'Jakarta', '2008-09-05', 'SMP', 'Pelajar', 'Anak', 'BPNT', 'HIDUP', 'WNI', NOW(), NOW()),

-- Keluarga 2 (Ahmad Wijaya)
('3201012345670001', '3201011234560002', 'Ahmad Wijaya', 'L', 'Surabaya', '1970-11-30', 'S1', 'PNS', 'Kepala Keluarga', 'PKH', 'HIDUP', 'WNI', NOW(), NOW()),
('3201015678900001', '3201011234560002', 'Fitria Aini', 'P', 'Malang', '1975-04-18', 'D3', 'Guru Swasta', 'Istri', 'PKH', 'HIDUP', 'WNI', NOW(), NOW()),
('3201018901230001', '3201011234560002', 'Muhammad Rizki', 'L', 'Surabaya', '2002-06-25', 'S1', 'Swasta', 'Anak', 'PKH', 'HIDUP', 'WNI', NOW(), NOW()),
('3201011234560002', '3201011234560002', 'Sarah Putri', 'P', 'Surabaya', '2006-12-15', 'SMA', 'Pelajar', 'Anak', 'PKH', 'HIDUP', 'WNI', NOW(), NOW()),
('3201013456780001', '3201011234560002', 'Abdul Malik', 'L', 'Surabaya', '2010-08-20', 'SD', 'Pelajar', 'Anak', 'PKH', 'HIDUP', 'WNI', NOW(), NOW()),

-- Keluarga 3 (Siti Nurhaliza)
('3201014567890002', '3201011234560003', 'Siti Nurhaliza', 'P', 'Yogyakarta', '1985-05-28', 'S1', 'Dokter', 'Kepala Keluarga', NULL, 'HIDUP', 'WNI', NOW(), NOW()),
('3201017890120002', '3201011234560003', 'Reza Fahlevi', 'L', 'Semarang', '1982-09-14', 'S1', 'Arsitek', 'Suami', NULL, 'HIDUP', 'WNI', NOW(), NOW()),

-- Keluarga 4 (Rudi Hermawan)
('3201015678900002', '3201011234560004', 'Rudi Hermawan', 'L', 'Medan', '1968-12-03', 'SMA', 'Petani', 'Kepala Keluarga', 'BPNT', 'HIDUP', 'WNI', NOW(), NOW()),
('3201018901230002', '3201011234560004', 'Dewi Lestari', 'P', 'Medan', '1972-07-08', 'SMP', 'Petani', 'Istri', 'BPNT', 'HIDUP', 'WNI', NOW(), NOW()),
('3201010123450002', '3201011234560004', 'Deni Pratama', 'L', 'Medan', '1998-02-20', 'SMK', 'Swasta', 'Anak', 'BPNT', 'HIDUP', 'WNI', NOW(), NOW()),
('3201012345670002', '3201011234560004', 'Dini Handayani', 'P', 'Medan', '2000-10-12', 'SMA', 'Mahasiswa', 'Anak', 'BPNT', 'HIDUP', 'WNI', NOW(), NOW()),
('3201013456780002', '3201011234560004', 'Doni Saputra', 'L', 'Medan', '2003-05-30', 'SMP', 'Pelajar', 'Anak', 'BPNT', 'HIDUP', 'WNI', NOW(), NOW()),

-- Keluarga 5 (Diana Putri)
('3201016789010001', '3201011234560005', 'Diana Putri', 'P', 'Palembang', '1980-08-15', 'S2', 'Dosen', 'Kepala Keluarga', 'PKH', 'HIDUP', 'WNI', NOW(), NOW()),
('3201019012340001', '3201011234560005', 'Rama Wijaya', 'L', 'Jakarta', '1978-03-22', 'S1', 'Programmer', 'Suami', 'PKH', 'HIDUP', 'WNI', NOW(), NOW()),
('3201011234560003', '3201011234560005', 'Rafif Ahmad', 'L', 'Palembang', '2010-11-08', 'SD', 'Pelajar', 'Anak', 'PKH', 'HIDUP', 'WNI', NOW(), NOW()),

-- Keluarga 6 (Joko Prakoso)
('3201017890120003', '3201011234560006', 'Joko Prakoso', 'L', 'Solo', '1965-06-10', 'SMP', 'Buruh Harian', 'Kepala Keluarga', NULL, 'HIDUP', 'WNI', NOW(), NOW()),
('3201010123450003', '3201011234560006', 'Sumarni', 'P', 'Solo', '1970-01-25', 'SD', 'Penjual Sayur', 'Istri', NULL, 'HIDUP', 'WNI', NOW(), NOW()),
('3201012345670003', '3201011234560006', 'Joko Prasetio', 'L', 'Solo', '1995-09-18', 'SMA', 'Tukang Bangunan', 'Anak', NULL, 'HIDUP', 'WNI', NOW(), NOW()),

-- Keluarga 7 (Fitri Handayani)
('3201018901230003', '3201011234560007', 'Fitri Handayani', 'P', 'Bogor', '1988-04-05', 'D3', 'Perawat', 'Kepala Keluarga', 'BPNT', 'HIDUP', 'WNI', NOW(), NOW()),
('3201011234560004', '3201011234560007', 'Indra Kusuma', 'L', 'Bogor', '1985-12-20', 'S1', 'Auditor', 'Suami', 'BPNT', 'HIDUP', 'WNI', NOW(), NOW()),
('3201013456780003', '3201011234560007', 'Nadia Putri', 'P', 'Bogor', '2015-07-30', 'TK', 'Pelajar', 'Anak', 'BPNT', 'HIDUP', 'WNI', NOW(), NOW()),

-- Keluarga 8 (Andi Kusuma)
('3201019012340002', '3201011234560008', 'Andi Kusuma', 'L', 'Makassar', '1972-02-14', 'SMA', 'Nelayan', 'Kepala Keluarga', 'PKH', 'HIDUP', 'WNI', NOW(), NOW()),
('3201011234560005', '3201011234560008', 'Mawarni', 'P', 'Makassar', '1976-08-28', 'SMP', 'Ibu Rumah Tangga', 'Istri', 'PKH', 'HIDUP', 'WNI', NOW(), NOW()),
('3201014567890003', '3201011234560008', 'Andi Saputra', 'L', 'Makassar', '2000-05-15', 'SMK', 'Nelayan', 'Anak', 'PKH', 'HIDUP', 'WNI', NOW(), NOW()),
('3201016789010002', '3201011234560008', 'Andi Sapriadi', 'L', 'Makassar', '2003-11-22', 'SMP', 'Pelajar', 'Anak', 'PKH', 'HIDUP', 'WNI', NOW(), NOW()),

-- Keluarga 9 (Maya Sari)
('3201010123450004', '3201011234560009', 'Maya Sari', 'P', 'Denpasar', '1990-07-12', 'S1', 'Wirausaha', 'Kepala Keluarga', NULL, 'HIDUP', 'WNI', NOW(), NOW()),

-- Keluarga 10 (Hendra Wijaya)
('3201011234560006', '3201011234560010', 'Hendra Wijaya', 'L', 'Bandung', '1978-10-25', 'S1', 'Wirausaha', 'Kepala Keluarga', 'BPNT', 'HIDUP', 'WNI', NOW(), NOW()),
('3201013456780004', '3201011234560010', 'Rina Susanti', 'P', 'Bandung', '1982-03-18', 'SMA', 'Ibu Rumah Tangga', 'Istri', 'BPNT', 'HIDUP', 'WNI', NOW(), NOW()),
('3201015678900003', '3201011234560010', 'Farhan Muhammad', 'L', 'Bandung', '2008-06-05', 'SD', 'Pelajar', 'Anak', 'BPNT', 'HIDUP', 'WNI', NOW(), NOW()),
('3201017890120004', '3201011234560010', 'Fiona Aulia', 'P', 'Bandung', '2011-09-15', 'TK', 'Pelajar', 'Anak', 'BPNT', 'HIDUP', 'WNI', NOW(), NOW());

-- Data tambahan untuk variasi status_dasar
INSERT INTO penduduks (nik, no_kk, nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir, pendidikan_terakhir, pekerjaan, status_hubungan_dalam_keluarga, jenis_bantuan, status_dasar, kewarganegaraan, created_at, updated_at) VALUES
-- Contoh data yang sudah meninggal
('3201019999990001', '3201011234560001', 'Suparman', 'L', 'Jakarta', '1940-01-01', 'SD', 'Pensiunan', 'Kakek', NULL, 'MATI', 'WNI', NOW(), NOW()),

-- Contoh data yang pindah
('3201019999990002', '3201011234560003', 'Taufik Hidayat', 'L', 'Yogyakarta', '1992-07-21', 'S1', 'Swasta', 'Anak', NULL, 'PINDAH', 'WNI', NOW(), NOW());