# Panduan Konfigurasi Email Reset Password (Production)

Saat ini di komputer lokal (Localhost), email reset password **tidak dikirim ke email asli**, melainkan dicatat di **Log File** (`storage/logs/laravel.log`) untuk tujuan pengujian tanpa internet/SMTP.

Ketika website ini sudah di-online-kan (Hosting), Anda harus mengubah pengaturan agar email benar-benar terkirim ke alamat email pengguna.

## 1. Persiapkan Akun Email (SMTP)

Anda membutuhkan akun email yang mendukung SMTP. Biasanya disediakan oleh penyedia hosting (cPanel/Titan Mail) atau layanan pihak ketiga seperti Gmail, Mailgun, atau SendGrid.

**Contoh data yang akan Anda dapatkan dari Hosting:**
-   **Host**: `mail.desasukma.id` (Alamat server email)
-   **Port**: `465` (SSL) atau `587` (TLS)
-   **Username**: `admin@desasukma.id` (Email lengkap Anda)
-   **Password**: `SuperSecretPassword123!` (Password email tersebut)
-   **Encryption**: `ssl` atau `tls`

---

## 2. Edit File `.env` di Hosting

Di dalam File Manager hosting Anda, cari file bernama `.env`. **Ganti nilai yang ada** dengan data yang Anda persiapkan di Langkah 1 tadi.

```env
# Ganti dengan "smtp"
MAIL_MAILER=smtp

# Isi dengan HOST dari Langkah 1
MAIL_HOST=mail.desasukma.id

# Isi dengan PORT dari Langkah 1
MAIL_PORT=465

# Isi dengan USERNAME dari Langkah 1
MAIL_USERNAME=admin@desasukma.id

# Isi dengan PASSWORD dari Langkah 1
MAIL_PASSWORD=SuperSecretPassword123!

# Isi dengan ENCRYPTION dari Langkah 1
MAIL_ENCRYPTION=ssl

# Ganti dengan email pengirim & nama desa
MAIL_FROM_ADDRESS="admin@desasukma.id"
MAIL_FROM_NAME="Desa Sukma"
```

> **Catatan Khusus Pengguna Gmail:**
> Sejak Mei 2022, Google tidak mengizinkan login menggunakan password akun biasa untuk aplikasi pihak ketiga.
>
> **Solusinya (Wajib):**
> 1.  Login ke akun Google Anda -> **Manage your Google Account**.
> 2.  Masuk ke menu **Security**.
> 3.  Aktifkan **2-Step Verification** (jika belum).
> 4.  Setelah aktif, cari menu **App passwords** (atau ketik di kolom pencarian settings).
> 5.  Buat app password baru:
>     -   **App name**: Desa Sukma
>     -   Klik **Create**.
> 6.  Google akan memberikan **16 digit password acak** (contoh: `abcd efgh ijkl mnop`).
> 7.  Gunakan 16 digit tersebut sebagai `MAIL_PASSWORD` di file `.env` Anda (jangan gunakan password login gmail biasa).

## 3. Pastikan Queue Tidak Macet (Opsional)

Secara default, Laravel mengirim email secara langsung (Sync). Jika terasa lambat saat klik "Kirim Link Reset", Anda bisa menggunakan Queue. Namun untuk penggunaan standar, settingan default `QUEUE_CONNECTION=sync` di `.env` sudah cukup.

## 4. Custom Template (Sudah Disiapkan)

Saya sudah membuatkan Custom Notification agar email yang dikirim menggunakan **Bahasa Indonesia** dan format yang rapi.

File pengaturannya ada di:
`app/Notifications/ResetPasswordNotification.php`

Anda tidak perlu mengubah apa-apa lagi di sini kecuali ingin mengganti kata-kata dalam emailnya.

### Contoh Isi Email yang Akan Diterima User:

**Subject:** Reset Password Notification
**Greeting:** Halo!
**Isi:** Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.
**Tombol:** [Reset Password]
**Footer:** Link reset password ini akan kadaluwarsa dalam 60 menit. Jika Anda tidak meminta reset password, abaikan email ini.

## 5. Testing di Hosting

1.  Buka halaman Login -> Lupa Password.
2.  Masukkan email Anda yang valid (pastikan email user di database `users` adalah email asli yang bisa Anda buka).
3.  Klik "Kirim Link Reset".
4.  Cek Inbox (atau Spam) di email Anda.
5.  Klik tombol di email, dan Anda akan diarahkan kembali ke website untuk membuat password baru.
