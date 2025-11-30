import React, { useState } from "react";
import { NavLink, useNavigate } from "react-router-dom";
import { IoPersonCircleOutline, IoLogOutOutline } from "react-icons/io5";
import ConfirmationModal from "../ConfirmationModal/ConfirmationModal";
import { authService } from "../../services/authService";
import LogoutLoadingOverlay from "../LogoutLoadingOverlay";

const Sidebar = () => {
  const navigate = useNavigate();
  const [showLogoutModal, setShowLogoutModal] = useState(false);
  const [isLoggingOut, setIsLoggingOut] = useState(false);

  const handleLogout = () => {
    setShowLogoutModal(true);
  };

  const confirmLogout = () => {
    setShowLogoutModal(false);
    setIsLoggingOut(true);
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
      <aside className="w-64 bg-green-600 border-r border-gray-200 flex flex-col h-screen shadow-lg fixed top-0 left-0 z-40">
        <div className="p-8 text-center border-b border-gray-200">
          <IoPersonCircleOutline
            size={60}
            className="text-white mx-auto mb-2"
          />
          <h3 className="m-0 font-semibold text-gray-800 text-lg">
            {authService.getCurrentUser().name}
          </h3>
        </div>

        <nav className="flex-grow py-4 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-white">
          <ul className="space-y-2">
            {" "}
            {/* Menambahkan space-y-2 untuk jarak antar NavLink */}
            <li>
              <NavLink
                to="/janji-temu"
                className={({ isActive }) =>
                  `flex items-center gap-2 py-4 px-8 text-gray-700 font-medium transition duration-300 ease-in-out border-l-4 ${
                    isActive
                      ? "bg-green-100 text-emerald-600 border-emerald-600" // active
                      : "border-transparent hover:bg-gray-50 hover:text-emerald-600" // non-active & hover
                  }`
                }
              >
                Janji Temu
              </NavLink>
            </li>
            <li>
              <NavLink
                to="/rekam-medis"
                className={({ isActive }) =>
                  `flex items-center gap-2 py-4 px-8 text-gray-700 font-medium transition duration-300 ease-in-out border-l-4 ${
                    isActive
                      ? "bg-green-100 text-emerald-600 border-emerald-600"
                      : "border-transparent hover:bg-gray-50 hover:text-emerald-600"
                  }`
                }
              >
                Rekam Medis
              </NavLink>
            </li>
            <li>
              <NavLink
                to="/profil"
                className={({ isActive }) =>
                  `flex items-center gap-2 py-4 px-8 text-gray-700 font-medium transition duration-300 ease-in-out border-l-4 ${
                    isActive
                      ? "bg-green-100 text-emerald-600 border-emerald-600"
                      : "border-transparent hover:bg-gray-50 hover:text-emerald-600"
                  }`
                }
              >
                Profil
              </NavLink>
            </li>
            {/* <li>
              <NavLink
                to="/catatan-medis"
                className={({ isActive }) =>
                  `flex items-center gap-2 py-4 px-8 text-gray-700 font-medium transition duration-300 ease-in-out border-l-4 ${
                    isActive
                      ? "bg-green-100 text-emerald-600 border-emerald-600"
                      : "border-transparent hover:bg-gray-50 hover:text-emerald-600"
                  }`
                }
              >
                Catatan Medis
              </NavLink>
            </li> */}
            {/* Tambahkan item navigasi lainnya di sini jika ada */}
          </ul>
        </nav>

        {/* Logout Section (fixed at bottom) */}
        <div className="mt-auto py-4 border-t border-gray-200">
          <button
            onClick={handleLogout}
            className="flex items-center gap-2 w-full py-4 px-8 text-red-600 font-medium transition duration-300 ease-in-out
                       hover:bg-red-50 border-l-4 border-transparent hover:border-red-600"
          >
            <IoLogOutOutline
              size={22}
              className="text-red-500 group-hover:text-red-600"
            />{" "}
            Logout
          </button>
        </div>
      </aside>
      <LogoutLoadingOverlay isVisible={isLoggingOut} />
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
