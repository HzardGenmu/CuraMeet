import React, { useState } from 'react';
import './CatatanMedis.css';
import TextDetailModal from '../../components/TextDetailModal/TextDetailModal';

// Dummy data untuk catatan medis
const dummyCatatanMedis = [
  {
    id: 1,
    tanggal: '23 Oktober 2025',
    dokter: 'Dr. Budi Santoso',
    diagnosis: 'Pasien datang dengan keluhan batuk berdahak selama 5 hari, disertai demam ringan. Pemeriksaan fisik menunjukkan adanya sedikit ronki di paru-paru bagian bawah. Diresepkan antibiotik dan obat batuk. Disarankan untuk istirahat cukup dan banyak minum air putih. Pasien juga mengeluh nyeri tenggorokan saat menelan.',
    resepObat: 'Amoxicillin 500mg (2x sehari), Paracetamol 500mg (3x sehari jika demam).' // Tambah resepObat
  },
  {
    id: 2,
    tanggal: '19 Oktober 2025',
    dokter: 'Dr. Budi Santoso',
    diagnosis: 'Kontrol setelah terapi antibiotik. Batuk sudah mereda, demam hilang. Ronki berkurang. Pasien merasa lebih baik. Terapi dilanjutkan sampai habis. Edukasi pentingnya menjaga kebersihan tangan dan menghindari kontak dengan orang sakit. Disarankan untuk kembali jika ada keluhan baru.',
    resepObat: 'Amoxicillin 500mg (2x sehari).' // Tambah resepObat
  },
  {
    id: 3,
    tanggal: '15 Oktober 2025',
    dokter: 'Dr. Anisa Putri',
    diagnosis: 'Pasien mengeluh sakit kepala berdenyut di bagian pelipis kanan, disertai pandangan kabur sesekali. Riwayat migrain (+) dari keluarga. Pemeriksaan neurologis dasar normal. Diresepkan obat anti-nyeri spesifik migrain dan disarankan menghindari pemicu seperti kafein berlebih dan kurang tidur.',
    resepObat: 'Sumatriptan 50mg (saat serangan), Ibuprofen 400mg (jika nyeri).' // Tambah resepObat
  },
  // ... data lainnya
];

const CatatanMedis = () => {
  const [isDetailModalOpen, setIsDetailModalOpen] = useState(false);
  const [modalTitle, setModalTitle] = useState('');
  const [modalContent, setModalContent] = useState('');

  const handleCardClick = (catatan) => {
    // Gabungkan diagnosa dan resep obat untuk modal
    const fullContent = `Diagnosa:\n${catatan.diagnosis}\n\nResep Obat:\n${catatan.resepObat || 'Tidak ada resep.'}`;
    setModalTitle(`Detail Catatan Medis - ${catatan.tanggal}`);
    setModalContent(fullContent);
    setIsDetailModalOpen(true);
  };

  const handleCloseDetailModal = () => {
    setIsDetailModalOpen(false);
    setModalTitle('');
    setModalContent('');
  };

  return (
    <>
      <div className="catatan-medis-container">
        <div className="page-header">
          <h1 className="page-title">Catatan Medis</h1>
          <p className="record-count">Anda memiliki {dummyCatatanMedis.length} catatan medis</p>
        </div>
        <div className="catatan-medis-grid">
          {dummyCatatanMedis.map((catatan) => (
            <div 
              key={catatan.id} 
              className="catatan-card"
              onClick={() => handleCardClick(catatan)}
            >
              <div className="catatan-card-header">
                <p className="catatan-tanggal">{catatan.tanggal}</p>
              </div>
              <div className="catatan-card-body">
                <p className="catatan-info"><strong>Dokter:</strong> {catatan.dokter}</p>
                <p className="catatan-info"><strong>Diagnosa:</strong></p>
                <p className="catatan-diagnosis-preview">{catatan.diagnosis}</p>
                {/* Tampilkan preview resep obat jika ada */}
                {catatan.resepObat && (
                  <>
                    <p className="catatan-info mt-3"><strong>Resep Obat:</strong></p>
                    <p className="catatan-diagnosis-preview">{catatan.resepObat}</p>
                  </>
                )}
              </div>
            </div>
          ))}
        </div>
      </div>

      <TextDetailModal
        show={isDetailModalOpen}
        title={modalTitle}
        content={modalContent}
        onClose={handleCloseDetailModal}
      />
    </>
  );
};

export default CatatanMedis;