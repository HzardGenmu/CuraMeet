import React, { useState, useEffect } from "react";
import { useParams, useNavigate } from "react-router-dom";
import {
  IoPersonCircleOutline,
  IoCalendarOutline,
  IoPhonePortraitOutline,
  IoMailOutline,
  IoLocationOutline,
  IoFlaskOutline,
  IoChatbubblesOutline,
  IoArrowBackOutline,
  IoAlertCircleOutline,
  IoDocument,
} from "react-icons/io5";

// Import services (sesuaikan path Anda)
import { patientService } from "../../../services/patientService";
import { medicalRecordService } from "../../../services/medicalRecordService";
import { doctorService } from "../../../services/doctorService";
import { authService } from "../../../services/authService";
import AddMedicalRecordModal from "../../../components/AddMedicalRecordModal/AddMedicalRecordModal";

// Tambahkan fungsi sanitasi sederhana
function sanitize(str) {
  if (typeof str !== "string") return "";
  return str.replace(
    /[<>&"'`]/g,
    (c) =>
      ({
        "<": "&lt;",
        ">": "&gt;",
        "&": "&amp;",
        '"': "&quot;",
        "'": "&#39;",
        "`": "&#96;",
      }[c])
  );
}
const DoctorPasienDetail = () => {
  const { pasienId } = useParams();
  const navigate = useNavigate();

  const [patient, setPatient] = useState(null);
  const [medicalRecords, setMedicalRecords] = useState([]);
  const [doctorNotes, setDoctorNotes] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [isAddNoteModalOpen, setIsAddNoteModalOpen] = useState(false);
  const [currentDoctorId, setCurrentDoctorId] = useState(null); // Ganti const jadi state
  const [currentDoctor, setCurrentDoctor] = useState(null); // Ganti const jadi state

  useEffect(() => {
    fetchDoctorProfile();
  }, []);

  useEffect(() => {
    if (currentDoctorId) {
      fetchPatientData();
    }
  }, [pasienId, currentDoctorId]);

  const fetchDoctorProfile = async () => {
    try {
      const res = await doctorService.getProfile();
      if (res.data.success && res.data.doctor) {
        setCurrentDoctorId(res.data.doctor.id);
        setCurrentDoctor(res.data.doctor);
      } else {
        throw new Error("Gagal memuat profil dokter");
      }
    } catch (err) {
      console.error("Failed to fetch doctor profile:", err);
      setError("Gagal memuat profil dokter.");
      setLoading(false);
    }
  };

  const fetchPatientData = async () => {
    setLoading(true);
    setError(null);

    try {
      // 1. Fetch patient data using correct service method: patientService.getById()
      const patientResponse = await patientService.getById(pasienId);

      if (!patientResponse || !patientResponse.success) {
        throw new Error("Pasien tidak ditemukan");
      }

      // Map patient data from API response
      const patientData = patientResponse.patient || patientResponse.data;
      setPatient({
        id: patientData.id || pasienId,
        nama: patientData.full_name || patientData.user?.name || "-",
        foto: patientData.picture
          ? `${import.meta.env.VITE_API_BASE_URL || ""}/${patientData.picture}`
          : "https://via.placeholder.com/150/007bff/ffffff?text=PS",
        allergy: Array.isArray(patientData.allergies)
          ? patientData.allergies.join(", ")
          : typeof patientData.allergies === "string" &&
            patientData.allergies.includes(",")
          ? patientData.allergies
              .split(",")
              .map((a) => a.trim())
              .join(", ")
          : patientData.allergies || "",
        email: patientData.user?.email || "-",
      });

      // 2. Fetch medical records using medicalRecordService.getForPatient()
      const recordsResponse = await doctorService.viewPatientMedicalRecords(
        currentDoctorId,
        pasienId
      );

      const records = recordsResponse.records || [];
      // console.log(records);
      setMedicalRecords(records);

      // 3. Fetch doctor's medical notes using doctorService.viewPatientMedicalRecords()
      const notesResponse = await doctorService.viewPatientMedicalRecords(
        currentDoctorId,
        pasienId
      );

      const notes = notesResponse.records || notesResponse.data || [];

      // Transform notes to match UI expectations
      const transformedNotes = notes.map((note) => ({
        id: note.id,
        tanggal: note.created_at || note.date || "-",
        dokter: note.doctor_name || "Dr. Anda",
        diagnosis: note.diagnosis || note.disease_name || "-",
        resepObat: note.prescription || note.resep_obat || "",
        catatan: note.doctor_note || "",
      }));

      setDoctorNotes(transformedNotes);
    } catch (err) {
      console.error("Error fetching patient data:", err);
      setError(err.message || "Gagal memuat data pasien");
    } finally {
      setLoading(false);
    }
  };

  const handleOpenAddNoteModal = () => {
    setIsAddNoteModalOpen(true);
  };

  const handleCloseAddNoteModal = () => {
    setIsAddNoteModalOpen(false);
  };

  const handleFileUpload = async (e) => {
    e.preventDefault();
    setError("");
    setSuccess("");

    if (!selectedFile || !selectedDoctorId || !diseaseName) {
      setError("Semua field wajib harus diisi.");
      return;
    }

    setUploadLoading(true);

    try {
      // Buat FormData untuk upload
      const formData = new FormData();
      formData.append("doctor_id", selectedDoctorId);
      formData.append("file", selectedFile);
      formData.append("disease_name", diseaseName);
      formData.append("doctor_note", doctorNote || "");

      // Kirim FormData ke parent component
      await onSave(formData);

      setSuccess("Rekam medis berhasil ditambahkan!");

      // Tutup modal setelah 1.5 detik
      setTimeout(() => {
        onClose();
      }, 1500);
    } catch (err) {
      console.error("Error uploading:", err);
      setError("Gagal menambahkan rekam medis. Silakan coba lagi.");
    } finally {
      setUploadLoading(false);
    }
  };

  const handleSaveNewNote = async (formData) => {
    try {
      // FormData sudah dibuat di modal dengan doctor_id, file, disease_name, doctor_note
      // Tinggal tambahkan patient_id
      formData.append("patient_id", patient.id);

      // console.log("FormData entries:");
      for (let pair of formData.entries()) {
        // console.log(pair[0] + ": " + pair[1]);
      }

      // Upload menggunakan medicalRecordService
      const result = await medicalRecordService.upload(formData);

      if (result.success) {
        alert("Catatan medis berhasil ditambahkan!");
        // Refresh data
        fetchPatientData();
      } else {
        alert(
          "Gagal menambahkan catatan medis: " +
            (result.message || "Unknown error")
        );
      }
    } catch (error) {
      console.error("Error saving medical record:", error);
      alert("Terjadi kesalahan saat menyimpan catatan medis.");
    }
  };
  const handleBack = () => {
    navigate("/dokter/pasien");
  };

  const handleExportData = async () => {
    try {
      const response = await doctorService.exportPatientData(pasienId);
      if (response.success) {
        alert("Data pasien berhasil diekspor!");
        // Handle download or display export data
        // console.log("Export data:", response);
      }
    } catch (err) {
      console.error("Error exporting data:", err);
      alert("Gagal mengekspor data pasien");
    }
  };

  // Loading state
  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-screen bg-gray-50">
        <div className="text-center">
          <div className="animate-spin rounded-full h-16 w-16 border-b-4 border-emerald-600 mx-auto mb-4"></div>
          <p className="text-gray-600 text-lg">Memuat data pasien...</p>
        </div>
      </div>
    );
  }

  // Error state
  if (error) {
    return (
      <div className="flex items-center justify-center min-h-screen bg-gray-50">
        <div className="bg-white rounded-xl shadow-lg p-8 max-w-md text-center">
          <IoAlertCircleOutline
            size={64}
            className="text-red-500 mx-auto mb-4"
          />
          <h2 className="text-2xl font-semibold text-gray-800 mb-2">
            Terjadi Kesalahan
          </h2>
          <p className="text-gray-600 mb-6">{error}</p>
          <button
            onClick={handleBack}
            className="px-6 py-3 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 transition"
          >
            Kembali ke Daftar Pasien
          </button>
        </div>
      </div>
    );
  }

  if (!patient) {
    return null;
  }

  return (
    <>
      <div className="p-4 sm:p-8 bg-gray-50 min-h-screen">
        {/* Header with Back Button */}
        <div className="flex items-center justify-between mb-8">
          <div className="flex items-center">
            <button
              onClick={handleBack}
              className="mr-4 p-2 hover:bg-gray-200 rounded-lg transition"
              title="Kembali"
            >
              <IoArrowBackOutline size={24} className="text-gray-700" />
            </button>
            <h1 className="text-2xl sm:text-3xl font-semibold text-gray-800">
              Profil Pasien: {sanitize(patient.nama)}
            </h1>
          </div>
          <button
            onClick={handleExportData}
            className="hidden sm:block px-4 py-2 bg-gray-700 text-white rounded-lg font-medium hover:bg-gray-800 transition text-sm"
          >
            Ekspor Data
          </button>
        </div>

        {/* Patient Profile Section */}
        <div className="bg-white rounded-xl shadow-lg p-6 md:p-8 mb-8">
          <div className="flex flex-col sm:flex-row items-center sm:items-start border-b border-gray-200 pb-6 mb-6">
            <img
              src={sanitize(patient.foto)}
              alt={sanitize(patient.nama)}
              className="w-24 h-24 rounded-full object-cover mb-4 sm:mb-0 sm:mr-6 border-4 border-emerald-600 shadow-md"
            />
            <div className="text-center sm:text-left">
              <h2 className="text-2xl sm:text-3xl font-bold text-gray-800">
                {patient.nama}
              </h2>
              {patient.allergy && (
                <p className="mt-1 text-gray-600 italic text-base sm:text-lg">
                  Alergi: {patient.allergy}
                </p>
              )}
            </div>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div className="flex items-center bg-gray-50 p-3 rounded-lg text-gray-700 md:col-span-2">
              <IoMailOutline
                size={20}
                className="mr-3 text-emerald-600 flex-shrink-0"
              />
              <span className="text-sm sm:text-base break-all">
                <strong>Email:</strong> {sanitize(patient.email)}
              </span>
            </div>
          </div>
        </div>
        {/* Medical Records Section */}
        <div className="flex items-center mt-12 mb-6 text-emerald-600">
          <IoFlaskOutline size={25} className="mr-3" />
          <h2 className="text-xl sm:text-2xl font-semibold text-gray-800">
            Rekam Medis Digital
          </h2>
        </div>

        {medicalRecords.length > 0 ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-12">
            {medicalRecords.map((record) => {
              // Ambil filename dari path_file
              const filename = record.path_file
                ? record.path_file.split("/").pop()
                : null;

              // Cek apakah file adalah gambar
              const isImage =
                filename && /\.(jpeg|jpg|png|gif)$/i.test(filename);

              return (
                <div
                  key={record.id}
                  className="bg-white rounded-xl shadow-md overflow-hidden flex flex-col"
                >
                  {/* Preview Section */}
                  <div className="relative w-full h-48 bg-gray-100 flex items-center justify-center p-4">
                    {isImage ? (
                      <img
                        src={`https://api.curameet-secure.duckdns.org/api/files/medical-records/${
                          record.patient_id
                        }/${filename}?token=${localStorage.getItem(
                          "authToken"
                        )}`}
                        alt={record.disease_name}
                        className="object-cover w-full h-full"
                        onError={(e) => {
                          e.target.onerror = null;
                          e.target.src =
                            "https://via.placeholder.com/300x200/cccccc/666666?text=Image+Not+Found";
                        }}
                      />
                    ) : (
                      <div className="text-5xl text-red-500">
                        <IoDocument />
                      </div>
                    )}
                  </div>

                  {/* Content Section */}
                  <div className="p-5 flex-grow">
                    <h3 className="text-lg sm:text-xl font-semibold mb-2 text-gray-800">
                      {sanitize(record.disease_name)}
                    </h3>
                    <p className="text-gray-600 text-sm mb-1">
                      <strong>Tanggal:</strong> {sanitize(record.created_at)}
                    </p>
                    <p className="text-gray-600 text-sm mb-1">
                      <strong>File:</strong> {sanitize(filename)}
                    </p>
                    {record.catatan_dokter && (
                      <p className="text-gray-600 text-sm mt-2 pt-2 border-t border-gray-200">
                        <strong>Catatan:</strong>{" "}
                        {sanitize(record.catatan_dokter)}
                      </p>
                    )}
                  </div>

                  {/* Action Button */}
                  <div className="p-4 border-t border-gray-200">
                    {record.path_file && filename && (
                      <a
                        href={`https://api.curameet-secure.duckdns.org/api/files/medical-records/${
                          record.patient_id
                        }/${filename}?token=${localStorage.getItem(
                          "authToken"
                        )}`}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="w-full inline-flex items-center justify-center py-2 px-4 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition duration-200 ease-in-out text-sm"
                      >
                        <IoDocument className="mr-2 text-lg" />
                        {isImage ? "Lihat Gambar" : "Lihat Dokumen"}
                      </a>
                    )}
                  </div>
                </div>
              );
            })}
          </div>
        ) : (
          <div className="bg-gray-100 rounded-lg p-8 text-center mb-12">
            <IoFlaskOutline size={48} className="text-gray-400 mx-auto mb-3" />
            <p className="text-gray-600 italic">
              Tidak ada rekam medis digital untuk pasien ini.
            </p>
          </div>
        )}

        {/* Add Medical Note Button */}
        <div className="text-center mt-12 mb-8">
          <button
            className="px-6 sm:px-8 py-3 sm:py-4 bg-blue-600 text-white rounded-lg font-semibold text-base sm:text-lg hover:bg-blue-700 transition shadow-lg hover:shadow-xl"
            onClick={handleOpenAddNoteModal}
          >
            Tambahkan Catatan Medis Baru
          </button>
        </div>
      </div>

      {/* Add Medical Record Modal */}
      <AddMedicalRecordModal
        show={isAddNoteModalOpen}
        onClose={handleCloseAddNoteModal}
        onSave={handleSaveNewNote}
        doctor={currentDoctor}
      />
    </>
  );
};

export default DoctorPasienDetail;
