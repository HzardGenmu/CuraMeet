import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";

import TextDetailModal from "../../components/TextDetailModal/TextDetailModal";

import { authService } from "../../services/authService";
import { patientService } from "../../services/patientService";
import { medicalRecordService } from "../../services/medicalRecordService"; // ✅ 1. Import service yang benar

const CatatanMedis = () => {
  const [catatanMedis, setCatatanMedis] = useState([]);
  const [isDetailModalOpen, setIsDetailModalOpen] = useState(false);
  const [modalTitle, setModalTitle] = useState("");
  const [modalContent, setModalContent] = useState("");
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  const navigate = useNavigate();

  useEffect(() => {
    const fetchCatatan = async () => {
      setLoading(true);
      setError("");
      try {
        if (!authService.isAuthenticated()) {
          navigate("/login");
          return;
        }

        // ✅ 2. Alur pengambilan data yang benar
        // Langkah A: Ambil profil pasien terlebih dahulu untuk mendapatkan patient.id
        const profileRes = await patientService.getProfile();
        if (profileRes.success && profileRes.patient) {
          const patientId = profileRes.patient.id;

          // Langkah B: Gunakan patient.id untuk mengambil catatan medis dari service yang benar
          const recordsRes = await medicalRecordService.getForPatient(patientId);
          if (recordsRes.success && Array.isArray(recordsRes.records)) {
            setCatatanMedis(recordsRes.records);
          } else {
            setCatatanMedis([]); // Tetapkan array kosong jika tidak ada catatan
          }
        } else {
          setError(profileRes.message || "Gagal memuat profil pasien.");
        }
      } catch (err) {
        console.error("Failed to load medical records:", err);
        setError(err.response?.data?.message || "Gagal memuat catatan medis.");
      } finally {
        setLoading(false);
      }
    };
    fetchCatatan();
  }, [navigate]);

  const handleCardClick = (catatan) => {
    // Logika ini sudah baik, menyesuaikan dengan key dari backend
    const fullContent = `Diagnosa:\n${
      catatan.disease_name || "-"
    }\n\nCatatan Dokter:\n${catatan.catatan_dokter || "-"}`;
    const formattedDate = new Date(catatan.created_at).toLocaleDateString(
      "id-ID",
      { day: "numeric", month: "long", year: "numeric" }
    );
    setModalTitle(`Detail Catatan Medis - ${formattedDate}`);
    setModalContent(fullContent);
    setIsDetailModalOpen(true);
  };

  const handleCloseDetailModal = () => {
    setIsDetailModalOpen(false);
  };

  return (
    <>
      <div className="p-8 min-h-screen bg-gray-50">
        <div className="mb-8">
          <h1 className="text-3xl font-semibold m-0 text-gray-800">
            Catatan Medis
          </h1>
          {loading ? (
            <p className="mt-2 text-gray-600">Memuat data catatan medis...</p>
          ) : error ? (
            <p className="mt-2 text-red-600">{error}</p>
          ) : (
            <p className="text-lg text-gray-600 mt-2">
              Anda memiliki {catatanMedis.length} catatan medis
            </p>
          )}
        </div>
        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
          {catatanMedis.map((catatan) => (
            <div
              key={catatan.id}
              className="bg-white rounded-xl shadow-md overflow-hidden transition-transform duration-200 ease-in-out cursor-pointer h-64 hover:translate-y-[-5px] hover:shadow-lg"
              onClick={() => handleCardClick(catatan)}
            >
              <div className="bg-gray-50 px-5 py-3 border-b border-gray-200">
                <p className="m-0 font-semibold text-gray-800 text-base">
                  {new Date(catatan.created_at).toLocaleDateString("id-ID", {
                    day: "numeric",
                    month: "long",
                    year: "numeric",
                  })}
                </p>
              </div>
              <div className="p-5 flex flex-col justify-between h-[calc(100%-57px)]">
                <div className="space-y-2">
                  <p className="m-0 text-gray-700 text-sm">
                    <strong className="font-semibold">Dokter:</strong>{" "}
                    {catatan.doctor_name || "-"}
                  </p>
                  <p className="m-0 text-gray-700 text-sm">
                    <strong className="font-semibold">Diagnosa:</strong>
                  </p>
                  <p className="mt-1 text-gray-800 text-sm line-clamp-3">
                    {catatan.disease_name || "-"}
                  </p>
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
