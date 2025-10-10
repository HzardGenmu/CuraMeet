import React from 'react';
import { Outlet } from 'react-router-dom';
import Header from '../components/Header/Header';
import DoctorSidebar from '../components/DoctorSidebar/DoctorSidebar';
import '../App.css';

const DoctorLayout = () => {
  return (
    <div className="app-layout">
      <DoctorSidebar />
      <div className="main-panel">
        <Header />
        <main className="content-area">
          <Outlet />
        </main>
      </div>
    </div>
  );
};

export default DoctorLayout;