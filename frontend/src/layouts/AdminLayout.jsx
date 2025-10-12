// src/layouts/AdminLayout.js
import React from 'react';
import { Outlet } from 'react-router-dom';
import Header from '../components/Header/Header'; // Impor Header
import AdminSidebar from '../components/AdminSidebar/AdminSidebar';
import '../App.css'; // Impor gaya layout utama

const AdminLayout = () => {
  return (
    <div className="app-layout"> {/* Gunakan kelas layout yang sama */}
      <AdminSidebar />
      <div className="main-panel"> {/* Gunakan panel utama yang sama */}
        <Header /> {/* Tambahkan Header di sini */}
        <main className="content-area"> {/* Area konten yang sama */}
          <Outlet />
        </main>
      </div>
    </div>
  );
};

export default AdminLayout;