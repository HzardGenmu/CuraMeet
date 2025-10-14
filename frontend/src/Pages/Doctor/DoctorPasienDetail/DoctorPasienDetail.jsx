import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import './DoctorPasienDetail.css';
import { IoPersonCircleOutline, IoCalendarOutline, IoPhonePortraitOutline, IoMailOutline, IoLocationOutline, IoFlaskOutline, IoChatbubblesOutline } from 'react-icons/io5';
import { patientService } from '../../../services/patientService';
import { doctorService } from '../../../services/doctorService';
import { authService } from '../../../services/authService';

// Impor modal untuk menambah catatan medis
import AddMedicalRecordModal from '../../../components/AddMedicalRecordModal/AddMedicalRecordModal';




const DoctorPasienDetail = () => {
  const { pasienId } = useParams();
  const navigate = useNavigate();
  const [patient, setPatient] = useState(null);
  const [patientMedicalRecords, setPatientMedicalRecords] = useState([]);
  const [patientMedicalNotes, setPatientMedicalNotes] = useState([]);
  const [isAddNoteModalOpen, setIsAddNoteModalOpen] = useState(false);

  // Ambil ID dokter dari authService
  const CURRENT_DOCTOR_ID = authService.getCurrentUser()?.id || '';

  useEffect(() => {
    const fetchData = async () => {
      try {
        // Ambil data pasien
        const patientData = await patientService.getPatientById(pasienId);
        if (!patientData || !patientData.success) {
          navigate('/dokter/pasien');
          alert('Pasien tidak ditemukan.');
          return;
        }
        setPatient({
          id: patientData.patient?.id || pasienId,
          nama: patientData.patient?.nama || '-',
          foto: patientData.patient?.foto || 'https://via.placeholder.com/150/007bff/ffffff?text=PS',
          deskripsiSingkat: patientData.patient?.deskripsi_singkat || '',
          alamat: patientData.patient?.alamat || '',
          tanggalLahir: patientData.patient?.tanggal_lahir || '',
          jenisKelamin: patientData.patient?.jenis_kelamin || '',
          telepon: patientData.patient?.telepon || '',
          email: patientData.patient?.email || '',
        });

        // Ambil rekam medis digital (hasil lab, rontgen, dll)
        const recordsRes = await patientService.getMedicalRecords(pasienId);
        setPatientMedicalRecords(Array.isArray(recordsRes.records) ? recordsRes.records : []);

        // Ambil catatan medis dokter untuk pasien ini
        const notesRes = await doctorService.getMedicalRecords(CURRENT_DOCTOR_ID, pasienId);
        setPatientMedicalNotes(Array.isArray(notesRes.records) ? notesRes.records : []);
      } catch (err) {
        console.error('Gagal memuat data pasien/detail:', err);
        navigate('/dokter/pasien');
        alert('Gagal memuat data pasien.');
      }
    };
    fetchData();
  }, [pasienId, navigate, CURRENT_DOCTOR_ID]);

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

  const handleSaveNewNote = async ({ diagnosa, resepObat }) => {
    if (!patient) return;
    try {
      const newNote = {
        patient_id: patient.id,
        doctor_id: CURRENT_DOCTOR_ID,
        diagnosis: diagnosa,
        resep_obat: resepObat,
        tanggal: new Date().toISOString().slice(0, 10),
      };
      const res = await doctorService.addMedicalRecord(newNote);
      if (res.success) {
        // Refresh catatan medis setelah tambah
        const notesRes = await doctorService.getMedicalRecords(CURRENT_DOCTOR_ID, patient.id);
        setPatientMedicalNotes(Array.isArray(notesRes.records) ? notesRes.records : []);
        alert('Catatan medis berhasil ditambahkan!');
      } else {
        alert('Gagal menambah catatan medis.');
      }
    } catch (err) {
      alert('Gagal menambah catatan medis.');
    }
    setIsAddNoteModalOpen(false);
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