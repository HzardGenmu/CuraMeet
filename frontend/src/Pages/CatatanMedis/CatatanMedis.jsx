import React, { useState, useEffect } from 'react';

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
      {/* .catatan-medis-container */}
      <div className="p-8">
        {/* .page-header */}
        <div className="mb-8">
          <h1 className="text-3xl font-semibold m-0 text-gray-800">Catatan Medis</h1>
          {loading ? (
            <p className="mt-2 text-gray-600">Memuat data catatan medis...</p>
          ) : error ? (
            <p className="mt-2 text-red-600">{error}</p>
          ) : (
            <p className="text-lg text-gray-600 mt-2">Anda memiliki {catatanMedis.length} catatan medis</p>
          )}
        </div>
        {/* .catatan-medis-grid */}
        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5"> {/* Grid responsif */}
          {catatanMedis.map((catatan) => (
            <div
              key={catatan.id}
              className="bg-white rounded-xl shadow-md overflow-hidden transition-transform duration-200 ease-in-out cursor-pointer h-64
                         hover:translate-y-[-5px] hover:shadow-lg" // h-64 = 256px
              onClick={() => handleCardClick(catatan)}
            >
              {/* .catatan-card-header */}
              <div className="bg-gray-50 px-5 py-3 border-b border-gray-200">
                <p className="m-0 font-semibold text-gray-800 text-base">
                  {new Date(catatan.tanggal || catatan.created_at).toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                  }) || '-'}
                </p>
              </div>
              {/* .catatan-card-body */}
              <div className="p-5 flex flex-col justify-between h-[calc(100%-48px)]"> {/* Sesuaikan tinggi body */}
                <div className="space-y-2"> {/* Memberi jarak antar paragraf */}
                  <p className="m-0 text-gray-700 text-sm">
                    <strong className="font-semibold">Dokter:</strong> {catatan.dokter || catatan.doctor_name || '-'}
                  </p>
                  <p className="m-0 text-gray-700 text-sm">
                    <strong className="font-semibold">Diagnosa:</strong>
                  </p>
                  <p className="mt-1 text-gray-800 text-sm line-clamp-2"> {/* line-clamp-2 untuk 2 baris */}
                    {catatan.diagnosis || catatan.disease_name || '-'}
                  </p>
                  {(catatan.resepObat || catatan.resep_obat) && (
                    <>
                      <p className="mt-3 text-gray-700 text-sm">
                        <strong className="font-semibold">Resep Obat:</strong>
                      </p>
                      <p className="mt-1 text-gray-800 text-sm line-clamp-2"> {/* line-clamp-2 */}
                        {catatan.resepObat || catatan.resep_obat}
                      </p>
                    </>
                  )}
                </div>
              </div>
            </div>
          ))}
          {catatanMedis.length === 0 && !loading && !error && (
            <p className="col-span-full text-center text-gray-500 text-lg mt-8">
              Belum ada catatan medis yang tersedia.
            </p>
          )}
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