import React, { useState } from 'react';

import { NavLink ,useNavigate} from 'react-router-dom';
import { IoPersonCircleOutline, IoLogOutOutline } from 'react-icons/io5';
import '../Sidebar/Sidebar.css'; // Gunakan CSS yang sama untuk konsistensi
import ConfirmationModal from '../ConfirmationModal/ConfirmationModal'; // Import modal

const DoctorSidebar = () => {
  // Logic untuk Logout Dokter
  const [showLogoutModal, setShowLogoutModal] = useState(false);
  const navigate = useNavigate(); // Pastikan useNavigate diimport dari react-router-dom

  const handleLogout = () => {
    setShowLogoutModal(true);
  };

  const confirmLogout = () => {
    localStorage.removeItem('doctorAuthToken'); // Gunakan token berbeda untuk dokter
    navigate('/login'); // Kembali ke halaman login
    setShowLogoutModal(false);
  };

  const cancelLogout = () => {
    setShowLogoutModal(false);
  };

  return (
    <>
      <aside className="sidebar">
        <div className="sidebar-profile">
          <IoPersonCircleOutline size={60} className="profile-avatar" />
          <h3 className="profile-title">Dokter</h3>
        </div>
        <nav className="sidebar-nav">
          <ul>
            <li>
              <NavLink to="/dokter/janji-temu">Janji Temu</NavLink>
            </li>
            <li>
              <NavLink to="/dokter/pasien">Daftar Pasien</NavLink>
            </li>
            
            <li>
              <button onClick={handleLogout} className="logout-button">
                <IoLogOutOutline size={22} className="logout-icon" /> Logout
              </button>
            </li>
          </ul>
        </nav>
      </aside>
      <ConfirmationModal
        show={showLogoutModal}
        title="Konfirmasi Logout"
        message="Apakah Anda yakin ingin keluar dari akun Dokter ini?"
        onConfirm={confirmLogout}
        onCancel={cancelLogout}
      />
    </>
  );
};

export default DoctorSidebar;