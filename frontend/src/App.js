
import './App.css';
import React from 'react';
import { Routes, Route, Navigate } from 'react-router-dom';
import Register from './Pages/Register/Register';
import Login from './Pages/Login/Login';
import DashboardLayout from './layouts/DashboardLayout';
import JanjiTemu from './Pages/JanjiTemu/JanjiTemu';
import Profil from './Pages/Profil/Profil';
import RekamMedis from './Pages/RekamMedis/RekamMedis';
import CatatanMedis from './Pages/CatatanMedis/CatatanMedis';
import DoctorLayout from './layouts/DoctorLayout';
import DoctorJanjiTemu from './Pages/Doctor/DoctorJanjiTemu/DoctorJanjiTemu';
import DoctorDaftarPasien from './Pages/Doctor/DoctorDaftarPasien/DoctorDaftarPasien'; 
import DoctorPasienDetail from './Pages/Doctor/DoctorPasienDetail/DoctorPasienDetail';


function App() {
  return (
    <div> 
      <Routes>
        {/* Rute Otentikasi */}
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />

        {/* Rute Pasien (menggunakan DashboardLayout) */}
        <Route element={<DashboardLayout />}>
          <Route path="/janji-temu" element={<JanjiTemu />} />
          <Route path="/profil" element={<Profil />} />
          <Route path="/rekam-medis" element={<RekamMedis />} />
          <Route path="/catatan-medis" element={<CatatanMedis />} />
          <Route path="/dashboard" element={<Navigate to="/janji-temu" />} />
        </Route>

        {/* Rute Dokter (menggunakan DoctorLayout) */}
        <Route path="/dokter" element={<DoctorLayout />}>
          <Route path="janji-temu" element={<DoctorJanjiTemu />} />
          <Route path="pasien" element={<DoctorDaftarPasien />}/>
          <Route path="pasien/:pasienId" element={<DoctorPasienDetail />} />
          {/* Tambahkan rute lain untuk dokter di sini */}
          {/* <Route path="pasien" element={<DoctorDaftarPasien />} /> */}
          {/* <Route path="profil" element={<DoctorProfil />} /> */}
          <Route path="" element={<Navigate to="janji-temu" />} /> {/* Default ke janji-temu dokter */}
        </Route>

        {/* Rute Default saat membuka root */}
        <Route path="/" element={<Navigate to="/login" />} />
      </Routes>
    </div>
  );
}

export default App;