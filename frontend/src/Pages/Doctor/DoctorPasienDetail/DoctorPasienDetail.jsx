import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';

import { IoPersonCircleOutline, IoCalendarOutline, IoPhonePortraitOutline, IoMailOutline, IoLocationOutline, IoFlaskOutline, IoChatbubblesOutline } from 'react-icons/io5';
import { patientService } from '../../../services/patientService';
import { doctorService } from '../../../services/doctorService';
import { authService } from '../../../services/authService';


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
    return <div className="p-8 text-center text-gray-600 text-lg">Memuat data pasien...</div>;
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
      <div className="p-8 bg-gray-50 min-h-screen">
        <h1 className="text-3xl font-semibold mb-8 text-gray-800">Profil Pasien: {patient.nama}</h1>

        {/* Bagian Profil Pasien */}
        <div className="bg-white rounded-xl shadow-lg p-6 md:p-8 mb-8">
          <div className="flex items-center border-b border-gray-200 pb-6 mb-6">
            <img
              src={patient.foto}
              alt={patient.nama}
              className="w-24 h-24 rounded-full object-cover mr-6 border-4 border-emerald-600 shadow-md"
            />
            <div>
              <h2 className="text-3xl font-bold text-gray-800 m-0">{patient.nama}</h2>
              <p className="mt-1 text-gray-600 italic text-lg">{patient.deskripsiSingkat}</p>
            </div>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div className="flex items-center bg-gray-50 p-3 rounded-lg text-gray-700 text-base">
              <IoCalendarOutline size={20} className="mr-3 text-emerald-600" />
              <span>Tanggal Lahir: {patient.tanggalLahir}</span>
            </div>
            <div className="flex items-center bg-gray-50 p-3 rounded-lg text-gray-700 text-base">
              <IoPersonCircleOutline size={20} className="mr-3 text-emerald-600" />
              <span>Jenis Kelamin: {patient.jenisKelamin}</span>
            </div>
            <div className="flex items-center bg-gray-50 p-3 rounded-lg text-gray-700 text-base">
              <IoPhonePortraitOutline size={20} className="mr-3 text-emerald-600" />
              <span>Telepon: {patient.telepon}</span>
            </div>
            <div className="flex items-center bg-gray-50 p-3 rounded-lg text-gray-700 text-base">
              <IoMailOutline size={20} className="mr-3 text-emerald-600" />
              <span>Email: {patient.email}</span>
            </div>
            <div className="flex items-center bg-gray-50 p-3 rounded-lg text-gray-700 text-base md:col-span-2">
              <IoLocationOutline size={20} className="mr-3 text-emerald-600" />
              <span>Alamat: {patient.alamat}</span>
            </div>
          </div>
        </div>

        {/* Bagian Rekam Medis Digital (Hasil Lab, Rontgen, dll.) */}
        <div className="flex items-center mt-12 mb-6 text-emerald-600">
          <IoFlaskOutline size={25} className="mr-3" />
          <h2 className="text-2xl font-semibold text-gray-800 m-0">Rekam Medis Digital</h2>
        </div>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-12">
          {patientMedicalRecords.length > 0 ? (
            patientMedicalRecords.map(record => (
              <div key={record.id} className="bg-white rounded-xl shadow-md p-5 flex flex-col justify-between
                                           hover:transform hover:translate-y-[-3px] hover:shadow-lg transition duration-200 ease-in-out">
                <div>
                  <h3 className="text-xl font-semibold mb-2 text-gray-800">{record.judul}</h3>
                  <p className="text-gray-600 text-sm mb-1">Tanggal: {record.tanggal}</p>
                  <p className="text-gray-600 text-sm">Tipe: {record.type}</p>
                </div>
                <a
                  href={record.fileUrl}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="mt-4 block bg-emerald-600 text-white py-2 px-4 rounded-lg text-center text-sm font-medium
                             hover:bg-emerald-700 transition duration-200 ease-in-out"
                >
                  Lihat Rekam Medis
                </a>
              </div>
            ))
          ) : (
            <p className="col-span-full text-center text-gray-600 italic p-5 bg-gray-100 rounded-lg mb-8">
              Tidak ada rekam medis digital untuk pasien ini.
            </p>
          )}
        </div>

        {/* Bagian Catatan Medis Dokter */}
        <div className="flex items-center mt-12 mb-6 text-emerald-600">
          <IoChatbubblesOutline size={25} className="mr-3" />
          <h2 className="text-2xl font-semibold text-gray-800 m-0">Catatan Medis Anda untuk Pasien Ini</h2>
        </div>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-12">
          {patientMedicalNotes.length > 0 ? (
            patientMedicalNotes.map(note => (
              <div key={note.id} className="bg-gray-50 border border-gray-200 rounded-xl shadow-sm p-5">
                <p className="text-sm text-gray-500 mb-4 pb-2 border-b border-dashed border-gray-200">
                  <strong className="text-gray-600">Tanggal:</strong> {note.tanggal}
                </p>
                <p className="text-gray-700 text-base mb-2">
                  <strong className="text-emerald-600">Dokter:</strong> {note.dokter}
                </p>
                <p className="text-gray-700 text-base mb-2">
                  <strong className="text-emerald-600">Diagnosa:</strong> {note.diagnosis}
                </p>
                {note.resepObat && (
                  <p className="text-gray-700 text-base">
                    <strong className="text-emerald-600">Resep Obat:</strong> {note.resepObat}
                  </p>
                )}
              </div>
            ))
          ) : (
            <p className="col-span-full text-center text-gray-600 italic p-5 bg-gray-100 rounded-lg mb-8">
              Belum ada catatan medis dari Anda untuk pasien ini.
            </p>
          )}
        </div>

        {/* Tombol Tambah Catatan Medis */}
        <div className="text-center mt-12 mb-8">
          <button
            className="px-8 py-4 bg-blue-600 text-white rounded-lg font-semibold text-lg
                       hover:bg-blue-700 transition duration-300 ease-in-out"
            onClick={handleOpenAddNoteModal}
          >
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