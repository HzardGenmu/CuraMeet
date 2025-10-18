import React, { useState, useEffect } from "react";
import "./AddMedicalRecordModal.css";
import { IoClose, IoCloudUploadOutline } from "react-icons/io5";

const AddMedicalRecordModal = ({ show, onClose, onSave }) => {
  const [diagnosa, setDiagnosa] = useState("");
  const [resepObat, setResepObat] = useState("");
  const [file, setFile] = useState(null);
  const [fileName, setFileName] = useState("");

  // Reset form saat modal dibuka atau ditutup
  useEffect(() => {
    if (!show) {
      setDiagnosa("");
      setResepObat("");
      setFile(null);
      setFileName("");
    }
  }, [show]);

  if (!show) {
    return null;
  }

  const handleFileChange = (e) => {
    const selectedFile = e.target.files[0];
    if (selectedFile) {
      // Validasi tipe file
      const allowedTypes = [
        "application/pdf",
        "image/jpeg",
        "image/jpg",
        "image/png",
      ];
      if (!allowedTypes.includes(selectedFile.type)) {
        alert(
          "Tipe file tidak diizinkan. Hanya PDF, JPG, JPEG, dan PNG yang diperbolehkan."
        );
        e.target.value = "";
        return;
      }

      // Validasi ukuran file (max 2MB)
      const maxSize = 2 * 1024 * 1024; // 2MB
      if (selectedFile.size > maxSize) {
        alert("Ukuran file terlalu besar. Maksimal 2MB.");
        e.target.value = "";
        return;
      }

      setFile(selectedFile);
      setFileName(selectedFile.name);
    }
  };

  const handleSubmit = (e) => {
    e.preventDefault();

    if (diagnosa.trim() === "") {
      alert("Diagnosa tidak boleh kosong.");
      return;
    }

    if (!file) {
      alert("File rekam medis harus diunggah.");
      return;
    }

    // Kirim data ke parent component
    onSave({
      diagnosa,
      resepObat,
      file,
    });

    onClose(); // Tutup modal setelah disimpan
  };

  return (
    <div className="add-medical-record-modal-overlay" onClick={onClose}>
      <div
        className="add-medical-record-modal-content"
        onClick={(e) => e.stopPropagation()}
      >
        <div className="add-medical-record-modal-header">
          <h2 className="add-medical-record-modal-title">
            Tambahkan Catatan Medis Baru
          </h2>
          <button onClick={onClose} className="add-medical-record-close-button">
            <IoClose size={28} />
          </button>
        </div>
        <div className="add-medical-record-modal-body">
          <form onSubmit={handleSubmit}>
            <div className="form-group">
              <label htmlFor="diagnosa">
                Diagnosa<span className="required-star">*</span>
              </label>
              <textarea
                id="diagnosa"
                value={diagnosa}
                onChange={(e) => setDiagnosa(e.target.value)}
                rows="5"
                required
              ></textarea>
            </div>

            <div className="form-group">
              <label htmlFor="resepObat">Resep Obat</label>
              <textarea
                id="resepObat"
                value={resepObat}
                onChange={(e) => setResepObat(e.target.value)}
                rows="5"
                placeholder="Misal: Paracetamol 500mg (3x sehari), Amoxicillin 250mg (2x sehari)"
              ></textarea>
            </div>

            <div className="form-group">
              <label htmlFor="file">
                Upload File Rekam Medis<span className="required-star">*</span>
              </label>
              <div className="file-upload-wrapper">
                <input
                  type="file"
                  id="file"
                  accept=".pdf,.jpg,.jpeg,.png"
                  onChange={handleFileChange}
                  required
                  style={{ display: "none" }}
                />
                <label htmlFor="file" className="file-upload-label">
                  <IoCloudUploadOutline size={24} className="mr-2" />
                  {fileName || "Pilih file (PDF, JPG, PNG, max 2MB)"}
                </label>
              </div>
              {fileName && (
                <p className="text-sm text-gray-600 mt-2">
                  File terpilih: <strong>{fileName}</strong>
                </p>
              )}
            </div>

            <button type="submit" className="btn-primary">
              Tambahkan Catatan
            </button>
          </form>
        </div>
      </div>
    </div>
  );
};

export default AddMedicalRecordModal;
