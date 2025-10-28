# CuraMeet

Pada Project ini, kami dari kelompok 2 menerapkan prinsip DevSecOps pada 2 macam app yaitu Open Journal System(OJS) dan CuraMeet yaitu platform yang berlandasan kesehatan untuk pengecekan dan mencatat rekaman medis.
Didalam project ini terdapat struktur artsitektur sebagai berikut:
- **OJS (Open Journal System)** untuk pengelolaan publikasi jurnal.
- **Laravel Backend API** untuk sistem manajemen dan logika.
- **React Frontend** untuk antarmuka pengguna.
- **Nginx Proxy Manager (NPM)** sebagai reverse proxy dengan SSL (Let's Encrypt).

---

## 🧩 Arsitektur Aplikasi

Semua layanan berjalan di dalam container Docker:

| Layanan | Container Name | Port Internal | Domain Proxy | Deskripsi |
|----------|----------------|----------------|---------------|------------|
| Nginx Proxy Manager | `nginx_proxy_manager` | 80, 81, 443 | — | Reverse proxy dan SSL management |
| OJS | `ojs_app` | 80 / 443 | `https://curameet-ojs.duckdns.org` | Open Journal System |
| Laravel Backend | `laravel_nginx` | 80 | `https://api.curameet.duckdns.org` | Backend API berbasis Laravel |
| React Frontend | `react_frontend` | 80 | `https://curameet.duckdns.org` | Tampilan utama aplikasi |
| MariaDB | `ojs_db` | 3306 | — | Database OJS |
| PostgreSQL | `postgres_backend` | 5432 | — | Database Laravel backend |

Semua container terhubung pada jaringan `curameet_app-network`.

---

Panduan ini menjelaskan tata cara instalasi dan menjalankan aplikasi CuraMeet. Gunakan bagian yang sesuai dengan stack proyek Anda (Node.js, Python, Docker, dll.). Isi nilai konfigurasi (.env) sesuai kebutuhan proyek.

> NOTE: Sesuaikan perintah dan nama script (mis. `npm run dev`, `manage.py`, dsb.) dengan isi repository ini — panduan ini bersifat template umum jika struktur proyek berbeda.

## Daftar isi
- [Prasyarat](#prasyarat)
- [Clone repo](#clone-repo)
- [Konfigurasi environment (.env)](#konfigurasi-environment-env)
- [Instalasi (contoh Node.js)](#instalasi-contoh-nodejs)
- [Instalasi (contoh Python/Django/Flask)](#instalasi-contoh-pythondjangoflask)
- [Menggunakan Docker (opsional)](#menggunakan-docker-opsional)
- [Database dan migrasi](#database-dan-migrasi)
- [Menjalankan aplikasi](#menjalankan-aplikasi)
- [Menjalankan test](#menjalankan-test)
- [Build untuk production](#build-untuk-production)
- [Troubleshooting umum](#troubleshooting-umum)
- [Kontribusi](#kontribusi)
- [Kontak / Bantuan](#kontak--bantuan)

## Prasyarat
Pastikan Anda menginstal:
- Git
- Node.js (mis. 16.x atau lebih tinggi) dan npm/yarn — jika proyek berbasis JavaScript/Node
- Python 3.8+ dan pip — jika proyek berbasis Python
- Database yang diperlukan (Postgres / MySQL / SQLite)
- Docker & Docker Compose (opsional, untuk container)
- (Opsional) `make` jika repo menyediakan Makefile

## Clone repo
Jalankan:
```bash
git clone https://github.com/HzardGenmu/CuraMeet.git
cd CuraMeet
```

## Konfigurasi environment (.env)
Buat salinan file contoh environment (jika tersedia) dan isi nilai yang benar:
```bash
cp .env.example .env
# lalu edit .env
```

Contoh .env (sesuaikan nama variabel dengan yang dipakai project):
```env
# ===== Server =====
PORT=3000
NODE_ENV=development

# ===== Database =====
DATABASE_URL=postgres://user:password@localhost:5432/curameet_db

# ===== Auth / Secrets =====
JWT_SECRET=your_jwt_secret
SESSION_SECRET=your_session_secret

# ===== Third-party =====
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
```

## Instalasi (contoh Node.js)
Jika proyek menggunakan Node.js / npm:
1. Install dependencies:
```bash
npm install
# atau
yarn install
```

2. Siapkan .env seperti di atas.

3. Jika ada langkah build awal:
```bash
npm run build
```

4. Jalankan migrasi / seed (lihat bagian Database).

## Instalasi (contoh Python/Django/Flask)
Jika proyek berbasis Python/Django:
1. Buat virtual environment dan install dependency:
```bash
python -m venv .venv
source .venv/bin/activate    # macOS / Linux
.venv\Scripts\activate       # Windows

pip install -r requirements.txt
```

2. Siapkan .env / konfigurasi dan database.

3. Jalankan migrasi:
```bash
python manage.py migrate
python manage.py loaddata initial_data.json   # jika ada seed
```

Jika Flask, sesuaikan perintah (mis. `flask db upgrade` jika menggunakan Flask-Migrate).

## Database dan migrasi
1. Pastikan database (Postgres/MySQL) sudah dibuat:
```sql
-- contoh Postgres
CREATE DATABASE curameet_db;
CREATE USER curameet_user WITH PASSWORD 'password';
GRANT ALL PRIVILEGES ON DATABASE curameet_db TO curameet_user;
```

2. Jalankan migrasi (sesuai framework):
- Django:
```bash
python manage.py migrate
```
- Sequelize (Node.js):
```bash
npx sequelize db:migrate
```
- TypeORM:
```bash
npm run typeorm migration:run
```

3. Untuk menambahkan data awal (seed), jalankan perintah seed sesuai tooling proyek.

## Menggunakan Docker (opsional)
Jika repo menyediakan Dockerfile / docker-compose.yml:
1. Build dan jalankan:
```bash
docker-compose up --build
```

2. Atau build image manual:
```bash
docker build -t curameet:latest .
docker run -p 3000:3000 --env-file .env curameet:latest
```

Pastikan variabel environment dan koneksi database diatur untuk Docker (gunakan network atau service name jika menggunakan docker-compose).

## Menjalankan aplikasi
- Mode development (contoh Node):
```bash
npm run dev
```
- Mode production:
```bash
npm start
```
- Django:
```bash
python manage.py runserver 0.0.0.0:8000
```

Akses aplikasi di http://localhost:PORT (sesuaikan PORT di .env atau perintah run).

## Menjalankan test
Contoh perintah umum:
- Node.js / Jest:
```bash
npm test
```
- Python / pytest:
```bash
pytest
```

## Build untuk production
- Node (mis. React / Next / Vite):
```bash
npm run build
npm run start      # atau perintah yang sesuai untuk serve
```
- Django: Siapkan konfigurasi WSGI/ASGI (gunicorn/uvicorn) dan web server (nginx).

## Troubleshooting umum
- Error dependency: hapus node_modules/virtualenv lalu install ulang.
```bash
rm -rf node_modules
npm install
```
- Masalah koneksi DB: periksa connection string di .env, pastikan DB berjalan dan kredensial benar.
- Port sudah digunakan: ubah PORT di .env atau matikan proses yang menggunakan port.

## Kontribusi
Terima kasih atas minat kontribusinya! Langkah umum:
1. Fork repo
2. Buat branch feature: `git checkout -b feature/nama-fitur`
3. Commit dan push: `git push origin feature/nama-fitur`
4. Buka Pull Request menjelaskan perubahan.

Tambahkan testing dan dokumentasi bila perlu.

## Kontak / Bantuan
Jika ada masalah instalasi, sertakan:
- System operasi & versi
- Versi Node / Python
- Log error yang muncul (copy/paste)
- Langkah yang sudah dicoba

Kirim isu di repository atau hubungi maintainer.

---

Jika Anda ingin, saya bisa:
- Menyusun README yang lebih spesifik berdasarkan isi repository (saya bisa membaca file-file konfigurasi dan package.json / requirements.txt untuk menyesuaikan perintah).
- Membuat file .env.example berdasarkan konfigurasi yang ditemukan.

Mau saya periksa struktur repo dan buat README yang terperinci otomatis?
