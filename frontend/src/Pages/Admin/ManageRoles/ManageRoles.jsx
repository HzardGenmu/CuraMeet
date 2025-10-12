import React, { useState, useEffect, useMemo, useRef } from 'react';
import './ManageRoles.css';
import { IoSearchOutline } from 'react-icons/io5';
import _ from 'lodash'; // Impor library lodash untuk perbandingan objek

// --- DUMMY DATA ---
const ALL_ROLES = ['Pasien', 'Dokter', 'Admin'];

const initialUsers = [
  { id: 'U001', nama: 'Budi Santoso', email: 'budi.santoso@example.com', role: 'Pasien' },
  { id: 'U002', nama: 'Dr. Anisa Putri', email: 'anisa.putri@example.com', role: 'Dokter' },
  { id: 'U003', nama: 'Admin Utama', email: 'admin@example.com', role: 'Admin' },
  { id: 'U004', nama: 'Siti Aminah', email: 'siti.aminah@example.com', role: 'Pasien' },
  { id: 'U005', nama: 'Dr. Chandra Wijaya', email: 'chandra.wijaya@example.com', role: 'Dokter' },
  { id: 'U006', nama: 'Herman Kusumo', email: 'herman.kusumo@example.com', role: 'Pasien' },
  { id: 'U007', nama: 'Maria Tan', email: 'maria.tan@example.com', role: 'Pasien' },
  { id: 'U008', nama: 'Dr. Eko Prasetyo', email: 'eko.prasetyo@example.com', role: 'Dokter' },
  { id: 'U009', nama: 'Dewi Lestari', email: 'dewi.lestari@example.com', role: 'Pasien' },
  { id: 'U010', nama: 'Rahmat Hidayat', email: 'rahmat.hidayat@example.com', role: 'Pasien' },
  { id: 'U011', nama: 'Faisal Ramadhan', email: 'faisal.r@example.com', role: 'Pasien' },
  { id: 'U012', nama: 'Putri Nurjanah', email: 'putri.n@example.com', role: 'Pasien' },
  { id: 'U013', nama: 'Joko Susilo', email: 'joko.s@example.com', role: 'Pasien' },
];

const USERS_PER_PAGE = 8; // Mengubah jumlah user per halaman agar list lebih kecil

const ManageRoles = () => {
  const [users, setUsers] = useState([]);
  const [originalUsers, setOriginalUsers] = useState([]); // State untuk menyimpan data awal
  const [searchTerm, setSearchTerm] = useState('');
  const [currentPage, setCurrentPage] = useState(1);
  const [hasUnsavedChanges, setHasUnsavedChanges] = useState(false); // Melacak perubahan

  useEffect(() => {
    // Di aplikasi nyata, Anda akan fetch data user dari API di sini
    // Misalnya: axios.get('/api/users').then(response => {
    //   setUsers(response.data);
    //   setOriginalUsers(response.data); // Simpan data awal
    // });
    const fetchedUsers = _.cloneDeep(initialUsers); // Clone untuk mencegah modifikasi langsung
    setUsers(fetchedUsers);
    setOriginalUsers(_.cloneDeep(fetchedUsers)); // Simpan salinan data awal
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

  const handleSaveRoles = () => {
    // Di aplikasi nyata, Anda akan mengirim semua perubahan role ke API backend
    console.log("Menyimpan perubahan roles:", users);
    alert('Perubahan role berhasil disimpan!');
    // Setelah berhasil menyimpan, update originalUsers ke users saat ini
    setOriginalUsers(_.cloneDeep(users)); 
    setHasUnsavedChanges(false); // Reset status perubahan
    // Jika ada API call, Anda mungkin ingin me-fetch ulang data untuk memastikan konsistensi
  };

  return (
    <div className="manage-roles-container">
      <h1 className="page-title">Kelola Role Pengguna</h1>

      <div className="search-bar">
        <IoSearchOutline size={20} className="search-icon" />
        <input
          type="text"
          placeholder="Cari pengguna berdasarkan nama atau email..."
          value={searchTerm}
          onChange={(e) => {
            setSearchTerm(e.target.value);
            setCurrentPage(1); // Reset halaman ke 1 saat pencarian
          }}
        />
      </div>

      <div className="roles-list-wrapper"> {/* Container untuk scroll */}
        {currentUsers.length > 0 ? (
          <table className="roles-table">
            <thead>
              <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
              </tr>
            </thead>
            <tbody>
              {currentUsers.map(user => (
                <tr key={user.id}>
                  <td>{user.nama}</td>
                  <td>{user.email}</td>
                  <td>
                    <select
                      value={user.role}
                      onChange={(e) => handleRoleChange(user.id, e.target.value)}
                      className="role-select"
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
          <p className="no-users-message">Tidak ada pengguna ditemukan.</p>
        )}
      </div>

      {/* Pagination Controls */}
      {filteredUsers.length > USERS_PER_PAGE && (
        <div className="pagination-controls">
          <button 
            onClick={() => handlePageChange(currentPage - 1)} 
            disabled={currentPage === 1}
            className="pagination-btn"
          >
            Previous
          </button>
          {[...Array(totalPages)].map((_, index) => (
            <button
              key={index + 1}
              onClick={() => handlePageChange(index + 1)}
              className={`pagination-btn ${currentPage === index + 1 ? 'active' : ''}`}
            >
              {index + 1}
            </button>
          ))}
          <button 
            onClick={() => handlePageChange(currentPage + 1)} 
            disabled={currentPage === totalPages}
            className="pagination-btn"
          >
            Next
          </button>
        </div>
      )}

      {/* Tombol Simpan selalu ada, tapi nonaktif jika tidak ada perubahan */}
      <div className="save-changes-action">
        <button 
          onClick={handleSaveRoles} 
          className="btn-primary"
          disabled={!hasUnsavedChanges} // Nonaktif jika tidak ada perubahan
        >
          Simpan Perubahan Role
        </button>
      </div>
    </div>
  );
};

export default ManageRoles;