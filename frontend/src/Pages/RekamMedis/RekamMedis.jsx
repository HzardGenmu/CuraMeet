import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { authService } from "../../services/authService";
import { patientService } from "../../services/patientService";
import { medicalRecordService } from "../../services/medicalRecordService";
import { doctorService } from "../../services/doctorService";

import {
  IoCloudUpload,
  IoDocument,
  IoCalendar,
  IoPerson,
  IoImage,
} from "react-icons/io5";

const RekamMedis = () => {
  const [records, setRecords] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [success, setSuccess] = useState("");

  // State untuk form upload
  const [selectedFile, setSelectedFile] = useState(null);
  const [uploadLoading, setUploadLoading] = useState(false);
  const [diseaseName, setDiseaseName] = useState("");
  const [doctorNote, setDoctorNote] = useState("");

  // State yang dibutuhkan untuk logika yang benar
  const [patientId, setPatientId] = useState(null);
  const [doctors, setDoctors] = useState([]);
  const [selectedDoctorId, setSelectedDoctorId] = useState("");

  const navigate = useNavigate();

  useEffect(() => {
    const fetchInitialData = async () => {
      if (!authService.isAuthenticated()) {
        navigate("/login");
        return;
      }
      try {
        setLoading(true);
        const profileRes = await patientService.getProfile();
        if (profileRes.success && profileRes.patient) {
          const currentPatientId = profileRes.patient.id;
          setPatientId(currentPatientId);
          const recordsRes = await medicalRecordService.getForPatient(currentPatientId);
          if (recordsRes.success) {
            setRecords(recordsRes.records || []);
          }
          const doctorsRes = await doctorService.getAll();
          // Axios membungkus respons dalam properti 'data'
          if (doctorsRes.data && doctorsRes.data.success) {
            setDoctors(doctorsRes.data.doctors || []);
          }
        } else {
          setError(profileRes.message || "Gagal memuat data profil Anda.");
        }
      } catch (err) {
        console.error("Error loading initial data:", err);
        setError(err.response?.data?.message || "Gagal memuat data halaman.");
      } finally {
        setLoading(false);
      }
    };
    fetchInitialData();
  }, [navigate]);

  const handleFileUpload = async (e) => {
    e.preventDefault();
    setError("");
    setSuccess("");

    if (!selectedFile) return setError("Pilih file terlebih dahulu.");
    if (!selectedDoctorId) return setError("Pilih dokter terlebih dahulu.");
    if (!patientId) return setError("ID Pasien tidak ditemukan. Harap muat ulang halaman.");
    if (!diseaseName) return setError("Nama penyakit wajib diisi.");

    // Validasi tipe file
    const allowedTypes = ["application/pdf", "image/jpeg", "image/png"];
    if (!allowedTypes.includes(selectedFile.type)) {
      return setError("Hanya file PDF, JPG, dan PNG yang diizinkan");
    }

    // Validasi ukuran file (misal, 2MB)
    if (selectedFile.size > 2 * 1024 * 1024) {
      return setError("Ukuran file maksimal 2MB");
    }

    try {
      setUploadLoading(true);

      const formData = new FormData();
      formData.append("patient_id", patientId);
      formData.append("doctor_id", selectedDoctorId);
      formData.append("file", selectedFile);
      formData.append("disease_name", diseaseName);
      formData.append("doctor_note", doctorNote);

      const response = await medicalRecordService.upload(formData);

      if (response.success) {
        setSuccess("File rekam medis berhasil diunggah!");
        // Reset semua state form
        setSelectedFile(null);
        setSelectedDoctorId("");
        setDiseaseName("");
        setDoctorNote("");
        if (document.getElementById("fileInput")) {
          document.getElementById("fileInput").value = "";
        }
        
        // Muat ulang daftar rekam medis
        const newRecords = await medicalRecordService.getForPatient(patientId);
        if (newRecords.success) setRecords(newRecords.records || []);
      } else {
        setError(response.message || "Gagal mengunggah file");
      }
    } catch (error) {
      console.error("Error uploading file:", error);
      setError(error.response?.data?.message || "Terjadi kesalahan saat mengunggah file");
    } finally {
      setUploadLoading(false);
    }
  };

  const formatDate = (dateString) => {
    try {
      return new Date(dateString).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
      });
    } catch (error) {
      return "Invalid Date";
    }
  };

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gray-100 p-4">
        <div className="flex flex-col items-center space-y-4">
          <div className="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
          <p className="text-lg text-gray-700">Memuat rekam medis...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-100 p-6 sm:p-8 flex flex-col items-center">
       <div className="w-full max-w-6xl mb-8">
        <h1 className="text-4xl md:text-5xl font-bold text-gray-800 text-center mb-6">
          <span className="inline-block align-middle mr-2">📋</span> Rekam Medis Saya
        </h1>
      </div>

      <div className="w-full max-w-3xl mb-12">
        <div className="border-2 border-dashed border-gray-300 rounded-2xl p-6 sm:p-8 bg-white text-center transition duration-300 ease-in-out hover:border-emerald-600 hover:shadow-md">
          <h3 className="text-2xl font-semibold text-gray-800 mb-4 flex items-center justify-center">
            <IoCloudUpload className="mr-3 text-3xl text-blue-500" /> Upload Rekam Medis Baru
          </h3>

          {error && <div className="bg-red-100 text-red-700 px-4 py-2 rounded-lg mb-4 text-sm flex items-center justify-center space-x-2"><span>❌</span> <span>{error}</span></div>}
          {success && <div className="bg-green-100 text-green-700 px-4 py-2 rounded-lg mb-4 text-sm flex items-center justify-center space-x-2"><span>✅</span> <span>{success}</span></div>}

          <form onSubmit={handleFileUpload} className="space-y-4">
            <select
              value={selectedDoctorId}
              onChange={(e) => setSelectedDoctorId(e.target.value)}
              disabled={uploadLoading}
              required
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base"
            >
              <option value="" disabled>Pilih Dokter Tujuan</option>
              {doctors.map((doctor) => (
                <option key={doctor.id} value={doctor.id}>
                  {doctor.full_name} - {doctor.specialist}
                </option>
              ))}
            </select>

            <input
              type="text"
              value={diseaseName}
              onChange={(e) => setDiseaseName(e.target.value)}
              placeholder="Nama Penyakit / Diagnosa *"
              required
              disabled={uploadLoading}
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base"
            />
            
            <textarea
              rows="3"
              value={doctorNote}
              onChange={(e) => setDoctorNote(e.target.value)}
              placeholder="Catatan Dokter (opsional)..."
              disabled={uploadLoading}
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base resize-y"
            />

            <div className="relative border border-gray-300 rounded-lg overflow-hidden cursor-pointer">
              <input
                id="fileInput"
                type="file"
                accept=".pdf,.jpg,.jpeg,.png"
                onChange={(e) => setSelectedFile(e.target.files[0])}
                className="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                disabled={uploadLoading}
              />
              <label htmlFor="fileInput" className="block w-full py-3 px-4 text-gray-700 bg-gray-50 text-center cursor-pointer hover:bg-gray-100 transition duration-200 ease-in-out text-base font-medium">
                {selectedFile ? selectedFile.name : "Pilih file (PDF, JPG, PNG) *"}
              </label>
            </div>

            <button
              type="submit"
              className="w-full py-3 px-6 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center text-lg"
              disabled={!selectedFile || !selectedDoctorId || !diseaseName || uploadLoading}
            >
              {uploadLoading ? (
                <>
                  <div className="w-5 h-5 border-2 border-blue-200 border-t-blue-600 rounded-full animate-spin mr-3"></div>
                  Mengunggah...
                </>
              ) : (
                <>
                  <IoCloudUpload className="mr-3 text-xl" /> Upload File
                </>
              )}
            </button>
          </form>

          <div className="text-gray-500 text-sm mt-4">
            <p>• Maksimal ukuran file: 2MB</p>
            <p>• Format yang didukung: PDF, JPG, PNG</p>
          </div>
        </div>
      </div>

      <div className="w-full max-w-6xl">
        <h3 className="text-2xl font-semibold text-gray-800 mb-6 text-center">
          <span className="inline-block align-middle mr-2">📁</span> Daftar Rekam Medis
        </h3>

        {records.length === 0 ? (
          <div className="flex flex-col items-center justify-center p-8 bg-white rounded-2xl shadow-lg text-gray-500">
            <IoDocument size={80} className="mb-4 text-gray-400" />
            <h4 className="text-xl font-semibold mb-2">Belum Ada Rekam Medis</h4>
            <p className="text-base text-center">
              Unggah file rekam medis pertama Anda di atas
            </p>
          </div>
        ) : (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {records.map((record) => (
              <div key={record.id} className="bg-white rounded-2xl shadow-lg flex flex-col cursor-pointer transition transform duration-200 ease-in-out hover:scale-105 hover:shadow-xl overflow-hidden">
                <div className="relative w-full h-48 bg-gray-100 flex items-center justify-center p-4">
                  {record.path_file &&
                  record.path_file.match(/\.(jpeg|jpg|png)$/i) ? (
                    <img
                      src={`http://localhost:8000/storage/${record.path_file}`}
                      alt="Rekam Medis"
                      className="object-cover w-full h-full"
                    />
                  ) : (
                    <div className="text-5xl text-red-500">
                      <IoDocument />
                    </div>
                  )}
                </div>

                <div className="p-4 flex-grow">
                  <h4 className="text-xl font-semibold text-gray-800 mb-2">
                    {record.disease_name || "Rekam Medis"}
                  </h4>
                  <div className="text-gray-600 text-sm space-y-1">
                    <div className="flex items-center">
                      <IoCalendar className="mr-2 text-gray-500" />
                      <span>{formatDate(record.created_at)}</span>
                    </div>
                    <div className="flex items-center">
                      <IoPerson className="mr-2 text-gray-500" />
                      <span>Oleh: {record.doctor_name || "N/A"}</span>
                    </div>
                  </div>

                  {record.catatan_dokter && (
                    <div className="mt-4 text-gray-700 text-sm border-t border-gray-200 pt-3">
                      <strong className="block mb-1">Catatan Dokter:</strong>
                      <p className="line-clamp-3">{record.catatan_dokter}</p>
                    </div>
                  )}
                </div>

                <div className="p-4 border-t border-gray-200 mt-auto">
                  {record.path_file && (
                    <a
                      href={`http://localhost:8000/storage/${record.path_file}`}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="w-full inline-flex items-center justify-center py-2 px-4 border border-blue-500 text-blue-600 font-medium rounded-lg hover:bg-blue-50 transition duration-200 ease-in-out text-base"
                    >
                      <IoDocument className="mr-2 text-xl" /> Lihat File
                    </a>
                  )}
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
};

export default RekamMedis;

