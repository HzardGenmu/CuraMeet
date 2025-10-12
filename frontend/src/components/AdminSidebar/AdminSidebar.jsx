import React, { useState } from 'react';
import { NavLink, useNavigate } from 'react-router-dom';
import { IoShieldOutline, IoPeopleOutline, IoLogOutOutline, IoDocumentTextOutline, IoAnalyticsOutline, IoServer } from 'react-icons/io5'; // Pastikan ikon ini terinstal
import '../Sidebar/Sidebar.css'; // INI PENTING: Menggunakan CSS yang sama
import ConfirmationModal from '../ConfirmationModal/ConfirmationModal';

const AdminSidebar = () => {
  const [showLogoutModal, setShowLogoutModal] = useState(false);
  const navigate = useNavigate();

  const handleLogout = () => {
    setShowLogoutModal(true);
  };

  const confirmLogout = () => {
    localStorage.removeItem('adminAuthToken'); 
    navigate('/login'); 
    setShowLogoutModal(false);
  };

  const cancelLogout = () => {
    setShowLogoutModal(false);
  };

  return (
    <>
      <aside className="sidebar"> {/* Kelas CSS utama */}
        <div className="sidebar-profile"> {/* Kelas CSS profil */}
          <IoShieldOutline size={60} className="profile-avatar" /> {/* Ikon & kelas avatar */}
          <h3 className="profile-title">Admin</h3> {/* Kelas judul profil */}
        </div>
        <nav className="sidebar-nav"> {/* Kelas navigasi */}
          <ul>
            <li>
              {/* Pastikan struktur NavLink konsisten, termasuk ikon jika digunakan di sidebar lain */}
              <NavLink to="/admin/kelola-role">
                <IoPeopleOutline size={22} className="nav-icon" /> Kelola Role
              </NavLink>
            </li>
            <li>
                <NavLink to="/admin/log-viewer">
                    <IoDocumentTextOutline size={22} className="nav-icon" /> Log Aktivitas
                </NavLink>
            </li>
             <li>
              
              <NavLink to="/admin/system-monitoring">
                <IoAnalyticsOutline className="nav-icon" /> Monitoring Sistem
              </NavLink>
            </li>
            <li>
              
              <NavLink to="/admin/data-management">
                <IoServer className="nav-icon" /> Manajemen Data
              </NavLink>
            </li>
            <li>
              <button onClick={handleLogout} className="logout-button"> {/* Kelas tombol logout */}
                <IoLogOutOutline size={22} className="logout-icon" /> Logout
              </button>
            </li>
          </ul>
        </nav>
      </aside>
      <ConfirmationModal
        show={showLogoutModal}
        title="Konfirmasi Logout"
        message="Apakah Anda yakin ingin keluar dari akun Admin ini?"
        onConfirm={confirmLogout}
        onCancel={cancelLogout}
      />
    </>
  );
};

export default AdminSidebar;