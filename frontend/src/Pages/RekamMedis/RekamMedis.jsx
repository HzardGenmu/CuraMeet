import React, { useState, useEffect } from "react";
import { patientService } from "../../services/patientService";
import { authService } from "../../services/authService";

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
  const [selectedFile, setSelectedFile] = useState(null);
  const [uploadLoading, setUploadLoading] = useState(false);

  const userInfo = JSON.parse(localStorage.getItem("userInfo") || "{}");

  useEffect(() => {
    if (authService.isAuthenticated() && userInfo?.id) {
      loadMedicalRecords();
    } else {
      setError("Anda belum login. Silakan login terlebih dahulu.");
      setLoading(false);
    }
  }, []);

  const loadMedicalRecords = async () => {
    try {
      setLoading(true);
      console.log("Loading medical records for patient:", userInfo.id);
      const response = await patientService.getMedicalRecords(userInfo.id);
      console.log("Medical records response:", response);

      if (response.success) {
        setRecords(response.records || response.data || []);
      } else {
        setRecords([]);
        console.warn("No medical records found");
      }
    } catch (error) {
      console.error("Error loading medical records:", error);
      setError("Gagal memuat rekam medis");
      setRecords([]);
    } finally {
      setLoading(false);
    }
  };

  const handleFileUpload = async (e) => {
    e.preventDefault();

    if (!selectedFile) {
      setError("Pilih file terlebih dahulu");
      return;
    }

    const allowedTypes = [
      "application/pdf",
      "image/jpeg",
      "image/jpg",
      "image/png",
    ];
    if (!allowedTypes.includes(selectedFile.type)) {
      setError("Hanya file PDF, JPG, JPEG, dan PNG yang diizinkan");
      return;
    }

    if (selectedFile.size > 5 * 1024 * 1024) {
      setError("Ukuran file maksimal 5MB");
      return;
    }

    try {
      setUploadLoading(true);
      setError("");
      setSuccess("");
      console.log("Uploading file for patient:", userInfo.id);
      const response = await patientService.uploadMedicalRecord(
        userInfo.id,
        selectedFile
      );
      console.log("Upload response:", response);

      if (response.success) {
        setSuccess("File rekam medis berhasil diupload!");
        setSelectedFile(null);
        document.getElementById("fileInput").value = "";
        await loadMedicalRecords();
      } else {
        setError(response.message || "Gagal mengupload file");
      }
    } catch (error) {
      console.error("Error uploading file:", error);
      setError("Terjadi kesalahan saat mengupload file");
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

  const getFileIcon = (filePath) => {
    if (!filePath) return <IoDocument size={24} className="text-gray-500" />;

    const extension = filePath.split(".").pop()?.toLowerCase();
    switch (extension) {
      case "pdf":
        return <IoDocument size={24} className="text-red-500" />;
      case "jpg":
      case "jpeg":
      case "png":
        return <IoImage size={24} className="text-blue-500" />; // Menggunakan IoImage
      default:
        return <IoDocument size={24} className="text-gray-500" />;
    }
  };

  if (loading && records.length === 0) {
    return (
      // Loading State
      <div className="min-h-screen flex items-center justify-center bg-gray-100 p-4">
        <div className="flex flex-col items-center space-y-4">
          <div className="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
          <p className="text-lg text-gray-700">Memuat rekam medis...</p>
        </div>
      </div>
    );
  }

  return (
    // .rekam-medis-container
    <div className="min-h-screen bg-gray-100 p-6 sm:p-8 flex flex-col items-center">
      {/* .rekam-medis-header */}
      <div className="w-full max-w-6xl mb-8">
        <h1 className="text-4xl md:text-5xl font-bold text-gray-800 text-center mb-6">
          <span className="inline-block align-middle mr-2">📋</span> Rekam Medis Saya
        </h1>
      </div>

      {/* File Upload Section */}
      <div className="w-full max-w-3xl mb-12">
        {/* .upload-card */}
        <div className="border-2 border-dashed border-gray-300 rounded-2xl p-6 sm:p-8 bg-white text-center transition duration-300 ease-in-out
                          hover:border-emerald-600 hover:text-emerald-700 hover:shadow-md">
          <h3 className="text-2xl font-semibold text-gray-800 mb-4 flex items-center justify-center">
            <IoCloudUpload className="mr-3 text-3xl text-blue-500" /> Upload Rekam Medis Baru
          </h3>

          {error && <div className="bg-red-100 text-red-700 px-4 py-2 rounded-lg mb-4 text-sm flex items-center justify-center space-x-2">
                      <span className="text-lg">❌</span> <span>{error}</span>
                    </div>}
          {success && <div className="bg-green-100 text-green-700 px-4 py-2 rounded-lg mb-4 text-sm flex items-center justify-center space-x-2">
                        <span className="text-lg">✅</span> <span>{success}</span>
                      </div>}

          <form onSubmit={handleFileUpload} className="space-y-4">
            <div className="relative border border-gray-300 rounded-lg overflow-hidden cursor-pointer">
              <input
                id="fileInput"
                type="file"
                accept=".pdf,.jpg,.jpeg,.png"
                onChange={(e) => setSelectedFile(e.target.files[0])}
                className="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                disabled={uploadLoading}
              />
              <label htmlFor="fileInput" className="block w-full py-3 px-4 text-gray-700 bg-gray-50 text-center cursor-pointer
                                                   hover:bg-gray-100 transition duration-200 ease-in-out text-base font-medium">
                {selectedFile
                  ? selectedFile.name
                  : "Pilih file (PDF, JPG, PNG)"}
              </label>
            </div>

            <button
              type="submit"
              className="w-full py-3 px-6 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300 ease-in-out
                         disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center text-lg"
              disabled={!selectedFile || uploadLoading}
            >
              {uploadLoading ? (
                <>
                  <div className="w-5 h-5 border-2 border-blue-200 border-t-blue-600 rounded-full animate-spin mr-3"></div>
                  Mengupload...
                </>
              ) : (
                <>
                  <IoCloudUpload className="mr-3 text-xl" /> Upload File
                </>
              )}
            </button>
          </form>

          <div className="text-gray-500 text-sm mt-4">
            <p>• Maksimal ukuran file: 5MB</p>
            <p>• Format yang didukung: PDF, JPG, JPEG, PNG</p>
          </div>
        </div>
      </div>

      {/* Medical Records List */}
      <div className="w-full max-w-6xl">
        <h3 className="text-2xl font-semibold text-gray-800 mb-6 text-center">
          <span className="inline-block align-middle mr-2">📁</span> Daftar Rekam Medis
        </h3>

        {records.length === 0 ? (
          <div className="flex flex-col items-center justify-center p-8 bg-white rounded-2xl shadow-lg text-gray-500">
            <IoDocument size={80} className="mb-4 text-gray-400" />
            <h4 className="text-xl font-semibold mb-2">Belum Ada Rekam Medis</h4>
            <p className="text-base text-center">Upload file rekam medis pertama Anda di atas</p>
          </div>
        ) : (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {records.map((record) => (
              // .record-card
              <div key={record.id} className="bg-white rounded-2xl shadow-lg flex flex-col cursor-pointer
                                             transition transform duration-200 ease-in-out hover:scale-105 hover:shadow-xl overflow-hidden">
                <div className="relative w-full h-48 bg-gray-100 flex items-center justify-center p-4">
                  {record.path_file && (
                    <>
                      {record.path_file.match(/\.(jpeg|jpg|png)$/i) ? (
                        <img
                          src={`/storage/${record.path_file}`} 
                          alt="Rekam Medis"
                          className="object-cover w-full h-full rounded-t-2xl"
                        />
                      ) : (
                        <div className="text-5xl text-red-500">
                           <IoDocument /> 
                        </div>
                      )}
                    </>
                  )}
                  
                  <div className="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60"></div>
                </div>

                <div className="p-4 flex-grow">
                    <h4 className="text-xl font-semibold text-gray-800 mb-2">{record.disease_name || "Rekam Medis"}</h4>
                    <div className="text-gray-600 text-sm space-y-1">
                      <div className="flex items-center">
                        <IoCalendar className="mr-2 text-gray-500" />
                        <span>{formatDate(record.created_at)}</span>
                      </div>
                      <div className="flex items-center">
                        <IoPerson className="mr-2 text-gray-500" />
                        <span>{record.doctor_name || userInfo?.name || "Pasien"}</span> 
                      </div>
                    </div>

                    {record.notes && (
                      <div className="mt-4 text-gray-700 text-sm border-t border-gray-200 pt-3">
                        <strong className="block mb-1">Catatan:</strong>
                        <p className="line-clamp-3">{record.notes}</p> {/* Batasi tampilan catatan hingga 3 baris */}
                      </div>
                    )}
                </div>

                <div className="p-4 border-t border-gray-200 mt-auto">
                  {record.path_file && (
                    <a
                      href={`/storage/${record.path_file}`} // Sesuaikan path sesuai backend
                      target="_blank"
                      rel="noopener noreferrer"
                      className="w-full inline-flex items-center justify-center py-2 px-4 border border-blue-500 text-blue-600 font-medium rounded-lg
                                 hover:bg-blue-50 transition duration-200 ease-in-out text-base"
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