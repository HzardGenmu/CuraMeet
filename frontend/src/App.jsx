import "./App.css";
import React from "react";
import { Routes, Route, Navigate } from "react-router-dom";
// Import Layouts
import DashboardLayout from "./layouts/DashboardLayout"; // Untuk Pasien
import DoctorLayout from "./layouts/DoctorLayout"; // Untuk Dokter
import AdminLayout from "./layouts/AdminLayout"; // Import AdminLayout baru

// Import Pages (Pasien)
import Login from "./Pages/Login/Login";
import Register from "./Pages/Register/Register";
import JanjiTemu from "./Pages/JanjiTemu/JanjiTemu";
import Profil from "./Pages/Profil/Profil";
import RekamMedis from "./Pages/RekamMedis/RekamMedis";
import CatatanMedis from "./Pages/CatatanMedis/CatatanMedis";

// Import Doctor Pages
import DoctorJanjiTemu from "./Pages/Doctor/DoctorJanjiTemu/DoctorJanjiTemu";
import DoctorDaftarPasien from "./Pages/Doctor/DoctorDaftarPasien/DoctorDaftarPasien";
import DoctorPasienDetail from "./Pages/Doctor/DoctorPasienDetail/DoctorPasienDetail";

// Import Admin Pages
import ManageRoles from "./Pages/Admin/ManageRoles/ManageRoles";
import LogViewer from "./Pages/Admin/LogViewer/LogViewer";
import SystemMonitoring from "./Pages/Admin/SystemMonitoring/SystemMonitoring";
import DataManagement from "./Pages/Admin/DataManagement/DataManagement";
import ForbiddenPage from "./Pages/Forbidden/ForbiddenPage";
import AuthGuard from "./components/AuthGuard/AuthGuard";
import { authService } from "./services/authService";

function App() {
  const isAuthenticated = authService.isAuthenticated();
  const userRole = authService.getRole(); // Buat fungsi getRole di authService

  // Fungsi untuk menentukan dashboard sesuai role
  const getDashboardPath = () => {
    if (userRole === "admin") return "/admin";
    if (userRole === "doctor") return "/dokter";
    if (userRole === "patient") return "/dashboard"; // default pasien
  };

  return (
    <div>
      <Routes>
        <Route
          path="/login"
          element={
            isAuthenticated ? <Navigate to={getDashboardPath()} /> : <Login />
          }
        />
        <Route
          path="/register"
          element={
            isAuthenticated ? (
              <Navigate to={getDashboardPath()} />
            ) : (
              <Register />
            )
          }
        />

        {/* Rute Pasien */}
        <Route
          element={
            <AuthGuard requiredRole="patient">
              <DashboardLayout />
            </AuthGuard>
          }
        >
          <Route path="/janji-temu" element={<JanjiTemu />} />
          <Route path="/profil" element={<Profil />} />
          <Route path="/rekam-medis" element={<RekamMedis />} />
          <Route path="/catatan-medis" element={<CatatanMedis />} />
          <Route path="/dashboard" element={<Navigate to="/janji-temu" />} />
        </Route>

        {/* Rute Dokter */}
        <Route
          path="/dokter"
          element={
            <AuthGuard requiredRole="doctor">
              <DoctorLayout />
            </AuthGuard>
          }
        >
          <Route path="janji-temu" element={<DoctorJanjiTemu />} />
          <Route path="pasien" element={<DoctorDaftarPasien />} />
          <Route path="pasien/:pasienId" element={<DoctorPasienDetail />} />
          <Route path="" element={<Navigate to="janji-temu" />} />
        </Route>

        {/* Rute Admin */}
        <Route
          path="/admin"
          element={
            <AuthGuard requiredRole="admin">
              <AdminLayout />
            </AuthGuard>
          }
        >
          {/* <Route path="/admin" element={<AdminLayout />}> */}
          <Route path="kelola-role" element={<ManageRoles />} />
          <Route path="log-viewer" element={<LogViewer />} />
          <Route path="system-monitoring" element={<SystemMonitoring />} />
          <Route path="data-management" element={<DataManagement />} />
          <Route path="" element={<Navigate to="kelola-role" />} />
        </Route>

        {/* Rute Default */}
        <Route path="/" element={<Navigate to="/register" />} />
        <Route path="/forbidden" element={<ForbiddenPage />} />
      </Routes>
    </div>
  );
}

export default App;
