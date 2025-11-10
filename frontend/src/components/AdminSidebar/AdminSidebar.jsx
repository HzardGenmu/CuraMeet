import React, { useState } from "react";
import { NavLink, useNavigate } from "react-router-dom";
import {
  IoShieldOutline,
  IoPeopleOutline,
  IoLogOutOutline,
  IoDocumentTextOutline,
  IoAnalyticsOutline,
  IoServer,
} from "react-icons/io5";
import ConfirmationModal from "../ConfirmationModal/ConfirmationModal";
import { authService } from "../../services/authService";

const AdminSidebar = () => {
  const [showLogoutModal, setShowLogoutModal] = useState(false);
  const navigate = useNavigate();

  const handleLogout = () => {
    setShowLogoutModal(true);
  };

  const confirmLogout = () => {
    authService.logout();
    navigate("/login");
    setShowLogoutModal(false);
  };

  const cancelLogout = () => {
    setShowLogoutModal(false);
  };

  return (
    <>
      {/* Tambahkan 'fixed', 'top-0', 'left-0', dan 'z-40' (atau nilai z-index yang sesuai) */}
      <aside className="w-64 bg-slate-800 text-white flex flex-col h-screen shadow-lg fixed top-0 left-0 z-40">
        {/* Profile Section (fixed at top) */}
        <div className="flex flex-col items-center justify-center p-6 border-b border-slate-700">
          <IoShieldOutline size={72} className="text-slate-300 mb-3" />
          <h3 className="text-xl font-semibold text-white">Admin</h3>
        </div>

        {/* Navigation Section (scrollable, fills remaining space) */}
        <nav className="flex-grow p-4 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-500 scrollbar-track-slate-800">
          <ul className="space-y-2">
            <li>
              <NavLink
                to="/admin/kelola-role"
                className={({ isActive }) =>
                  `flex items-center p-3 rounded-lg text-lg hover:bg-slate-700 transition-colors duration-200
                  ${
                    isActive
                      ? "bg-slate-700 font-bold text-teal-300"
                      : "text-slate-100"
                  }`
                }
              >
                <IoPeopleOutline size={22} className="mr-3" /> Kelola Role
              </NavLink>
            </li>
            <li>
              <NavLink
                to="/admin/log-viewer"
                className={({ isActive }) =>
                  `flex items-center p-3 rounded-lg text-lg hover:bg-slate-700 transition-colors duration-200
                  ${
                    isActive
                      ? "bg-slate-700 font-bold text-teal-300"
                      : "text-slate-100"
                  }`
                }
              >
                <IoDocumentTextOutline size={22} className="mr-3" /> Log
                Aktivitas
              </NavLink>
            </li>
            <li>
              <NavLink
                to="/admin/system-monitoring"
                className={({ isActive }) =>
                  `flex items-center p-3 rounded-lg text-lg hover:bg-slate-700 transition-colors duration-200
                  ${
                    isActive
                      ? "bg-slate-700 font-bold text-teal-300"
                      : "text-slate-100"
                  }`
                }
              >
                <IoAnalyticsOutline size={22} className="mr-3" /> Monitoring
                Sistem
              </NavLink>
            </li>
            <li>
              <NavLink
                to="/admin/data-management"
                className={({ isActive }) =>
                  `flex items-center p-3 rounded-lg text-lg hover:bg-slate-700 transition-colors duration-200
                  ${
                    isActive
                      ? "bg-slate-700 font-bold text-teal-300"
                      : "text-slate-100"
                  }`
                }
              >
                <IoServer size={22} className="mr-3" /> Manajemen Data
              </NavLink>
            </li>
            {/* Tambahkan item navigasi lainnya di sini jika ada */}
          </ul>
        </nav>

        {/* Logout Section (fixed at bottom) */}
        <div className="p-4 border-t border-slate-700 mt-auto">
          <button
            onClick={handleLogout}
            className="flex items-center w-full p-3 rounded-lg text-lg text-red-300 hover:bg-red-700 hover:text-white transition-colors duration-200"
          >
            <IoLogOutOutline size={22} className="mr-3" /> Logout
          </button>
        </div>
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
