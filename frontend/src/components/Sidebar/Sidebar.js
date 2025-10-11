import React, { useState } from 'react'; // Import useState
import './Sidebar.css';
import { NavLink, useNavigate } from 'react-router-dom'; // Import useNavigate
import { IoPersonCircleOutline, IoLogOutOutline } from 'react-icons/io5'; // Import IoLogOutOutline

import ConfirmationModal from '../ConfirmationModal/ConfirmationModal'; // Import ConfirmationModal

const Sidebar = () => {
  const navigate = useNavigate(); // Hook untuk navigasi programatik
  const [showLogoutModal, setShowLogoutModal] = useState(false); // State untuk modal logout

  const handleLogout = () => {
    // Tampilkan modal konfirmasi
    setShowLogoutModal(true);
  };

  const confirmLogout = () => {
    // Logika logout sebenarnya:
    // 1. Hapus token otentikasi (misal dari localStorage)
    localStorage.removeItem('authToken'); 
    // 2. Redirect ke halaman login
    navigate('/login');
    // 3. Tutup modal
    setShowLogoutModal(false);
  };

  const cancelLogout = () => {
    setShowLogoutModal(false); // Tutup modal tanpa logout
  };

  return (
    <> {/* Gunakan Fragment untuk merender modal di samping sidebar */}
      <aside className="sidebar">
        <div className="sidebar-profile">
          <IoPersonCircleOutline size={60} className="profile-avatar" />
          <h3 className="profile-title">Patient</h3>
        </div>
        <nav className="sidebar-nav">
          <ul>
            <li>
              <NavLink to="/janji-temu">Janji Temu</NavLink>
            </li>
            <li>
              <NavLink to="/rekam-medis">Rekam Medis</NavLink>
            </li>
            <li>
              <NavLink to="/profil">Profil</NavLink>
            </li>
            <li>
              <NavLink to="/catatan-medis">Catatan Medis</NavLink>
            </li>
            {/* Item Logout */}
            <li>
              <button onClick={handleLogout} className="logout-button">
                <IoLogOutOutline size={22} className="logout-icon" /> Logout
              </button>
            </li>
          </ul>
        </nav>
      </aside>

      {/* Render ConfirmationModal di sini */}
      <ConfirmationModal
        show={showLogoutModal}
        title="Konfirmasi Logout"
        message="Apakah Anda yakin ingin keluar dari akun ini?"
        onConfirm={confirmLogout}
        onCancel={cancelLogout}
      />
    </>
  );
};

export default Sidebar;