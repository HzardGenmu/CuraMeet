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
