import React, { useState, useEffect, useMemo } from 'react';
import { IoSearchOutline, IoAddCircleOutline, IoPencilOutline, IoTrashOutline } from 'react-icons/io5';
import ConfirmationModal from '../../../components/ConfirmationModal/ConfirmationModal'; 
import DataFormModal from '../../../components/DataFormModal/DataFormModal'; 

// --- DUMMY DATA ---
const initialPatients = [
  { id: 'P001', name: 'Alif Kurniawan', email: 'alif.k@example.com', phone: '081234567890', status: 'Aktif' },
  { id: 'P002', name: 'Bunga Citra', email: 'bunga.c@example.com', phone: '081298765432', status: 'Aktif' },
  { id: 'P003', name: 'Cahyo Widodo', email: 'cahyo.w@example.com', phone: '087811223344', status: 'Nonaktif' },
  { id: 'P004', name: 'Dewi Lestari', email: 'dewi.l@example.com', phone: '085600112233', status: 'Aktif' },
  { id: 'P005', name: 'Eko Prasetyo', email: 'eko.p@example.com', phone: '081177889900', status: 'Aktif' },
  { id: 'P006', name: 'Fitriani Indah', email: 'fitriani.i@example.com', phone: '089644556677', status: 'Aktif' },
  { id: 'P007', name: 'Guruh Setiawan', email: 'guruh.s@example.com', phone: '081322334455', status: 'Nonaktif' },
  { id: 'P008', name: 'Hani Putri', email: 'hani.p@example.com', phone: '085799887766', status: 'Aktif' },
  { id: 'P009', name: 'Indra Jaya', email: 'indra.j@example.com', phone: '081866554433', status: 'Aktif' },
  { id: 'P010', name: 'Juwita Sari', email: 'juwita.s@example.com', phone: '087711223344', status: 'Aktif' },
];

const DATA_PER_PAGE = 8;

const DataManagement = () => {
  const [data, setData] = useState([]);
  const [searchTerm, setSearchTerm] = useState('');
  const [filterStatus, setFilterStatus] = useState('Semua');
  const [currentPage, setCurrentPage] = useState(1);

  const [showConfirmationModal, setShowConfirmationModal] = useState(false);
  const [itemToDelete, setItemToDelete] = useState(null);

  const [showFormModal, setShowFormModal] = useState(false);
  const [dataToEdit, setDataToEdit] = useState(null); // null untuk tambah, objek untuk edit

  useEffect(() => {
    // Di aplikasi nyata, fetch data dari API
    setData(initialPatients);
  }, []);

  const filteredData = useMemo(() => {
    let temp = data;
    if (filterStatus !== 'Semua') {
      temp = temp.filter(item => item.status === filterStatus);
    }
    if (searchTerm) {
      temp = temp.filter(item =>
        item.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        item.email.toLowerCase().includes(searchTerm.toLowerCase()) ||
        item.phone.toLowerCase().includes(searchTerm.toLowerCase()) ||
        item.id.toLowerCase().includes(searchTerm.toLowerCase())
      );
    }
    return temp;
  }, [data, searchTerm, filterStatus]);

  const totalPages = Math.ceil(filteredData.length / DATA_PER_PAGE);
  const indexOfLastItem = currentPage * DATA_PER_PAGE;
  const indexOfFirstItem = indexOfLastItem - DATA_PER_PAGE;
  const currentData = filteredData.slice(indexOfFirstItem, indexOfLastItem);

  const handlePageChange = (pageNumber) => {
    setCurrentPage(pageNumber);
  };

  const handleAddItem = () => {
    setDataToEdit(null); // Set null untuk mode tambah baru
    setShowFormModal(true);
  };

  const handleEditItem = (item) => {
    setDataToEdit(item); // Set item yang akan diedit
    setShowFormModal(true);
  };

  const handleDeleteItem = (item) => {
    setItemToDelete(item);
    setShowConfirmationModal(true);
  };

  const confirmDelete = () => {
    setData(prev => prev.filter(item => item.id !== itemToDelete.id));
    alert(`Data pasien ${itemToDelete.name} berhasil dihapus.`);
    setShowConfirmationModal(false);
    setItemToDelete(null);
  };

  const cancelDelete = () => {
    setShowConfirmationModal(false);
    setItemToDelete(null);
  };

  const handleSaveData = (newData) => {
    if (dataToEdit) {
      // Mode Edit
      setData(prev => prev.map(item =>
        item.id === newData.id ? { ...newData, id: item.id } : item
      ));
      alert(`Data pasien ${newData.name} berhasil diperbarui.`);
    } else {
      // Mode Tambah Baru
      const newId = `P${String(data.length + 1).padStart(3, '0')}`; // Simple ID generation
      setData(prev => [...prev, { ...newData, id: newId, status: 'Aktif' }]);
      alert(`Data pasien ${newData.name} berhasil ditambahkan.`);
    }
    setShowFormModal(false);
    setDataToEdit(null);
  };

  return (
    <div className="p-6 bg-gray-100 min-h-screen">
      <div className="bg-white rounded-xl shadow-lg p-6 lg:p-8">
        <h1 className="text-3xl font-semibold mb-8 text-gray-800 text-center">Manajemen Data Pasien</h1>

        <div className="flex flex-col md:flex-row justify-between items-center gap-4 md:gap-6 mb-8 p-6 bg-gray-50 rounded-lg shadow-inner">
          <div className="flex flex-col md:flex-row gap-4 md:gap-6 flex-grow w-full md:w-auto">
            <div className="flex items-center bg-white border border-gray-300 rounded-lg py-2 px-4 shadow-sm flex-grow min-w-[250px] focus-within:ring-2 focus-within:ring-emerald-500 focus-within:border-emerald-500 transition-all duration-200">
              <IoSearchOutline size={20} className="text-gray-500 mr-3" />
              <input
                type="text"
                placeholder="Cari pasien (ID, Nama, Email, Telepon)..."
                value={searchTerm}
                onChange={(e) => {
                  setSearchTerm(e.target.value);
                  setCurrentPage(1);
                }}
                className="flex-grow border-none outline-none text-base text-gray-700 placeholder-gray-400"
              />
            </div>
            <select
              value={filterStatus}
              onChange={(e) => {setFilterStatus(e.target.value); setCurrentPage(1);}}
              className="p-2.5 border border-gray-300 rounded-lg text-base bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 min-w-[150px]"
            >
              <option value="Semua">Semua Status</option>
              <option value="Aktif">Aktif</option>
              <option value="Nonaktif">Nonaktif</option>
            </select>
          </div>
          <button
            className="flex items-center gap-2 bg-emerald-600 text-white
                       py-2.5 px-6 rounded-lg font-medium text-base w-full md:w-auto
                       hover:bg-emerald-700 transition-all duration-300 ease-in-out"
            onClick={handleAddItem}
          >
            <IoAddCircleOutline className="text-xl" /> Tambah Pasien Baru
          </button>
        </div>

        <div className="max-h-[600px] overflow-y-auto border border-gray-200 rounded-xl bg-white shadow-md mb-8">
          {currentData.length > 0 ? (
            <table className="w-full border-collapse">
              <thead className="sticky top-0 bg-gray-50 z-10">
                <tr>
                  <th className="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">ID Pasien</th>
                  <th className="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Nama</th>
                  <th className="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Email</th>
                  <th className="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Telepon</th>
                  <th className="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Status</th>
                  <th className="py-3 px-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200 w-[120px]">Aksi</th>
                </tr>
              </thead>
              <tbody>
                {currentData.map(item => (
                  <tr key={item.id} className="hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                    <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">{item.id}</td>
                    <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">{item.name}</td>
                    <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">{item.email}</td>
                    <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">{item.phone}</td>
                    <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">
                      <span className={`px-3 py-1 rounded-full text-xs font-semibold text-white min-w-[70px] inline-block text-center
                                      ${item.status.toLowerCase() === 'aktif' ? 'bg-green-600' : 'bg-gray-500'}`}>
                        {item.status}
                      </span>
                    </td>
                    <td className="py-3 px-4 text-center text-sm text-gray-800 border-b border-gray-100 whitespace-nowrap">
                      <button
                        className="text-blue-600 hover:bg-blue-100 p-2 rounded-md transition-colors duration-200 mx-1"
                        onClick={() => handleEditItem(item)}
                      >
                        <IoPencilOutline size={18} />
                      </button>
                      <button
                        className="text-red-600 hover:bg-red-100 p-2 rounded-md transition-colors duration-200 mx-1"
                        onClick={() => handleDeleteItem(item)}
                      >
                        <IoTrashOutline size={18} />
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          ) : (
            <p className="text-center p-8 text-gray-600 italic text-base">Tidak ada data pasien yang ditemukan.</p>
          )}
        </div>

        {filteredData.length > DATA_PER_PAGE && (
          <div className="flex justify-center items-center mt-6">
            <button
              onClick={() => handlePageChange(currentPage - 1)}
              disabled={currentPage === 1}
              className="py-2 px-4 border border-gray-300 rounded-lg mx-1
                         bg-white text-gray-700 text-sm cursor-pointer
                         hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
            >
              Previous
            </button>
            {[...Array(totalPages)].map((_, index) => (
              <button
                key={index + 1}
                onClick={() => handlePageChange(index + 1)}
                className={`py-2 px-4 border border-gray-300 rounded-lg mx-1 text-sm
                            ${currentPage === index + 1 ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-gray-700 hover:bg-gray-100'}
                            transition-all duration-200`}
              >
                {index + 1}
              </button>
            ))}
            <button
              onClick={() => handlePageChange(currentPage + 1)}
              disabled={currentPage === totalPages}
              className="py-2 px-4 border border-gray-300 rounded-lg mx-1
                         bg-white text-gray-700 text-sm cursor-pointer
                         hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
            >
              Next
            </button>
          </div>
        )}

        {/* Confirmation Modal */}
        <ConfirmationModal
          show={showConfirmationModal}
          title="Konfirmasi Hapus Data"
          message={`Apakah Anda yakin ingin menghapus data pasien "${itemToDelete?.name}" (${itemToDelete?.id})?`}
          onConfirm={confirmDelete}
          onCancel={cancelDelete}
        />

        {/* Data Form Modal (Add/Edit) */}
        <DataFormModal
          show={showFormModal}
          onClose={() => setShowFormModal(false)}
          onSave={handleSaveData}
          initialData={dataToEdit}
        />
      </div>
    </div>
  );
};

export default DataManagement;