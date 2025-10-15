import React, { useState, useEffect, useMemo } from 'react';
import axios from 'axios';

import { IoSearchOutline } from 'react-icons/io5';
import _ from 'lodash'; 

// --- KONFIGURASI ENDPOINT BACKEND ---
const API_BASE_URL = 'https://api.curameet.duckdns.org/api'; 
const USERS_PER_PAGE = 8;
const ALL_ROLES = ['Pasien', 'Dokter', 'Admin'];

const ManageRoles = () => {
  const [users, setUsers] = useState([]);
  const [originalUsers, setOriginalUsers] = useState([]); // State untuk menyimpan data awal
  const [searchTerm, setSearchTerm] = useState('');
  const [currentPage, setCurrentPage] = useState(1);
  const [hasUnsavedChanges, setHasUnsavedChanges] = useState(false); // Melacak perubahan

  useEffect(() => {
    // Fetch data user dari backend
    axios.get(`${API_BASE_URL}/users`)
      .then(response => {
        setUsers(response.data);
        setOriginalUsers(_.cloneDeep(response.data));
      })
      .catch(error => {
        console.error('Gagal fetch data user:', error);
        // Jika gagal, bisa tampilkan pesan error atau fallback ke data kosong
        setUsers([]);
        setOriginalUsers([]);
      });
  }, []);

  // Membandingkan users saat ini dengan originalUsers untuk menentukan hasUnsavedChanges
  useEffect(() => {
    // Gunakan deep comparison dari lodash untuk membandingkan array of objects
    const changesMade = !_.isEqual(users, originalUsers);
    setHasUnsavedChanges(changesMade);
  }, [users, originalUsers]);


  // Filter users berdasarkan search term
  const filteredUsers = useMemo(() => {
    if (!searchTerm) return users;
    return users.filter(user =>
      user.nama.toLowerCase().includes(searchTerm.toLowerCase()) ||
      user.email.toLowerCase().includes(searchTerm.toLowerCase())
    );
  }, [users, searchTerm]);

  // Pagination Logic
  const totalPages = Math.ceil(filteredUsers.length / USERS_PER_PAGE);
  const indexOfLastUser = currentPage * USERS_PER_PAGE;
  const indexOfFirstUser = indexOfLastUser - USERS_PER_PAGE;
  const currentUsers = filteredUsers.slice(indexOfFirstUser, indexOfLastUser);

  const handlePageChange = (pageNumber) => {
    setCurrentPage(pageNumber);
  };

  const handleRoleChange = (userId, newRole) => {
    setUsers(prevUsers =>
      prevUsers.map(user =>
        user.id === userId ? { ...user, role: newRole } : user
      )
    );
    // hasUnsavedChanges akan dihitung ulang oleh useEffect
  };

  const handleSaveRoles = async () => {
    try {
      // Kirim perubahan ke backend
      await axios.put(`${API_BASE_URL}/users/roles`, { users });
      alert('Perubahan role berhasil disimpan!');
      setOriginalUsers(_.cloneDeep(users));
      setHasUnsavedChanges(false);
      
    } catch (error) {
      console.error('Gagal menyimpan perubahan role:', error);
      alert('Gagal menyimpan perubahan role!');
    }
  };

  return (
    <div className="p-6 bg-gray-100 min-h-screen">
      <div className="bg-white rounded-xl shadow-lg p-6 lg:p-8">
        <h1 className="text-3xl font-semibold mb-6 text-gray-800">Kelola Role Pengguna</h1>

        <div className="flex items-center bg-white border border-gray-300 rounded-lg py-2 px-4 mb-4 shadow-sm max-w-xl">
          <IoSearchOutline size={20} className="text-gray-500 mr-3" />
          <input
            type="text"
            placeholder="Cari pengguna berdasarkan nama atau email..."
            value={searchTerm}
            onChange={(e) => {
              setSearchTerm(e.target.value);
              setCurrentPage(1); // Reset halaman ke 1 saat pencarian
            }}
            className="flex-grow border-none outline-none text-base text-gray-700 placeholder-gray-400"
          />
        </div>

        <div className="max-h-96 overflow-y-auto border border-gray-200 rounded-lg bg-white shadow-md mb-4">
          {currentUsers.length > 0 ? (
            <table className="w-full border-collapse">
              <thead className="sticky top-0 bg-gray-50 z-10">
                <tr>
                  <th className="py-3 px-4 text-left text-sm font-semibold text-gray-600 border-b border-gray-200">Nama</th>
                  <th className="py-3 px-4 text-left text-sm font-semibold text-gray-600 border-b border-gray-200">Email</th>
                  <th className="py-3 px-4 text-left text-sm font-semibold text-gray-600 border-b border-gray-200">Role</th>
                </tr>
              </thead>
              <tbody>
                {currentUsers.map(user => (
                  <tr key={user.id} className="hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                    <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">{user.nama}</td>
                    <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">{user.email}</td>
                    <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">
                      <select
                        value={user.role}
                        onChange={(e) => handleRoleChange(user.id, e.target.value)}
                        className="py-1.5 px-3 border border-gray-300 rounded-md bg-white text-sm cursor-pointer
                                   focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 ease-in-out"
                      >
                        {ALL_ROLES.map(role => (
                          <option key={role} value={role}>
                            {role}
                          </option>
                        ))}
                      </select>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          ) : (
            <p className="text-center p-6 text-gray-600 italic text-base">Tidak ada pengguna ditemukan.</p>
          )}
        </div>

        {/* Pagination Controls */}
        {filteredUsers.length > USERS_PER_PAGE && (
          <div className="flex justify-center items-center mt-4 mb-6">
            <button
              onClick={() => handlePageChange(currentPage - 1)}
              disabled={currentPage === 1}
              className="py-2 px-4 border border-gray-300 rounded-lg mx-1
                         bg-white text-gray-700 text-sm cursor-pointer
                         hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 ease-in-out"
            >
              Previous
            </button>
            {[...Array(totalPages)].map((_, index) => (
              <button
                key={index + 1}
                onClick={() => handlePageChange(index + 1)}
                className={`py-2 px-4 border border-gray-300 rounded-lg mx-1 text-sm
                            ${currentPage === index + 1 ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-gray-700 hover:bg-gray-100'}
                            transition-all duration-200 ease-in-out`}
              >
                {index + 1}
              </button>
            ))}
            <button
              onClick={() => handlePageChange(currentPage + 1)}
              disabled={currentPage === totalPages}
              className="py-2 px-4 border border-gray-300 rounded-lg mx-1
                         bg-white text-gray-700 text-sm cursor-pointer
                         hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 ease-in-out"
            >
              Next
            </button>
          </div>
        )}

        {/* Tombol Simpan selalu ada, tapi nonaktif jika tidak ada perubahan */}
        <div className="text-center mt-6">
          <button
            onClick={handleSaveRoles}
            className={`py-2.5 px-6 rounded-lg text-white text-lg font-medium transition-all duration-300 ease-in-out
                        ${hasUnsavedChanges ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-gray-400 cursor-not-allowed opacity-70'}`}
            disabled={!hasUnsavedChanges} // Nonaktif jika tidak ada perubahan
          >
            Simpan Perubahan Role
          </button>
        </div>
      </div>
    </div>
  );
};

export default ManageRoles;