import React, { useState, useEffect, useMemo } from 'react';
import './LogViewer.css';
import { IoSearchOutline, IoDownloadOutline, IoFilterOutline } from 'react-icons/io5';
import DatePicker from 'react-datepicker'; // Untuk pemilih tanggal
import 'react-datepicker/dist/react-datepicker.css'; // Gaya default datepicker
import { format } from 'date-fns'; // Untuk format tanggal

// --- DUMMY DATA ---
const ALL_LOG_TYPES = ['LOGIN_SUKSES', 'LOGIN_GAGAL', 'ROLE_CHANGE', 'UPDATE_DATA', 'HAPUS_DATA', 'TAMBAH_DATA'];
const ALL_USERS_FOR_FILTER = ['Semua', 'Budi Santoso', 'Dr. Anisa Putri', 'Admin Utama', 'Siti Aminah', 'Herman Kusumo'];

const dummyLogs = [
  { id: 'L001', timestamp: new Date(2023, 9, 26, 10, 30, 15), user: 'Budi Santoso', email: 'budi.santoso@example.com', action: 'LOGIN_SUKSES', detail: 'Autentikasi', status: 'Sukses', ipAddress: '203.0.113.88', type: 'activity' },
  { id: 'L002', timestamp: new Date(2023, 9, 26, 10, 30, 45), user: 'Budi Santoso', email: 'budi.santoso@example.com', action: 'UPDATE_DATA', detail: 'Rekam Medis', status: 'Sukses', ipAddress: '203.0.113.88', type: 'activity' },
  { id: 'L003', timestamp: new Date(2023, 9, 26, 11, 0, 0), user: 'Admin Utama', email: 'admin@example.com', action: 'ROLE_CHANGE', detail: 'Ubah role U002 ke Dokter', status: 'Sukses', ipAddress: '192.168.1.50', type: 'audit' },
  { id: 'L004', timestamp: new Date(2023, 9, 26, 11, 5, 20), user: 'Herman Kusumo', email: 'herman.kusumo@example.com', action: 'LOGIN_GAGAL', detail: 'Password salah', status: 'Gagal', ipAddress: '203.0.113.90', type: 'activity' },
  { id: 'L005', timestamp: new Date(2023, 9, 27, 9, 15, 30), user: 'Siti Aminah', email: 'siti.aminah@example.com', action: 'TAMBAH_DATA', detail: 'Janji Temu baru', status: 'Sukses', ipAddress: '10.0.0.1', type: 'activity' },
  { id: 'L006', timestamp: new Date(2023, 9, 27, 10, 0, 0), user: 'Dr. Anisa Putri', email: 'anisa.putri@example.com', action: 'UPDATE_DATA', detail: 'Catatan Pasien P001', status: 'Sukses', ipAddress: '192.168.1.60', type: 'activity' },
  { id: 'L007', timestamp: new Date(2023, 9, 28, 14, 0, 0), user: 'Admin Utama', email: 'admin@example.com', action: 'HAPUS_DATA', detail: 'Hapus akun U007', status: 'Sukses', ipAddress: '192.168.1.50', type: 'audit' },
  { id: 'L008', timestamp: new Date(2023, 9, 28, 14, 10, 0), user: 'Admin Utama', email: 'admin@example.com', action: 'HAPUS_DATA', detail: 'Hapus role Dokter', status: 'Gagal', ipAddress: '192.168.1.50', type: 'audit' },
  { id: 'L009', timestamp: new Date(2023, 9, 29, 8, 30, 0), user: 'Budi Santoso', email: 'budi.santoso@example.com', action: 'LOGIN_GAGAL', detail: 'Percobaan login', status: 'Gagal', ipAddress: '203.0.113.92', type: 'activity' },
  { id: 'L010', timestamp: new Date(2023, 9, 29, 8, 30, 30), user: 'Budi Santoso', email: 'budi.santoso@example.com', action: 'LOGIN_SUKSES', detail: 'Autentikasi', status: 'Sukses', ipAddress: '203.0.113.92', type: 'activity' },
  { id: 'L011', timestamp: new Date(2023, 9, 30, 16, 0, 0), user: 'Dr. Chandra Wijaya', email: 'chandra.wijaya@example.com', action: 'UPDATE_DATA', detail: 'Profil dokter', status: 'Sukses', ipAddress: '192.168.1.70', type: 'activity' },
  { id: 'L012', timestamp: new Date(2023, 9, 30, 16, 15, 0), user: 'Admin Utama', email: 'admin@example.com', action: 'ROLE_CHANGE', detail: 'Ubah role U004 ke Pasien', status: 'Sukses', ipAddress: '192.168.1.50', type: 'audit' },
  { id: 'L013', timestamp: new Date(2023, 10, 1, 9, 0, 0), user: 'Admin Utama', email: 'admin@example.com', action: 'LOGIN_SUKSES', detail: 'Autentikasi', status: 'Sukses', ipAddress: '192.168.1.50', type: 'activity' },
  { id: 'L014', timestamp: new Date(2023, 10, 1, 9, 5, 0), user: 'Budi Santoso', email: 'budi.santoso@example.com', action: 'LOGIN_SUKSES', detail: 'Autentikasi', status: 'Sukses', ipAddress: '203.0.113.93', type: 'activity' },
  { id: 'L015', timestamp: new Date(2023, 10, 1, 9, 5, 0), user: 'Budi Santoso', email: 'budi.santoso@example.com', action: 'UPDATE_DATA', detail: 'Profil pengguna', status: 'Sukses', ipAddress: '203.0.113.93', type: 'activity' },
];

const LOGS_PER_PAGE = 10;

const LogViewer = () => {
  const [logs, setLogs] = useState([]);
  const [searchTerm, setSearchTerm] = useState('');
  const [filterType, setFilterType] = useState('Semua'); // 'Semua', 'activity', 'audit'
  const [filterAction, setFilterAction] = useState('Semua');
  const [filterUser, setFilterUser] = useState('Semua');
  const [startDate, setStartDate] = useState(null);
  const [endDate, setEndDate] = useState(null);
  const [currentPage, setCurrentPage] = useState(1);

  useEffect(() => {
    // Di aplikasi nyata, fetch logs dari API
    setLogs(dummyLogs.sort((a, b) => b.timestamp - a.timestamp)); // Urutkan dari terbaru
  }, []);

  const filteredAndSearchedLogs = useMemo(() => {
    let tempLogs = logs;

    // Filter by type (All, Activity, Audit)
    if (filterType !== 'Semua') {
      tempLogs = tempLogs.filter(log => log.type === filterType);
    }

    // Filter by action
    if (filterAction !== 'Semua') {
      tempLogs = tempLogs.filter(log => log.action === filterAction);
    }

    // Filter by user
    if (filterUser !== 'Semua') {
        tempLogs = tempLogs.filter(log => log.user === filterUser);
    }

    // Filter by date range
    if (startDate && endDate) {
      tempLogs = tempLogs.filter(log => {
        const logDate = new Date(log.timestamp.getFullYear(), log.timestamp.getMonth(), log.timestamp.getDate());
        const start = new Date(startDate.getFullYear(), startDate.getMonth(), startDate.getDate());
        const end = new Date(endDate.getFullYear(), endDate.getMonth(), endDate.getDate());
        return logDate >= start && logDate <= end;
      });
    }


    // Search term
    if (searchTerm) {
      tempLogs = tempLogs.filter(log =>
        log.user.toLowerCase().includes(searchTerm.toLowerCase()) ||
        log.email.toLowerCase().includes(searchTerm.toLowerCase()) ||
        log.action.toLowerCase().includes(searchTerm.toLowerCase()) ||
        log.detail.toLowerCase().includes(searchTerm.toLowerCase()) ||
        log.ipAddress.toLowerCase().includes(searchTerm.toLowerCase())
      );
    }

    return tempLogs;
  }, [logs, searchTerm, filterType, filterAction, filterUser, startDate, endDate]);

  // Pagination Logic
  const totalPages = Math.ceil(filteredAndSearchedLogs.length / LOGS_PER_PAGE);
  const indexOfLastLog = currentPage * LOGS_PER_PAGE;
  const indexOfFirstLog = indexOfLastLog - LOGS_PER_PAGE;
  const currentLogs = filteredAndSearchedLogs.slice(indexOfFirstLog, indexOfLastLog);

  const handlePageChange = (pageNumber) => {
    setCurrentPage(pageNumber);
  };

  const handleExportLogs = () => {
    const headers = ["Timestamp", "User", "Email", "Action", "Detail", "Status", "IP Address", "Type"];
    const csvContent = filteredAndSearchedLogs.map(log =>
      `"${format(log.timestamp, 'yyyy-MM-dd HH:mm:ss')}",` +
      `"${log.user}",` +
      `"${log.email}",` +
      `"${log.action}",` +
      `"${log.detail.replace(/"/g, '""')}",` + // Escape double quotes in detail
      `"${log.status}",` +
      `"${log.ipAddress}",` +
      `"${log.type}"`
    ).join('\n');

    const csv = `${headers.join(',')}\n${csvContent}`;
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `log_aktivitas_${filterType.toLowerCase()}_${format(new Date(), 'yyyyMMdd_HHmmss')}.csv`;
    link.click();
    URL.revokeObjectURL(link.href);
  };


  return (
    <div className="log-viewer-container">
      <h1 className="page-title">Monitoring Log & Audit Aktivitas</h1>

      <div className="filters-section">
        <div className="filter-group">
          <label>Periode Waktu</label>
          <div className="date-range-picker">
            <DatePicker
              selected={startDate}
              onChange={(date) => setStartDate(date)}
              selectsStart
              startDate={startDate}
              endDate={endDate}
              placeholderText="Dari Tanggal"
              className="date-input"
              dateFormat="dd/MM/yyyy"
              isClearable
            />
            <DatePicker
              selected={endDate}
              onChange={(date) => setEndDate(date)}
              selectsEnd
              startDate={startDate}
              endDate={endDate}
              minDate={startDate}
              placeholderText="Sampai Tanggal"
              className="date-input"
              dateFormat="dd/MM/yyyy"
              isClearable
            />
          </div>
        </div>

        <div className="filter-group">
          <label>Filter Jenis Log</label>
          <select value={filterType} onChange={(e) => {setFilterType(e.target.value); setCurrentPage(1);}} className="filter-select">
            <option value="Semua">Semua Log</option>
            <option value="activity">Log Aktivitas Pengguna</option>
            <option value="audit">Log Audit & Keamanan</option>
          </select>
        </div>

        <div className="filter-group">
          <label>Filter Aksi</label>
          <select value={filterAction} onChange={(e) => {setFilterAction(e.target.value); setCurrentPage(1);}} className="filter-select">
            <option value="Semua">Semua Aksi</option>
            {ALL_LOG_TYPES.map(type => (
              <option key={type} value={type}>{type.replace(/_/g, ' ')}</option>
            ))}
          </select>
        </div>

        <div className="filter-group">
          <label>Filter Pengguna</label>
          <select value={filterUser} onChange={(e) => {setFilterUser(e.target.value); setCurrentPage(1);}} className="filter-select">
            {ALL_USERS_FOR_FILTER.map(user => (
              <option key={user} value={user}>{user}</option>
            ))}
          </select>
        </div>

        <div className="search-group">
          <label>Cari (Pengguna, Aksi, IP...)</label>
          <div className="search-input-wrapper">
            <IoSearchOutline className="search-icon" />
            <input
              type="text"
              placeholder="Cari log..."
              value={searchTerm}
              onChange={(e) => {setSearchTerm(e.target.value); setCurrentPage(1);}}
              className="search-input"
            />
          </div>
        </div>
      </div>

      <div className="log-table-wrapper">
        {currentLogs.length > 0 ? (
          <table className="log-table">
            <thead>
              <tr>
                <th>Timestamp</th>
                <th>Pengguna</th>
                <th>Aksi</th>
                <th>Detail</th>
                <th>Status</th>
                <th>IP Address</th>
                <th>Tipe</th>
              </tr>
            </thead>
            <tbody>
              {currentLogs.map(log => (
                <tr key={log.id}>
                  <td>{format(log.timestamp, 'yyyy-MM-dd HH:mm:ss')}</td>
                  <td>{log.user}</td>
                  <td>{log.action.replace(/_/g, ' ')}</td>
                  <td>{log.detail}</td>
                  <td>
                    <span className={`log-status ${log.status.toLowerCase()}`}>
                      {log.status}
                    </span>
                  </td>
                  <td>{log.ipAddress}</td>
                  <td>
                    <span className={`log-type ${log.type}`}>
                      {log.type === 'activity' ? 'Aktivitas' : 'Audit'}
                    </span>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        ) : (
          <p className="no-logs-message">Tidak ada log yang ditemukan dengan kriteria ini.</p>
        )}
      </div>

      <div className="table-footer">
        {filteredAndSearchedLogs.length > 0 && (
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

        <button onClick={handleExportLogs} className="btn-export">
          <IoDownloadOutline className="export-icon" /> Ekspor Data
        </button>
      </div>
    </div>
  );
};

export default LogViewer;