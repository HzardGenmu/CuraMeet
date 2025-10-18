# CuraMeet – Web Platform Deployment Guide

CuraMeet adalah platform terintegrasi yang menggabungkan:
- **OJS (Open Journal System)** untuk pengelolaan publikasi ilmiah.
- **Laravel Backend API** untuk sistem manajemen dan logika bisnis.
- **React Frontend** untuk antarmuka pengguna modern.
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
