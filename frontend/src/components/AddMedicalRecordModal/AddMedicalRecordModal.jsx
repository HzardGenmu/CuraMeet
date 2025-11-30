import React, { useState, useEffect } from "react";
import { IoCloudUpload } from "react-icons/io5";

const AddMedicalRecordModal = ({ show, onClose, onSave, doctor }) => {
  const [selectedDoctorId, setSelectedDoctorId] = useState(doctor?.id);
  const [diseaseName, setDiseaseName] = useState("");
  const [doctorNote, setDoctorNote] = useState("");
  const [selectedFile, setSelectedFile] = useState(null);
  const [uploadLoading, setUploadLoading] = useState(false);
  const [error, setError] = useState("");
  const [success, setSuccess] = useState("");

  // Set doctor ID saat modal dibuka dan doctor prop tersedia
  useEffect(() => {
    if (show && doctor && doctor.id) {
      setSelectedDoctorId(doctor.id);
    }
  }, [show, doctor]);

  // Reset form saat modal ditutup
  useEffect(() => {
    if (!show) {
      setSelectedDoctorId("");
      setDiseaseName("");
      setDoctorNote("");
      setSelectedFile(null);
      setError("");
      setSuccess("");
      setUploadLoading(false);
    }
  }, [show]);

  if (!show) {
    return null;
  }

  const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      // Validasi tipe file
      const allowedTypes = [
        "application/pdf",
        "image/jpeg",
        "image/jpg",
        "image/png",
      ];
      if (!allowedTypes.includes(file.type)) {
        setError(
          "Tipe file tidak diizinkan. Hanya PDF, JPG, JPEG, dan PNG yang diperbolehkan."
        );
        e.target.value = "";
        return;
      }

      // Validasi ukuran file (max 2MB)
      const maxSize = 2 * 1024 * 1024; // 2MB
      if (file.size > maxSize) {
        setError("Ukuran file terlalu besar. Maksimal 2MB.");
        e.target.value = "";
        return;
      }

      setSelectedFile(file);
      setError("");
    }
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
  return (
    <div
      className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      onClick={onClose}
    >
      <div
        className="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto"
        onClick={(e) => e.stopPropagation()}
      >
        <div className="p-6 sm:p-8">
          <div className="border-2 border-dashed border-gray-300 rounded-2xl p-6 sm:p-8 bg-white text-center transition duration-300 ease-in-out hover:border-emerald-600 hover:shadow-md">
            <h3 className="text-2xl font-semibold text-gray-800 mb-4 flex items-center justify-center">
              <IoCloudUpload className="mr-3 text-3xl text-blue-500" /> Upload
              Rekam Medis Baru
            </h3>

            {error && (
              <div className="bg-red-100 text-red-700 px-4 py-2 rounded-lg mb-4 text-sm flex items-center justify-center space-x-2">
                <span>❌</span> <span>{error}</span>
              </div>
            )}
            {success && (
              <div className="bg-green-100 text-green-700 px-4 py-2 rounded-lg mb-4 text-sm flex items-center justify-center space-x-2">
                <span>✅</span> <span>{success}</span>
              </div>
            )}

            <div className="space-y-4">
              {/* Info Dokter (read-only) */}
              {doctor && (
                <div className="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-left">
                  <strong>Dokter:</strong> {doctor.full_name} -{" "}
                  {doctor.specialist}
                </div>
              )}

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
                  onChange={handleFileChange}
                  className="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                  disabled={uploadLoading}
                />
                <label
                  htmlFor="fileInput"
                  className="block w-full py-3 px-4 text-gray-700 bg-gray-50 text-center cursor-pointer hover:bg-gray-100 transition duration-200 ease-in-out text-base font-medium"
                >
                  {selectedFile
                    ? selectedFile.name
                    : "Pilih file (PDF, JPG, PNG) *"}
                </label>
              </div>

              <button
                type="button"
                onClick={handleFileUpload}
                className="w-full py-3 px-6 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center text-lg"
                disabled={
                  !selectedFile ||
                  !selectedDoctorId ||
                  !diseaseName ||
                  uploadLoading
                }
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
            </div>

            <div className="text-gray-500 text-sm mt-4">
              <p>• Maksimal ukuran file: 2MB</p>
              <p>• Format yang didukung: PDF, JPG, PNG</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default AddMedicalRecordModal;
