-- Dummy Data for Website Desa Sukma

-- 1. Village Profile
INSERT INTO village_profiles (kades_name, kades_photo, sambutan_title, sambutan_content, video_url, created_at, updated_at)
VALUES 
('Bapak Sutrisno', 'kades.jpg', 'Sambutan Kepala Desa', 'Selamat datang di Website Resmi Desa Sukma. Kami berkomitmen untuk melayani masyarakat dengan sepenuh hati, transparan, dan akuntabel. Website ini hadir sebagai wujud keterbukaan informasi publik dan sarana komunikasi antara pemerintah desa dengan masyarakat.', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', NOW(), NOW());

-- 2. Officials (Perangkat Desa)
INSERT INTO officials (name, position, photo, `order`, created_at, updated_at)
VALUES 
('Sutrisno', 'Kepala Desa', 'official1.jpg', 1, NOW(), NOW()),
('Budi Santoso', 'Sekretaris Desa', 'official2.jpg', 2, NOW(), NOW()),
('Siti Aminah', 'Bendahara Desa', 'official3.jpg', 3, NOW(), NOW()),
('Joko Widodo', 'Kasi Pemerintahan', 'official4.jpg', 4, NOW(), NOW()),
('Rina Wati', 'Kasi Kesejahteraan', 'official5.jpg', 5, NOW(), NOW());

-- 3. Institutions (Lembaga Desa)
INSERT INTO institutions (abbr, name, description, icon, created_at, updated_at)
VALUES 
('BPD', 'Badan Permusyawaratan Desa', 'Lembaga perwujudan demokrasi dalam penyelenggaraan pemerintahan desa.', 'users', NOW(), NOW()),
('LPM', 'Lembaga Pemberdayaan Masyarakat', 'Lembaga yang membantu pemerintah desa dalam menyerap aspirasi masyarakat.', 'activity', NOW(), NOW()),
('PKK', 'Pemberdayaan Kesejahteraan Keluarga', 'Gerakan nasional dalam pembangunan masyarakat yang tumbuh dari bawah.', 'heart', NOW(), NOW()),
('Karang Taruna', 'Karang Taruna Tunas Bangsa', 'Wadah pengembangan generasi muda non-partisan, yang tumbuh atas dasar kesadaran dan rasa tanggung jawab sosial.', 'zap', NOW(), NOW());

-- 4. Kartu Keluarga (KK)
INSERT INTO kartu_keluargas (no_kk, kepala_keluarga, dusun, alamat, rt, rw, kode_pos, jenis_bangunan, pemakaian_air, jenis_bantuan, status_kesejahteraan, created_at, updated_at)
VALUES 
('3201010101010001', 'Budi Santoso', 'Dusun Mawar', 'Jl. Mawar No. 1', '001', '001', '16111', 'Permanen', 'PDAM', 'PKH', 'KS1', NOW(), NOW()),
('3201010101010002', 'Siti Aminah', 'Dusun Melati', 'Jl. Melati No. 10', '002', '001', '16112', 'Semi Permanen', 'Sumur', NULL, 'KS2', NOW(), NOW()),
('3201010101010003', 'Ahmad Dahlan', 'Dusun Anggrek', 'Jl. Anggrek No. 5', '001', '002', '16113', 'Permanen', 'PDAM', 'BLT', 'KS1', NOW(), NOW());

-- 5. Penduduks (Residents)
-- Note: NIK must be unique and 16 chars.
INSERT INTO penduduks (nik, no_kk, nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir, pendidikan_terakhir, pekerjaan, status_hubungan_dalam_keluarga, jenis_bantuan, status_dasar, kewarganegaraan, created_at, updated_at)
VALUES 
-- Family 1 (Budi Santoso)
('3201010101900001', '3201010101010001', 'Budi Santoso', 'L', 'Bogor', '1990-01-01', 'S1', 'Wiraswasta', 'KEPALA KELUARGA', 'PKH', 'HIDUP', 'WNI', NOW(), NOW()),
('3201010202920002', '3201010101010001', 'Ratna Sari', 'P', 'Bogor', '1992-02-02', 'D3', 'Ibu Rumah Tangga', 'ISTRI', 'PKH', 'HIDUP', 'WNI', NOW(), NOW()),
('3201010303150003', '3201010101010001', 'Doni Santoso', 'L', 'Bogor', '2015-03-03', 'SD', 'Pelajar', 'ANAK', NULL, 'HIDUP', 'WNI', NOW(), NOW()),

-- Family 2 (Siti Aminah - Widow/Single Parent scenario)
('3201010404850004', '3201010101010002', 'Siti Aminah', 'P', 'Bandung', '1985-04-04', 'S1', 'PNS', 'KEPALA KELUARGA', NULL, 'HIDUP', 'WNI', NOW(), NOW()),
('3201010505100005', '3201010101010002', 'Rina Aminah', 'P', 'Bogor', '2010-05-05', 'SMP', 'Pelajar', 'ANAK', NULL, 'HIDUP', 'WNI', NOW(), NOW()),

-- Family 3 (Ahmad Dahlan)
('3201010606800006', '3201010101010003', 'Ahmad Dahlan', 'L', 'Jakarta', '1980-06-06', 'SMA', 'Petani', 'KEPALA KELUARGA', 'BLT', 'HIDUP', 'WNI', NOW(), NOW()),

-- Individual (Mutated/Moved/Deceased scenarios)
('3201010707230007', '3201010101010001', 'Bayu Santoso', 'L', 'Bogor', '2023-07-07', 'Belum Sekolah', 'Belum Bekerja', 'ANAK', NULL, 'HIDUP', 'WNI', NOW(), NOW()), -- Baru Lahir
('3201010808500008', '3201010101010003', 'Kakek Dahlan', 'L', 'Bogor', '1950-08-08', 'SD', 'Tidak Bekerja', 'ORANG TUA', NULL, 'MATI', 'WNI', NOW(), NOW()); -- Meninggal

-- 6. Mutasi (Mutations)
INSERT INTO mutasis (nik, jenis_mutasi, tanggal_mutasi, keterangan, created_at, updated_at)
VALUES 
('3201010707230007', 'LAHIR', '2023-07-07', 'Lahir di Puskesmas Desa Sukma', NOW(), NOW()),
('3201010808500008', 'MATI', '2024-01-01', 'Meninggal karena sakit tua', NOW(), NOW());

-- 7. Galleries
INSERT INTO galleries (title, description, image, date, created_at, updated_at)
VALUES 
('Kegiatan Gotong Royong', 'Warga Desa Sukma melakukan gotong royong membersihkan saluran air.', 'gallery1.jpg', '2024-11-20', NOW(), NOW()),
('Musyawarah Desa', 'Musyawarah perencanaan pembangunan desa tahun 2025.', 'gallery2.jpg', '2024-11-25', NOW(), NOW()),
('Panen Raya', 'Syukuran panen raya padi di Dusun Mawar.', 'gallery3.jpg', '2024-12-01', NOW(), NOW());
