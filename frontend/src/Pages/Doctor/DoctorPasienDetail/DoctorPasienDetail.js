import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import './DoctorPasienDetail.css';
import { IoPersonCircleOutline, IoCalendarOutline, IoPhonePortraitOutline, IoMailOutline, IoLocationOutline, IoFlaskOutline, IoChatbubblesOutline } from 'react-icons/io5';

// Impor modal untuk menambah catatan medis
import AddMedicalRecordModal from '../../../components/AddMedicalRecordModal/AddMedicalRecordModal';

// --- DUMMY DATA ---
// Sesuaikan dengan data dari DoctorDaftarPasien
const allPatientsData = [
  { 
    id: 'P001', 
    nama: 'Budi Hartono', 
    foto: 'https://via.placeholder.com/150/007bff/ffffff?text=BH', 
    deskripsiSingkat: 'Pasien aktif, riwayat alergi obat.',
    alamat: 'Jl. Melati No. 10, Jakarta',
    tanggalLahir: '1990-05-15',
    jenisKelamin: 'Laki-laki',
    telepon: '081234567890',
    email: 'budi.h@example.com',
  },
  { 
    id: 'P002', 
    nama: 'Siti Aminah', 
    foto: 'https://via.placeholder.com/150/28a745/ffffff?text=SA', 
    deskripsiSingkat: 'Memiliki riwayat diabetes tipe 2.',
    alamat: 'Jl. Kenanga No. 5, Bandung',
    tanggalLahir: '1978-11-20',
    jenisKelamin: 'Perempuan',
    telepon: '085678901234',
    email: 'siti.a@example.com',
  },
];

// Dummy Rekam Medis (misal: hasil lab, rontgen)
const dummyRekamMedis = [
  { id: 1, pasienId: 'P001', judul: 'Hasil Tes Darah Lengkap', tanggal: '2025-09-20', fileUrl: 'link_ke_file_darah.pdf', type: 'Lab' },
  { id: 2, pasienId: 'P001', judul: 'Foto Rontgen Dada', tanggal: '2025-08-10', fileUrl: 'link_ke_file_rontgen.jpg', type: 'Radiologi' },
  { id: 3, pasienId: 'P002', judul: 'Pemeriksaan Gula Darah', tanggal: '2025-10-01', fileUrl: 'link_ke_file_guladarah.pdf', type: 'Lab' },
];

// Dummy Catatan Medis (disimpan di state agar bisa ditambahkan)
const initialDummyCatatanMedis = [
  { 
    id: 1, pasienId: 'P001', dokter: 'Dr. Budi Santoso', tanggal: '2025-10-23', 
    diagnosis: 'Pasien datang dengan keluhan batuk berdahak selama 5 hari, disertai demam ringan. Pemeriksaan fisik menunjukkan adanya sedikit ronki di paru-paru bagian bawah.',
    resepObat: 'Amoxicillin 500mg (2x sehari), Paracetamol 500mg (3x sehari jika demam).'
  },
  { 
    id: 2, pasienId: 'P001', dokter: 'Dr. Budi Santoso', tanggal: '2025-09-19', 
    diagnosis: 'Kontrol batuk, sudah membaik. Dada bersih. Resep obat dilanjutkan sampai habis.',
    resepObat: 'Amoxicillin 500mg (2x sehari).'
  },
  { 
    id: 3, pasienId: 'P002', dokter: 'Dr. Budi Santoso', tanggal: '2025-10-05', 
    diagnosis: 'Pasien mengeluh pusing dan lemas. Riwayat diabetes. Cek gula darah sewaktu: 300 mg/dL. Edukasi diet dan pentingnya kepatuhan obat.',
    resepObat: 'Metformin 500mg (2x sehari), resep insulin jika diperlukan (sesuai dosis).'
  },
];


const DoctorPasienDetail = () => {
  const { pasienId } = useParams(); // Ambil ID pasien dari URL
  const navigate = useNavigate();
  const [patient, setPatient] = useState(null);
  const [patientMedicalRecords, setPatientMedicalRecords] = useState([]);
  const [patientMedicalNotes, setPatientMedicalNotes] = useState([]); // State untuk catatan medis

  const [isAddNoteModalOpen, setIsAddNoteModalOpen] = useState(false); // State untuk modal tambah catatan

  useEffect(() => {
    // --- Ambil Data Pasien ---
    // Di aplikasi nyata, ini akan menjadi panggilan API: /api/pasien/${pasienId}
    const foundPatient = allPatientsData.find(p => p.id === pasienId);
    if (foundPatient) {
      setPatient(foundPatient);
      // --- Ambil Rekam Medis Pasien ---
      // Di aplikasi nyata: /api/rekam-medis?pasienId=${pasienId}
      const filteredRecords = dummyRekamMedis.filter(rec => rec.pasienId === pasienId);
      setPatientMedicalRecords(filteredRecords);
      // --- Ambil Catatan Medis Pasien ---
      // Di aplikasi nyata: /api/catatan-medis?pasienId=${pasienId}&dokterId=${CURRENT_DOCTOR_ID}
      // Kita asumsikan semua catatan medis di initialDummyCatatanMedis adalah dari dokter yang login
      const filteredNotes = initialDummyCatatanMedis.filter(note => note.pasienId === pasienId);
      setPatientMedicalNotes(filteredNotes);

    } else {
      // Jika pasien tidak ditemukan, arahkan kembali ke daftar pasien
      navigate('/dokter/pasien');
      alert('Pasien tidak ditemukan.');
    }
  }, [pasienId, navigate]); // Dependensi pasienId dan navigate

  if (!patient) {
    return <div className="loading-message">Memuat data pasien...</div>;
  }

  // --- Fungsi untuk Tambah Catatan Medis ---
  const handleOpenAddNoteModal = () => {
    setIsAddNoteModalOpen(true);
  };

  const handleCloseAddNoteModal = () => {
    setIsAddNoteModalOpen(false);
  };

  const handleSaveNewNote = ({ diagnosa, resepObat }) => {
    const newNote = {
      id: patientMedicalNotes.length + 1, // ID dummy
      pasienId: patient.id,
      dokter: 'Dr. Budi Santoso', // Sesuaikan dengan dokter yang login
      tanggal: new Date().toISOString().slice(0, 10), // Tanggal hari ini
      diagnosis: diagnosa,
      resepObat: resepObat,
    };
    setPatientMedicalNotes(prevNotes => [...prevNotes, newNote]);
    alert('Catatan medis berhasil ditambahkan!');
    // Di aplikasi nyata: Kirim newNote ke API POST /api/catatan-medis
  };


  return (
    <>
      <div className="doctor-pasien-detail-container">
        <h1 className="page-title">Profil Pasien: {patient.nama}</h1>
        
        {/* Bagian Profil Pasien */}
        <div className="patient-profile-section">
          <div className="profile-header">
            <img src={patient.foto} alt={patient.nama} className="profile-photo-detail" />
            <div className="profile-main-info">
              <h2>{patient.nama}</h2>
              <p className="profile-short-desc">{patient.deskripsiSingkat}</p>
            </div>
          </div>
          <div className="profile-details-grid">
            <div className="detail-item">
              <IoCalendarOutline size={20} />
              <span>Tanggal Lahir: {patient.tanggalLahir}</span>
            </div>
            <div className="detail-item">
              <IoPersonCircleOutline size={20} />
              <span>Jenis Kelamin: {patient.jenisKelamin}</span>
            </div>
            <div className="detail-item">
              <IoPhonePortraitOutline size={20} />
              <span>Telepon: {patient.telepon}</span>
            </div>
            <div className="detail-item">
              <IoMailOutline size={20} />
              <span>Email: {patient.email}</span>
            </div>
            <div className="detail-item full-width">
              <IoLocationOutline size={20} />
              <span>Alamat: {patient.alamat}</span>
            </div>
          </div>
        </div>

        {/* Bagian Rekam Medis (Hasil Lab, Rontgen, dll.) */}
        <div className="section-header">
          <IoFlaskOutline size={25} />
          <h2 className="section-title">Rekam Medis Digital</h2>
        </div>
        <div className="rekam-medis-grid">
          {patientMedicalRecords.length > 0 ? (
            patientMedicalRecords.map(record => (
              <div key={record.id} className="rekam-medis-card">
                <h3>{record.judul}</h3>
                <p>Tanggal: {record.tanggal}</p>
                <p>Tipe: {record.type}</p>
                {/* Di sini Anda bisa menambahkan tombol untuk melihat/mengunduh file */}
                <a href={record.fileUrl} target="_blank" rel="noopener noreferrer" className="btn-view-record">
                  Lihat Rekam Medis
                </a>
              </div>
            ))
          ) : (
            <p className="no-records-message">Tidak ada rekam medis digital untuk pasien ini.</p>
          )}
        </div>

        {/* Bagian Catatan Medis Dokter */}
        <div className="section-header">
          <IoChatbubblesOutline size={25} />
          <h2 className="section-title">Catatan Medis Anda untuk Pasien Ini</h2>
        </div>
        <div className="catatan-medis-grid">
          {patientMedicalNotes.length > 0 ? (
            patientMedicalNotes.map(note => (
              <div key={note.id} className="catatan-card-detail"> {/* Gunakan gaya card berbeda jika perlu */}
                <p className="note-date"><strong>Tanggal:</strong> {note.tanggal}</p>
                <p><strong>Dokter:</strong> {note.dokter}</p>
                <p><strong>Diagnosa:</strong> {note.diagnosis}</p>
                {note.resepObat && <p><strong>Resep Obat:</strong> {note.resepObat}</p>}
              </div>
            ))
          ) : (
            <p className="no-records-message">Belum ada catatan medis dari Anda untuk pasien ini.</p>
          )}
        </div>

        {/* Tombol Tambah Catatan Medis */}
        <div className="add-note-action">
          <button className="btn-primary" onClick={handleOpenAddNoteModal}>
            Tambahkan Catatan Medis Baru
          </button>
        </div>
      </div>

      {/* Modal Tambah Catatan Medis */}
      <AddMedicalRecordModal
        show={isAddNoteModalOpen}
        onClose={handleCloseAddNoteModal}
        onSave={handleSaveNewNote}
      />
    </>
  );
};

export default DoctorPasienDetail;