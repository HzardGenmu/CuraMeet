import React, { useState, useEffect } from "react";
import { patientService } from "../../services/patientService";
import { authService } from "../../services/authService";
import "./RekamMedis.css";
import {
  IoCloudUpload,
  IoDocument,
  IoCalendar,
  IoPerson,
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

  // ✅ Load medical records - sesuai backend route
  const loadMedicalRecords = async () => {
    try {
      setLoading(true);
      console.log("Loading medical records for patient:", userInfo.id);

      // ✅ Sesuai route: GET /patient/catatan-medis/{patientId}
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

  // ✅ Handle file upload - sesuai backend route
  const handleFileUpload = async (e) => {
    e.preventDefault();

    if (!selectedFile) {
      setError("Pilih file terlebih dahulu");
      return;
    }

    // Validate file type
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

    // Validate file size (max 5MB)
    if (selectedFile.size > 5 * 1024 * 1024) {
      setError("Ukuran file maksimal 5MB");
      return;
    }

    try {
      setUploadLoading(true);
      setError("");
      setSuccess("");

      console.log("Uploading file for patient:", userInfo.id);

      // ✅ Sesuai route: POST /patient/upload-rekam-medis
      const response = await patientService.uploadMedicalRecord(
        userInfo.id,
        selectedFile
      );
      console.log("Upload response:", response);

      if (response.success) {
        setSuccess("File rekam medis berhasil diupload!");
        setSelectedFile(null);
        document.getElementById("fileInput").value = ""; // Reset file input
        await loadMedicalRecords(); // Refresh records list
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

  // ✅ Format date for display
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

  // ✅ Get file icon based on file type
  const getFileIcon = (filePath) => {
    if (!filePath) return <IoDocument />;

    const extension = filePath.split(".").pop()?.toLowerCase();
    switch (extension) {
      case "pdf":
        return "📄";
      case "jpg":
      case "jpeg":
      case "png":
        return "🖼️";
      default:
        return <IoDocument />;
    }
  };

  if (loading && records.length === 0) {
    return (
      <div className="rekam-medis-container">
        <div className="loading-spinner">
          <div className="spinner"></div>
          <p>Memuat rekam medis...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="rekam-medis-container">
      <div className="rekam-medis-header">
        <h1>📋 Rekam Medis Saya</h1>
      </div>

      {/* File Upload Section */}
      <div className="upload-section">
        <div className="upload-card">
          <h3>
            <IoCloudUpload /> Upload Rekam Medis Baru
          </h3>

          {error && <div className="alert alert-error">❌ {error}</div>}

          {success && <div className="alert alert-success">✅ {success}</div>}

          <form onSubmit={handleFileUpload} className="upload-form">
            <div className="file-input-wrapper">
              <input
                id="fileInput"
                type="file"
                accept=".pdf,.jpg,.jpeg,.png"
                onChange={(e) => setSelectedFile(e.target.files[0])}
                className="file-input"
                disabled={uploadLoading}
              />
              <label htmlFor="fileInput" className="file-input-label">
                {selectedFile
                  ? selectedFile.name
                  : "Pilih file (PDF, JPG, PNG)"}
              </label>
            </div>

            <button
              type="submit"
              className="btn-primary"
              disabled={!selectedFile || uploadLoading}
            >
              {uploadLoading ? (
                <>
                  <div className="spinner-small"></div>
                  Mengupload...
                </>
              ) : (
                <>
                  <IoCloudUpload /> Upload File
                </>
              )}
            </button>
          </form>

          <div className="upload-info">
            <small>
              • Maksimal ukuran file: 5MB
              <br />• Format yang didukung: PDF, JPG, JPEG, PNG
            </small>
          </div>
        </div>
      </div>

      {/* Medical Records List */}
      <div className="records-section">
        <h3>📁 Daftar Rekam Medis</h3>

        {records.length === 0 ? (
          <div className="no-records">
            <div className="empty-state">
              <IoDocument size={64} />
              <h4>Belum Ada Rekam Medis</h4>
              <p>Upload file rekam medis pertama Anda di atas</p>
            </div>
          </div>
        ) : (
          <div className="records-grid">
            {records.map((record) => (
              <div key={record.id} className="record-card">
                <div className="record-header">
                  <div className="file-icon">
                    {getFileIcon(record.path_file)}
                  </div>
                  <h4>{record.disease_name || "Rekam Medis"}</h4>
                </div>

                <div className="record-body">
                  <div className="record-info">
                    <div className="info-item">
                      <IoCalendar className="icon" />
                      <span>{formatDate(record.created_at)}</span>
                    </div>
                    <div className="info-item">
                      <IoPerson className="icon" />
                      <span>{record.doctor_name || "Doctor TBD"}</span>
                    </div>
                  </div>

                  {record.notes && (
                    <div className="record-notes">
                      <strong>Catatan:</strong> {record.notes}
                    </div>
                  )}
                </div>

                <div className="record-actions">
                  {record.path_file && (
                    <a
                      href={`/storage/${record.path_file}`}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="btn-outline"
                    >
                      <IoDocument /> Lihat File
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
