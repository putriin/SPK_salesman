# SPK Salesman Hamasa

Sistem Pendukung Keputusan (SPK) Penilaian Kinerja Salesman menggunakan metode TOPSIS berbasis web.

---

## Tentang Project

Project ini dibuat untuk membantu proses penilaian kinerja salesman secara objektif dan terstruktur menggunakan metode TOPSIS (Technique for Order Preference by Similarity to Ideal Solution).

Sistem dapat melakukan:

- Pengelolaan data salesman
- Pengelolaan data kriteria
- Input penilaian kinerja
- Proses perhitungan TOPSIS otomatis
- Perankingan salesman terbaik
- Cetak laporan hasil perhitungan
- Pengaturan user dan role

---

## Fitur Sistem

### Authentication

- Login manual
- Login Google
- Show / Hide password
- Multi role user

### Dashboard

- Dashboard Admin
- Dashboard CEO
- Dashboard Manajer

### Data Master

- Data Salesman
- Data Kriteria
- Pengaturan Bobot Kriteria

### Penilaian

- Input nilai penilaian salesman
- Riwayat penilaian per periode

### Perhitungan TOPSIS

- Matriks keputusan
- Normalisasi matriks
- Matriks ternormalisasi terbobot
- Solusi ideal positif dan negatif
- Perhitungan jarak solusi
- Nilai preferensi
- Ranking alternatif

### Laporan

- Cetak hasil TOPSIS
- Laporan ranking salesman

### User Management

- Tambah user
- Reset password
- Pengaturan role

---

# Metode yang Digunakan

## TOPSIS

Technique for Order Preference by Similarity to Ideal Solution

Tahapan metode TOPSIS pada sistem ini:

1. Menentukan matriks keputusan
2. Melakukan normalisasi matriks
3. Menghitung matriks normalisasi terbobot
4. Menentukan solusi ideal positif dan negatif
5. Menghitung jarak setiap alternatif
6. Menghitung nilai preferensi
7. Menentukan ranking alternatif terbaik

---

# Teknologi yang Digunakan

- PHP 8
- CodeIgniter 4
- MySQL
- Bootstrap 5
- JavaScript
- HTML
- CSS

---

# Struktur Project

```text
spk-Topsisalesman/
│
├── app/
├── public/
├── writable/
├── vendor/
├── database/
│   └── spk_topsis.sql
├── .env
├── composer.json
└── README.md
```

---

# Cara Menjalankan Project

## 1. Clone Repository

```bash
git clone https://github.com/putriin/SPK_salesman.git
```

---

## 2. Masuk ke Folder Project

```bash
cd spk-Topsisalesman
```

---

## 3. Install Dependency

```bash
composer install
```

---

## 4. Import Database

Import file database berikut ke phpMyAdmin:

```text
database/spk_topsis.sql
```

Langkah import:

1. Buka phpMyAdmin
2. Buat database baru dengan nama:
   `spk_topsis`
3. Klik tab Import
4. Pilih file:
   `database/spk_topsis.sql`
5. Klik Go

---

## 5. Konfigurasi File .env

Buka file `.env`

Lalu sesuaikan konfigurasi database:

```env
database.default.hostname = localhost
database.default.database = spk_topsis
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

---

## 6. Jalankan Project

```bash
php spark serve
```

Akses melalui browser:

```text
http://localhost:8080
```

---

# Tampilan Sistem

## Halaman Login

- Login manual
- Login Google
- Show / hide password

## Dashboard

Menampilkan ringkasan data sistem.

## Data Salesman

Mengelola data alternatif salesman.

## Data Kriteria

Mengelola data kriteria dan bobot.

## Penilaian Kinerja

Input nilai salesman berdasarkan kriteria.

## Perhitungan TOPSIS

Menampilkan proses perhitungan TOPSIS secara detail:

- Matriks keputusan
- Normalisasi
- Bobot
- Solusi ideal
- Ranking

## Laporan

Cetak hasil ranking salesman terbaik.

---

# Role User

| Role       | Hak Akses                 |
| ---------- | ------------------------- |
| IT Support | Full akses sistem         |
| Manajer    | Penilaian dan monitoring  |
| CEO        | Melihat hasil dan laporan |

---

# Author

## Putri Indaryani

Project Skripsi  
Sistem Pendukung Keputusan Penilaian Kinerja Salesman Menggunakan Metode TOPSIS

---

# Lisensi

Project ini dibuat untuk kebutuhan pembelajaran dan skripsi.
