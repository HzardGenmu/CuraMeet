import React, { useState } from "react";
import { NavLink, useNavigate } from "react-router-dom";
import { IoPersonCircleOutline, IoLogOutOutline } from "react-icons/io5";
import ConfirmationModal from "../ConfirmationModal/ConfirmationModal"; // Import modal
import { authService } from "../../services/authService";

const DoctorSidebar = () => {
  // Logic untuk Logout Dokter
  const [showLogoutModal, setShowLogoutModal] = useState(false);
  const [isLoggingOut, setIsLoggingOut] = useState(false);

  const navigate = useNavigate(); // Pastikan useNavigate diimport dari react-router-dom

  const handleLogout = () => {
    setShowLogoutModal(true);
  };

  const confirmLogout = () => {
    setShowLogoutModal(false);
    setIsLoggingOut(true);
    authService.logout();
    localStorage.removeItem("authToken");
    setTimeout(() => {
      setIsLoggingOut(false);
      window.location.href = "/login";
    }, 3000);
  };

  const cancelLogout = () => {
    setShowLogoutModal(false);
  };

  return (
    <>
      {/* Tambahkan 'fixed', 'top-0', 'left-0', dan 'z-40' */}
      <aside className="w-64 bg-emerald-700 text-white flex flex-col h-screen shadow-lg fixed top-0 left-0 z-40">
        {/* Profile Section (fixed at top) */}
        <div className="flex flex-col items-center justify-center p-6 border-b border-emerald-600">
          <IoPersonCircleOutline size={72} className="text-emerald-200 mb-3" />
          <h3 className="text-xl font-semibold text-white text-center">
            {authService.getCurrentUser().name}
          </h3>
        </div>

        {/* Navigation Section (scrollable, fills remaining space) */}
        {/* Menggunakan scrollbar-thin, scrollbar-thumb-emerald-500, scrollbar-track-emerald-700 */}
        <nav className="flex-grow p-4 overflow-y-auto scrollbar-thin scrollbar-thumb-emerald-500 scrollbar-track-emerald-700">
          <ul className="space-y-2">
            <li>
              <NavLink
                to="/dokter/janji-temu"
                className={({ isActive }) =>
                  `flex items-center p-3 rounded-lg text-lg hover:bg-emerald-600 transition-colors duration-200
                  ${isActive ? "bg-emerald-600 font-bold" : ""}`
                }
              >
                Janji Temu
              </NavLink>
            </li>
            <li>
              <NavLink
                to="/dokter/pasien"
                className={({ isActive }) =>
                  `flex items-center p-3 rounded-lg text-lg hover:bg-emerald-600 transition-colors duration-200
                  ${isActive ? "bg-emerald-600 font-bold" : ""}`
                }
              >
                Daftar Pasien
              </NavLink>
            </li>
            {/* Tambahkan item navigasi lainnya di sini jika ada */}
          </ul>
        </nav>

        {/* Logout Section (fixed at bottom) */}
        <div className="p-4 border-t border-emerald-600 mt-auto">
          <button
            onClick={handleLogout}
            className="flex items-center w-full p-3 rounded-lg text-lg text-red-100 hover:bg-red-700 hover:text-white transition-colors duration-200"
          >
            <IoLogOutOutline size={22} className="mr-3" /> Logout
          </button>
        </div>
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
