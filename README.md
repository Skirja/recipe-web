# Web Resep Makanan

Proyek web untuk berbagi dan menemukan resep makanan.

## Setup Proyek

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di lingkungan lokal Anda:

1.  **Clone Repository:**
    Jika proyek ini berada di repository Git, clone ke komputer lokal Anda.
    ```bash
    git clone <URL_REPOSITORY>
    cd <nama_folder_proyek>
    ```
    (Ganti `<URL_REPOSITORY>` dan `<nama_folder_proyek>` sesuai dengan detail proyek Anda).

2.  **Salin File Environment:**
    Laravel menggunakan file `.env` untuk konfigurasi lingkungan. Salin file contoh yang sudah ada.
    ```bash
    cp .env.example .env
    ```
    Di sistem Windows, Anda mungkin perlu menggunakan perintah `copy`:
    ```bash
    copy .env.example .env
    ```

3.  **Konfigurasi Database:**
    Buka file `.env` yang baru saja disalin dan sesuaikan detail koneksi database Anda.
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database_anda # Ganti dengan nama database yang Anda buat
    DB_USERNAME=username_database_anda # Ganti dengan username database Anda
    DB_PASSWORD=password_database_anda # Ganti dengan password database Anda
    ```
    Pastikan Anda telah membuat database kosong dengan nama yang sesuai di server database Anda (misalnya, MySQL).

4.  **Instal Dependensi PHP:**
    Gunakan Composer untuk menginstal semua dependensi PHP yang dibutuhkan proyek.
    ```bash
    composer install
    ```

5.  **Generate Application Key:**
    Laravel memerlukan kunci aplikasi unik. Jalankan perintah berikut untuk menghasilkannya.
    ```bash
    php artisan key:generate
    ```

6.  **Jalankan Migrasi Database:**
    Buat tabel database yang dibutuhkan proyek menggunakan Artisan.
    ```bash
    php artisan migrate
    ```

7.  **Instal Dependensi JavaScript:**
    Gunakan NPM (Node Package Manager) untuk menginstal dependensi frontend.
    ```bash
    npm install
    ```

8.  **Kompilasi Aset Frontend:**
    Gunakan Vite untuk mengkompilasi aset CSS dan JavaScript. Untuk pengembangan, gunakan perintah `build` yang akan memantau perubahan file.
    ```bash
    npm run build
    ```

9.  **Jalankan Server Pengembangan Lokal:**
    Gunakan Artisan untuk menjalankan server pengembangan lokal Laravel.
    ```bash
    composer run dev
    ```

10. **Akses Aplikasi:**
    Buka browser Anda dan kunjungi alamat yang ditampilkan oleh perintah `composer run dev` (biasanya `http://localhost:8000`).

Proyek Anda sekarang seharusnya sudah berjalan di lingkungan lokal.
