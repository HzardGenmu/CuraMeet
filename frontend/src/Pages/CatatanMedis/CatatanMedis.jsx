import React, { useState, useEffect } from 'react';
import './CatatanMedis.css';
import TextDetailModal from '../../components/TextDetailModal/TextDetailModal';

import { patientService } from '../../services/patientService';
import { authService } from '../../services/authService';



const CatatanMedis = () => {
  const [catatanMedis, setCatatanMedis] = useState([]);
  const [isDetailModalOpen, setIsDetailModalOpen] = useState(false);
  const [modalTitle, setModalTitle] = useState('');
  const [modalContent, setModalContent] = useState('');
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    const fetchCatatan = async () => {
      setLoading(true);
      setError('');
      try {
        const user = authService.getCurrentUser();
        if (!user || !user.id) {
          setError('User belum login.');
          setLoading(false);
          return;
        }
        const res = await patientService.getMedicalRecords(user.id);
        if (res.success && Array.isArray(res.records)) {
          setCatatanMedis(res.records);
        } else {
          setCatatanMedis([]);
        }
      } catch (err) {
        setError('Gagal memuat catatan medis.');
      } finally {
        setLoading(false);
      }
    };
    fetchCatatan();
  }, []);

  const handleCardClick = (catatan) => {
    const fullContent = `Diagnosa:\n${catatan.diagnosis || catatan.disease_name || '-'}\n\nResep Obat:\n${catatan.resepObat || catatan.resep_obat || '-'}`;
    setModalTitle(`Detail Catatan Medis - ${catatan.tanggal || catatan.created_at || '-'}`);
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
          {loading ? (
            <p>Memuat data catatan medis...</p>
          ) : error ? (
            <p style={{color:'red'}}>{error}</p>
          ) : (
            <p className="record-count">Anda memiliki {catatanMedis.length} catatan medis</p>
          )}
        </div>
        <div className="catatan-medis-grid">
          {catatanMedis.map((catatan) => (
            <div
              key={catatan.id}
              className="catatan-card"
              onClick={() => handleCardClick(catatan)}
            >
              <div className="catatan-card-header">
                <p className="catatan-tanggal">{catatan.tanggal || catatan.created_at || '-'}</p>
              </div>
              <div className="catatan-card-body">
                <p className="catatan-info"><strong>Dokter:</strong> {catatan.dokter || catatan.doctor_name || '-'}</p>
                <p className="catatan-info"><strong>Diagnosa:</strong></p>
                <p className="catatan-diagnosis-preview">{catatan.diagnosis || catatan.disease_name || '-'}</p>
                {(catatan.resepObat || catatan.resep_obat) && (
                  <>
                    <p className="catatan-info mt-3"><strong>Resep Obat:</strong></p>
                    <p className="catatan-diagnosis-preview">{catatan.resepObat || catatan.resep_obat}</p>
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