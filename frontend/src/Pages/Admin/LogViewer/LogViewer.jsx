import React, { useState, useEffect, useMemo } from 'react';
// Hapus import './LogViewer.css';
import { IoSearchOutline, IoDownloadOutline, IoFilterOutline } from 'react-icons/io5';
import DatePicker from 'react-datepicker'; // Untuk pemilih tanggal
import 'react-datepicker/dist/react-datepicker.css'; // Gaya default datepicker (tetap dipertahankan)
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
    <div className="p-6 bg-gray-100 min-h-screen">
      <div className="bg-white rounded-xl shadow-lg p-6 lg:p-8">
        <h1 className="text-3xl font-semibold mb-8 text-gray-800 text-center">Monitoring Log & Audit Aktivitas</h1>

        <div className="flex flex-wrap gap-x-6 gap-y-4 mb-8 p-6 bg-gray-50 rounded-lg shadow-inner justify-between items-end">
          <div className="flex flex-col flex-1 min-w-[200px] sm:min-w-[220px]">
            <label htmlFor="date-start" className="block mb-2 font-medium text-gray-600 text-sm">Periode Waktu</label>
            <div className="flex flex-wrap gap-2">
              <DatePicker
                selected={startDate}
                onChange={(date) => {setStartDate(date); setCurrentPage(1);}}
                selectsStart
                startDate={startDate}
                endDate={endDate}
                placeholderText="Dari Tanggal"
                className="w-full p-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200"
                dateFormat="dd/MM/yyyy"
                isClearable
                id="date-start"
              />
              <DatePicker
                selected={endDate}
                onChange={(date) => {setEndDate(date); setCurrentPage(1);}}
                selectsEnd
                startDate={startDate}
                endDate={endDate}
                minDate={startDate}
                placeholderText="Sampai Tanggal"
                className="w-full p-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200"
                dateFormat="dd/MM/yyyy"
                isClearable
              />
            </div>
          </div>

          <div className="flex flex-col flex-1 min-w-[150px]">
            <label htmlFor="filter-type" className="block mb-2 font-medium text-gray-600 text-sm">Filter Jenis Log</label>
            <select
              id="filter-type"
              value={filterType}
              onChange={(e) => {setFilterType(e.target.value); setCurrentPage(1);}}
              className="w-full p-2 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200"
            >
              <option value="Semua">Semua Log</option>
              <option value="activity">Log Aktivitas Pengguna</option>
              <option value="audit">Log Audit & Keamanan</option>
            </select>
          </div>

          <div className="flex flex-col flex-1 min-w-[150px]">
            <label htmlFor="filter-action" className="block mb-2 font-medium text-gray-600 text-sm">Filter Aksi</label>
            <select
              id="filter-action"
              value={filterAction}
              onChange={(e) => {setFilterAction(e.target.value); setCurrentPage(1);}}
              className="w-full p-2 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200"
            >
              <option value="Semua">Semua Aksi</option>
              {ALL_LOG_TYPES.map(type => (
                <option key={type} value={type}>{type.replace(/_/g, ' ')}</option>
              ))}
            </select>
          </div>

          <div className="flex flex-col flex-1 min-w-[150px]">
            <label htmlFor="filter-user" className="block mb-2 font-medium text-gray-600 text-sm">Filter Pengguna</label>
            <select
              id="filter-user"
              value={filterUser}
              onChange={(e) => {setFilterUser(e.target.value); setCurrentPage(1);}}
              className="w-full p-2 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200"
            >
              {ALL_USERS_FOR_FILTER.map(user => (
                <option key={user} value={user}>{user}</option>
              ))}
            </select>
          </div>

          <div className="flex flex-col flex-2 min-w-[250px] sm:min-w-[300px]">
            <label htmlFor="search-log" className="block mb-2 font-medium text-gray-600 text-sm">Cari (Pengguna, Aksi, IP...)</label>
            <div className="flex items-center border border-gray-300 rounded-lg px-3 bg-white focus-within:ring-2 focus-within:ring-emerald-500 focus-within:border-emerald-500 transition-all duration-200">
              <IoSearchOutline className="text-gray-500 mr-2" />
              <input
                id="search-log"
                type="text"
                placeholder="Cari log..."
                value={searchTerm}
                onChange={(e) => {setSearchTerm(e.target.value); setCurrentPage(1);}}
                className="flex-grow p-2 border-none outline-none text-sm placeholder-gray-400"
              />
            </div>
          </div>
        </div>

        <div className="max-h-[600px] overflow-y-auto border border-gray-200 rounded-xl bg-white shadow-md mb-8">
          {currentLogs.length > 0 ? (
            <table className="w-full border-collapse">
              <thead className="sticky top-0 bg-gray-50 z-10">
                <tr>
                  <th className="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Timestamp</th>
                  <th className="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Pengguna</th>
                  <th className="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Aksi</th>
                  <th className="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Detail</th>
                  <th className="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Status</th>
                  <th className="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">IP Address</th>
                  <th className="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Tipe</th>
                </tr>
              </thead>
              <tbody>
                {currentLogs.map(log => (
                  <tr key={log.id} className="hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                    <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">{format(log.timestamp, 'yyyy-MM-dd HH:mm:ss')}</td>
                    <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">{log.user}</td>
                    <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">{log.action.replace(/_/g, ' ')}</td>
                    <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">{log.detail}</td>
                    <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">
                      <span className={`px-2 py-1 rounded-md text-xs font-semibold text-white
                                      ${log.status.toLowerCase() === 'sukses' ? 'bg-green-600' : 'bg-red-600'}`}>
                        {log.status}
                      </span>
                    </td>
                    <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">{log.ipAddress}</td>
                    <td className="py-3 px-4 text-sm text-gray-800 border-b border-gray-100">
                      <span className={`px-2 py-1 rounded-md text-xs font-medium
                                      ${log.type === 'activity' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'}`}>
                        {log.type === 'activity' ? 'Aktivitas' : 'Audit'}
                      </span>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          ) : (
            <p className="text-center p-8 text-gray-600 italic text-base">Tidak ada log yang ditemukan dengan kriteria ini.</p>
          )}
        </div>

        <div className="flex flex-col sm:flex-row justify-between items-center mt-6">
          {filteredAndSearchedLogs.length > 0 && (
            <div className="flex justify-center items-center mb-4 sm:mb-0">
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

          <button onClick={handleExportLogs} className="flex items-center gap-2 bg-blue-600 text-white
                                                    py-2.5 px-6 rounded-lg font-medium text-base
                                                    hover:bg-blue-700 transition-all duration-300 ease-in-out">
            <IoDownloadOutline className="text-xl" /> Ekspor Data
          </button>
        </div>
      </div>
    </div>
  );
};

export default LogViewer;