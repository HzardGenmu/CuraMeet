import React from 'react';
import { Outlet } from 'react-router-dom'; // Penting untuk merender halaman anak
import Header from '../components/Header/Header';
import Sidebar from '../components/Sidebar/Sidebar';
import '../App.css'; // Kita akan menggunakan gaya dari App.css

const DashboardLayout = () => {
  return (
    <div className="app-layout">
      <Sidebar />
      <div className="main-panel">
        <Header />
        <main className="content-area">
          {/* Outlet akan merender komponen halaman sesuai URL */}
          <Outlet />
        </main>
      </div>
    </div>
  );
};

export default DashboardLayout;