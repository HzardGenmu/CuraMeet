import React, { useState, useEffect, useMemo } from 'react';
import './DataManagement.css';
import { IoSearchOutline, IoAddCircleOutline, IoPencilOutline, IoTrashOutline } from 'react-icons/io5';
import ConfirmationModal from '../../../components/ConfirmationModal/ConfirmationModal'; // Pastikan path benar
import DataFormModal from '../../../components/DataFormModal/DataFormModal'; // Impor modal baru

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
    <div className="data-management-container">
      <h1 className="page-title">Manajemen Data Pasien</h1>

      <div className="controls-section">
        <div className="search-filter-group">
          <div className="search-bar">
            <IoSearchOutline size={20} className="search-icon" />
            <input
              type="text"
              placeholder="Cari pasien (ID, Nama, Email, Telepon)..."
              value={searchTerm}
              onChange={(e) => {
                setSearchTerm(e.target.value);
                setCurrentPage(1);
              }}
            />
          </div>
          <select value={filterStatus} onChange={(e) => {setFilterStatus(e.target.value); setCurrentPage(1);}} className="filter-select">
            <option value="Semua">Semua Status</option>
            <option value="Aktif">Aktif</option>
            <option value="Nonaktif">Nonaktif</option>
          </select>
        </div>
        <button className="btn-primary add-data-btn" onClick={handleAddItem}>
          <IoAddCircleOutline /> Tambah Pasien Baru
        </button>
      </div>

      <div className="data-table-wrapper">
        {currentData.length > 0 ? (
          <table className="data-table">
            <thead>
              <tr>
                <th>ID Pasien</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Status</th>
                <th className="action-column">Aksi</th>
              </tr>
            </thead>
            <tbody>
              {currentData.map(item => (
                <tr key={item.id}>
                  <td>{item.id}</td>
                  <td>{item.name}</td>
                  <td>{item.email}</td>
                  <td>{item.phone}</td>
                  <td>
                    <span className={`status-badge ${item.status.toLowerCase()}`}>
                      {item.status}
                    </span>
                  </td>
                  <td className="action-column">
                    <button className="btn-action edit" onClick={() => handleEditItem(item)}>
                      <IoPencilOutline />
                    </button>
                    <button className="btn-action delete" onClick={() => handleDeleteItem(item)}>
                      <IoTrashOutline />
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        ) : (
          <p className="no-data-message">Tidak ada data pasien yang ditemukan.</p>
        )}
      </div>

      {filteredData.length > DATA_PER_PAGE && (
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
  );
};

export default DataManagement;