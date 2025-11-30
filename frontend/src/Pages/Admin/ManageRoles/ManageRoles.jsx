import React, { useState, useEffect, useMemo } from "react";
import { adminService } from "../../../services/adminService";
import { doctorService } from "../../../services/doctorService";
import { patientService } from "../../../services/patientService";
import { IoSearchOutline } from "react-icons/io5";

const USERS_PER_PAGE = 8;
const ALL_ROLES = ["patient", "doctor", "admin"];

// Helper untuk menampilkan nama role dengan huruf besar di UI
const capitalize = (s) => {
  if (typeof s !== "string" || !s) return "";
  return s.charAt(0).toUpperCase() + s.slice(1);
};

const ManageRoles = () => {
  const [users, setUsers] = useState([]);
  const [searchTerm, setSearchTerm] = useState("");
  const [currentPage, setCurrentPage] = useState(1);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [success, setSuccess] = useState("");

  // Logika untuk mengambil dan menggabungkan data
  const fetchCombinedData = async () => {
    setLoading(true);
    setError("");
    try {
      const response = await adminService.getAllUsers();
      // console.log(response);
      let combinedUsers = [];
      if (response.success && Array.isArray(response.users)) {
        combinedUsers = response.users.map((u) => ({
          id: u.id,
          userId: u.id,
          name: u.name,
          email: u.email,
          role: u.role,
        }));
      }

      setUsers(combinedUsers);
    } catch (err) {
      console.error("Gagal fetch data gabungan:", err);
      setError("Terjadi kesalahan saat mengambil data dokter atau pasien.");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchCombinedData();
  }, []);

  // Fungsi untuk menangani perubahan role
  const handleRoleChange = async (userId, newRole) => {
    const originalUsers = [...users];
    setUsers((prevUsers) =>
      prevUsers.map((user) =>
        user.userId === userId ? { ...user, role: newRole } : user
      )
    );

    setError("");
    setSuccess("");
    try {
      const response = await adminService.manageUserRole(userId, newRole);
      if (!response.success) {
        throw new Error(response.message || "Gagal menyimpan perubahan.");
      }
      setSuccess(
        `Role untuk pengguna berhasil diubah menjadi ${capitalize(newRole)}.`
      );
      // Refresh data setelah perubahan role berhasil
      fetchCombinedData();
    } catch (error) {
      console.error("Gagal menyimpan perubahan role:", error);
      setError(`Gagal mengubah role. Mengembalikan ke semula.`);
      setUsers(originalUsers);
    }
  };
  // Logika untuk filter dan paginasi
  const filteredUsers = useMemo(() => {
    if (!searchTerm) return users;
    return users.filter(
      (user) =>
        (user.name &&
          user.name.toLowerCase().includes(searchTerm.toLowerCase())) ||
        (user.email &&
          user.email.toLowerCase().includes(searchTerm.toLowerCase()))
    );
  }, [users, searchTerm]);

  const totalPages = Math.ceil(filteredUsers.length / USERS_PER_PAGE);
  const currentUsers = filteredUsers.slice(
    (currentPage - 1) * USERS_PER_PAGE,
    currentPage * USERS_PER_PAGE
  );

  const handlePageChange = (page) => setCurrentPage(page);

  if (loading) {
    return (
      <div className="p-8 text-center text-gray-600">
        Memuat daftar pengguna...
      </div>
    );
  }

  return (
    <div className="p-6 bg-gray-100 min-h-screen">
      <div className="bg-white rounded-xl shadow-lg p-6 lg:p-8">
        <h1 className="text-3xl font-semibold mb-6 text-gray-800">
          Kelola Role Pengguna
        </h1>

        {error && (
          <div className="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-center text-sm">
            ⚠️ {error}
          </div>
        )}
        {success && (
          <div className="bg-green-100 text-green-700 p-3 rounded-lg mb-4 text-center text-sm">
            ✅ {success}
          </div>
        )}

        <div className="flex items-center bg-white border border-gray-300 rounded-lg py-2 px-4 mb-4 shadow-sm max-w-xl">
          <IoSearchOutline size={20} className="text-gray-500 mr-3" />
          <input
            type="text"
            placeholder="Cari berdasarkan nama atau email..."
            value={searchTerm}
            onChange={(e) => {
              setSearchTerm(e.target.value);
              setCurrentPage(1);
            }}
            className="flex-grow border-none outline-none text-base text-gray-700 placeholder-gray-400"
          />
        </div>

        <div className="overflow-x-auto border border-gray-200 rounded-lg bg-white shadow-md mb-8">
          <table className="w-full border-collapse min-w-[600px]">
            <thead className="sticky top-0 bg-gray-50 z-10">
              <tr>
                <th className="py-3 px-4 text-left text-sm font-semibold text-gray-600 border-b">
                  Nama
                </th>
                <th className="py-3 px-4 text-left text-sm font-semibold text-gray-600 border-b">
                  Email
                </th>
                <th className="py-3 px-4 text-left text-sm font-semibold text-gray-600 border-b">
                  Role
                </th>
              </tr>
            </thead>
            <tbody>
              {currentUsers.length > 0 ? (
                currentUsers.map((user) => (
                  <tr
                    key={user.id}
                    className="hover:bg-gray-50 transition-colors"
                  >
                    <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">
                      {user.name}
                    </td>
                    <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">
                      {user.email}
                    </td>
                    <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">
                      <select
                        value={user.role}
                        onChange={(e) =>
                          handleRoleChange(user.id, e.target.value)
                        }
                        className="py-1.5 px-3 border border-gray-300 rounded-md bg-white text-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500"
                      >
                        {ALL_ROLES.map((role) => (
                          <option key={role} value={role}>
                            {capitalize(role)}
                          </option>
                        ))}
                      </select>
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan="3" className="text-center p-6 text-gray-600">
                    Tidak ada data ditemukan.
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>

        {/* ====================================================== */}
        {/* ✅ BAGIAN PAGINASI DITAMBAHKAN DI SINI                 */}
        {/* ====================================================== */}
        {totalPages > 1 && (
          <div className="flex justify-center items-center mt-6">
            <button
              onClick={() => handlePageChange(currentPage - 1)}
              disabled={currentPage === 1}
              className="py-2 px-4 border border-gray-300 rounded-lg mx-1 bg-white text-gray-700 text-sm cursor-pointer hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
            >
              Previous
            </button>
            {[...Array(totalPages)].map((_, index) => (
              <button
                key={index + 1}
                onClick={() => handlePageChange(index + 1)}
                className={`py-2 px-4 border rounded-lg mx-1 text-sm ${
                  currentPage === index + 1
                    ? "bg-emerald-600 text-white border-emerald-600"
                    : "bg-white text-gray-700 border-gray-300 hover:bg-gray-100"
                } transition-all duration-200`}
              >
                {index + 1}
              </button>
            ))}
            <button
              onClick={() => handlePageChange(currentPage + 1)}
              disabled={currentPage === totalPages}
              className="py-2 px-4 border border-gray-300 rounded-lg mx-1 bg-white text-gray-700 text-sm cursor-pointer hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
            >
              Next
            </button>
          </div>
        )}
      </div>
    </div>
  );
};

export default ManageRoles;
