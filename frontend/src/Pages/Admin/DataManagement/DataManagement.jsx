// src/pages/admin/DataManagement.jsx
import React, { useState, useEffect, useMemo } from 'react';
import { IoSearchOutline, IoAddCircleOutline, IoPencilOutline, IoTrashOutline } from 'react-icons/io5';
import ConfirmationModal from '../../../components/ConfirmationModal/ConfirmationModal';
import DataFormModal from '../../../components/DataFormModal/DataFormModal';
import { patientService } from '../../../services/patientService'; // Service untuk CRUD pasien
import { authService } from '../../../services/authService'; // Service untuk registrasi (tambah)

const DATA_PER_PAGE = 8;

// Fungsi helper untuk mapping data API ke format UI
const mapToView = (patientData) => ({
    id: patientData.id, // ID pasien dari tabel patients
    userId: patientData.user_id, // ID user dari tabel users
    name: patientData.full_name, // Map full_name ke name
    email: patientData.user?.email || patientData.email || 'N/A', // Ambil email dari relasi user jika ada
    phone: patientData.phone || 'N/A',
    nik: patientData.NIK || 'N/A', // Map NIK ke nik
    status: patientData.status || 'Aktif', // Default status jika tidak ada
});

// Fungsi helper untuk mapping data UI ke format API (update)
const mapToApiUpdate = (viewData) => ({
    full_name: viewData.name,
    email: viewData.email,
    phone: viewData.phone,
    nik: viewData.nik, // Map nik ke NIK
    // Tambahkan field lain yang bisa diupdate jika perlu
});

// Fungsi helper untuk mapping data UI ke format API (register)
const mapToApiRegister = (viewData) => ({
    name: viewData.name, // Nama untuk tabel users
    email: viewData.email,
    password: viewData.password, // Password dibutuhkan untuk user baru
    password_confirmation: viewData.password, // Konfirmasi password
    role: 'patient', // Role default
    // Data spesifik untuk tabel patients
    full_name: viewData.name, // Nama untuk tabel patients
    phone: viewData.phone,
    NIK: viewData.nik,
});


const DataManagement = () => {
    const [data, setData] = useState([]); // Data yang sudah di-map ke format UI
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');

    const [searchTerm, setSearchTerm] = useState('');
    const [filterStatus, setFilterStatus] = useState('Semua');
    const [currentPage, setCurrentPage] = useState(1);

    const [showConfirmationModal, setShowConfirmationModal] = useState(false);
    const [itemToDelete, setItemToDelete] = useState(null);

    const [showFormModal, setShowFormModal] = useState(false);
    const [dataToEdit, setDataToEdit] = useState(null); // null = tambah, object = edit

    // Fungsi untuk fetch data dari backend
    const fetchData = async () => {
        setLoading(true);
        setError('');
        try {
            // Menggunakan search dengan string kosong untuk mengambil semua pasien
            const response = await patientService.search('');
            if (response.success && Array.isArray(response.patients)) {
                setData(response.patients.map(mapToView)); // Map data API ke format UI
            } else {
                setData([]);
                setError(response.message || 'Gagal mengambil data pasien.');
            }
        } catch (err) {
            console.error('Fetch data error:', err);
            setError(err.response?.data?.message || 'Terjadi kesalahan saat mengambil data.');
            setData([]); // Pastikan data kosong jika error
        } finally {
            setLoading(false);
        }
    };

    // Load data saat komponen pertama kali dimuat
    useEffect(() => {
        fetchData();
    }, []);

    // Logika filter dan paginasi (menggunakan data yang sudah di-map)
    const filteredData = useMemo(() => {
        let temp = data;
        if (filterStatus !== 'Semua') {
            temp = temp.filter(item => item.status === filterStatus);
        }
        if (searchTerm) {
            temp = temp.filter(item =>
                item.name?.toLowerCase().includes(searchTerm.toLowerCase()) ||
                item.email?.toLowerCase().includes(searchTerm.toLowerCase()) ||
                item.phone?.toLowerCase().includes(searchTerm.toLowerCase()) ||
                String(item.id)?.toLowerCase().includes(searchTerm.toLowerCase()) // ID pasien
            );
        }
        return temp;
    }, [data, searchTerm, filterStatus]);

    const totalPages = Math.ceil(filteredData.length / DATA_PER_PAGE);
    const currentData = filteredData.slice((currentPage - 1) * DATA_PER_PAGE, currentPage * DATA_PER_PAGE);
    const handlePageChange = (page) => setCurrentPage(page);

    // --- Handler Aksi CRUD ---

    const handleAddItem = () => {
        setDataToEdit(null);
        setShowFormModal(true);
    };

    const handleEditItem = (item) => {
        setDataToEdit(item);
        setShowFormModal(true);
    };

    const handleDeleteItem = (item) => {
        setItemToDelete(item);
        setShowConfirmationModal(true);
    };

    const confirmDelete = async () => {
        if (!itemToDelete) return;
        setError('');
        setSuccess('');
        try {
            // Panggil API delete
            const response = await patientService.deleteById(itemToDelete.id);
            if (!response || !response.success) { // Periksa jika response null atau success false
                 throw new Error(response?.message || 'Gagal menghapus data pasien.');
            }
            setSuccess(`Data pasien ${itemToDelete.name} berhasil dihapus.`);
            await fetchData(); // Muat ulang data
        } catch (err) {
            console.error('Delete error:', err);
            setError(err.message || 'Terjadi kesalahan saat menghapus data.');
        } finally {
            setShowConfirmationModal(false);
            setItemToDelete(null);
        }
    };

    const cancelDelete = () => {
        setShowConfirmationModal(false);
        setItemToDelete(null);
    };

    const handleSaveData = async (formData) => {
        setError('');
        setSuccess('');
        try {
            let response;
            if (dataToEdit) {
                // Mode Edit: Panggil patientService.updateProfile
                const apiPayload = mapToApiUpdate(formData);
                response = await patientService.updateProfile(dataToEdit.id, apiPayload);
                if (!response || !response.success) {
                     throw new Error(response?.message || 'Gagal memperbarui data pasien.');
                }
                setSuccess(`Data pasien ${formData.name} berhasil diperbarui.`);

            } else {
                // Mode Tambah: Panggil authService.register
                if (!formData.password) {
                    throw new Error('Password wajib diisi untuk pasien baru.');
                }
                const apiPayload = mapToApiRegister(formData);
                response = await authService.register(apiPayload);
                if (!response || !response.success) {
                     throw new Error(response?.message || response?.errors?.join(', ') || 'Gagal menambahkan pasien baru.');
                }
                setSuccess(`Data pasien ${formData.name} berhasil ditambahkan.`);
            }
            setShowFormModal(false);
            setDataToEdit(null);
            await fetchData(); // Muat ulang data
        } catch (err) {
            console.error('Save error:', err);
            setError(err.message || 'Terjadi kesalahan saat menyimpan data.');
        }
    };

    // --- Render ---

    if (loading && data.length === 0) {
      return <div className="p-8 text-center text-gray-600">Memuat data pasien...</div>;
    }

    return (
        <div className="p-6 bg-gray-100 min-h-screen">
            <div className="bg-white rounded-xl shadow-lg p-6 lg:p-8">
                <h1 className="text-3xl font-semibold mb-8 text-gray-800 text-center">Manajemen Data Pasien</h1>

                {error && <div className="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-center text-sm">⚠️ {error}</div>}
                {success && <div className="bg-green-100 text-green-700 p-3 rounded-lg mb-4 text-center text-sm">✅ {success}</div>}

                {/* Filter dan Tombol Tambah */}
                <div className="flex flex-col md:flex-row justify-between items-center gap-4 md:gap-6 mb-8 p-6 bg-gray-50 rounded-lg shadow-inner">
                    <div className="flex flex-col md:flex-row gap-4 md:gap-6 flex-grow w-full md:w-auto">
                        {/* Search Input */}
                        <div className="flex items-center bg-white border border-gray-300 rounded-lg py-2 px-4 shadow-sm flex-grow min-w-[250px] focus-within:ring-2 focus-within:ring-emerald-500">
                            <IoSearchOutline size={20} className="text-gray-500 mr-3" />
                            <input
                                type="text"
                                placeholder="Cari pasien..."
                                value={searchTerm}
                                onChange={(e) => { setSearchTerm(e.target.value); setCurrentPage(1); }}
                                className="flex-grow border-none outline-none text-base text-gray-700 placeholder-gray-400"
                            />
                        </div>
                        {/* Filter Status */}
                        <select
                            value={filterStatus}
                            onChange={(e) => { setFilterStatus(e.target.value); setCurrentPage(1); }}
                            className="p-2.5 border border-gray-300 rounded-lg text-base bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 min-w-[150px]"
                        >
                            <option value="Semua">Semua Status</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
                    </div>
                    {/* Tombol Tambah */}
                    <button
                        className="flex items-center gap-2 bg-emerald-600 text-white py-2.5 px-6 rounded-lg font-medium text-base w-full md:w-auto hover:bg-emerald-700 transition"
                        onClick={handleAddItem}
                    >
                        <IoAddCircleOutline className="text-xl" /> Tambah Pasien Baru
                    </button>
                </div>

                {/* Tabel Data */}
                <div className="overflow-x-auto border border-gray-200 rounded-xl bg-white shadow-md mb-8">
                    <table className="w-full border-collapse min-w-[800px]">
                        <thead className="sticky top-0 bg-gray-50 z-10">
                            <tr>
                                <th className="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b">ID Pasien</th>
                                <th className="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b">Nama</th>
                                <th className="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b">Email</th>
                                <th className="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b">Telepon</th>
                                <th className="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b">Status</th>
                                <th className="py-3 px-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-b w-[120px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {currentData.length > 0 ? (
                                currentData.map(item => (
                                    <tr key={item.id} className="hover:bg-gray-50 transition-colors">
                                        <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">{item.id}</td>
                                        <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">{item.name}</td>
                                        <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">{item.email}</td>
                                        <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">{item.phone}</td>
                                        <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">
                                            <span className={`px-3 py-1 rounded-full text-xs font-semibold text-white min-w-[70px] inline-block text-center ${item.status?.toLowerCase() === 'aktif' ? 'bg-green-600' : 'bg-gray-500'}`}>
                                                {item.status}
                                            </span>
                                        </td>
                                        <td className="py-3 px-4 text-center text-sm border-b border-gray-100 whitespace-nowrap">
                                            <button
                                                className="text-blue-600 hover:bg-blue-100 p-2 rounded-md transition-colors mx-1"
                                                onClick={() => handleEditItem(item)}
                                                title="Edit Data"
                                            >
                                                <IoPencilOutline size={18} />
                                            </button>
                                            <button
                                                className="text-red-600 hover:bg-red-100 p-2 rounded-md transition-colors mx-1"
                                                onClick={() => handleDeleteItem(item)}
                                                title="Hapus Data"
                                            >
                                                <IoTrashOutline size={18} />
                                            </button>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan="6" className="text-center p-8 text-gray-600 italic">Tidak ada data pasien yang ditemukan.</td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>

                {/* Paginasi */}
                {totalPages > 1 && (
                    <div className="flex justify-center items-center mt-6">
                       <button onClick={() => handlePageChange(currentPage - 1)} disabled={currentPage === 1} className="py-2 px-4 border border-gray-300 rounded-lg mx-1 bg-white text-gray-700 text-sm hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">
                          Previous
                       </button>
                       {[...Array(totalPages)].map((_, index) => (
                           <button
                             key={index + 1}
                             onClick={() => handlePageChange(index + 1)}
                             className={`py-2 px-4 border rounded-lg mx-1 text-sm ${currentPage === index + 1 ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'}`}
                           >
                              {index + 1}
                           </button>
                       ))}
                       <button onClick={() => handlePageChange(currentPage + 1)} disabled={currentPage === totalPages} className="py-2 px-4 border border-gray-300 rounded-lg mx-1 bg-white text-gray-700 text-sm hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">
                          Next
                       </button>
                    </div>
                )}

                {/* Modal Konfirmasi Hapus */}
                <ConfirmationModal
                    show={showConfirmationModal}
                    title="Konfirmasi Hapus Data"
                    message={`Apakah Anda yakin ingin menghapus data pasien "${itemToDelete?.name}" (${itemToDelete?.id})? Tindakan ini tidak dapat dibatalkan.`}
                    onConfirm={confirmDelete}
                    onCancel={cancelDelete}
                />

                {/* Modal Form Tambah/Edit */}
                <DataFormModal
                    show={showFormModal}
                    onClose={() => setShowFormModal(false)}
                    onSave={handleSaveData}
                    initialData={dataToEdit}
                    // Menambahkan prop untuk membedakan mode tambah (membutuhkan password)
                    isEditMode={!!dataToEdit}
                />
            </div>
        </div>
    );
};

export default DataManagement;