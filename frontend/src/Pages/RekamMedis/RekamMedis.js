import React, { useState, useRef } from 'react';
import './RekamMedis.css';
import { IoAdd } from 'react-icons/io5';
import ImageModal from '../../components/ImageModal/ImageModal'; // Import ImageModal

const RekamMedis = () => {
  // State untuk menyimpan daftar URL gambar rekam medis
  const [medicalRecords, setMedicalRecords] = useState([]);
  // State untuk mengontrol visibilitas ImageModal
  const [isImageModalOpen, setIsImageModalOpen] = useState(false);
  // State untuk menyimpan URL gambar yang akan ditampilkan di ImageModal
  const [selectedImageUrl, setSelectedImageUrl] = useState('');

  // Ref untuk input file yang tersembunyi
  const fileInputRef = useRef(null);

  // Fungsi untuk memicu klik pada input file
  const handleUploadClick = () => {
    fileInputRef.current.click();
  };

  // Fungsi saat file dipilih
  const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file && file.type.startsWith('image/')) {
      // Buat URL sementara untuk file gambar yang diunggah
      const imageUrl = URL.createObjectURL(file);
      setMedicalRecords(prevRecords => [...prevRecords, imageUrl]);
    } else if (file) {
      alert("Hanya file gambar (JPEG, PNG, GIF, dll.) yang diizinkan.");
    }
  };

  // Fungsi untuk membuka ImageModal
  const handleImageClick = (imageUrl) => {
    setSelectedImageUrl(imageUrl);
    setIsImageModalOpen(true);
  };

  // Fungsi untuk menutup ImageModal
  const handleCloseImageModal = () => {
    setIsImageModalOpen(false);
    setSelectedImageUrl(''); // Reset URL gambar yang dipilih
  };

  return (
    <div className="rekam-medis-container">
      <h1 className="page-title">Rekam Medis</h1>
      <div className="medical-records-grid">
        {/* Render setiap gambar rekam medis yang sudah diupload */}
        {medicalRecords.map((imageUrl, index) => (
          <div 
            key={index} 
            className="record-card existing-record"
            onClick={() => handleImageClick(imageUrl)} // Tambahkan onClick untuk membuka modal
          >
            <img src={imageUrl} alt={`Rekam Medis ${index + 1}`} className="record-image" />
          </div>
        ))}

        {/* Card untuk Upload Rekam Medis */}
        <div className="record-card upload-card" onClick={handleUploadClick}>
          <IoAdd size={50} className="upload-icon" />
          <p>Upload Rekam Medis</p>
          {/* Input file yang tersembunyi */}
          <input
            type="file"
            accept="image/jpeg, image/png, image/gif, image/webp"
            ref={fileInputRef}
            onChange={handleFileChange}
            style={{ display: 'none' }} // Sembunyikan input file
          />
        </div>
      </div>

      {/* Render ImageModal */}
      <ImageModal
        show={isImageModalOpen}
        imageUrl={selectedImageUrl}
        onClose={handleCloseImageModal}
      />
    </div>
  );
};

export default RekamMedis;