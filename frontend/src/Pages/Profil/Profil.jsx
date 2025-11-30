import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { authService } from "../../services/authService";
import { patientService } from "../../services/patientService";

const Profil = () => {
  // ✅ State disesuaikan dengan field yang bisa diproses backend
  const [formData, setFormData] = useState({
    full_name: "",
    email: "",
    NIK: "",
    allergies: "",
    disease_histories: "",
  });

  const [patientId, setPatientId] = useState(null);
  const [isEditing, setIsEditing] = useState(false);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [successMessage, setSuccessMessage] = useState("");

  const navigate = useNavigate();

  useEffect(() => {
    const fetchProfile = async () => {
      setLoading(true);
      setError("");
      try {
        if (authService.isAuthenticated()) {
          const res = await patientService.getProfile();
          if (res.success && res.patient) {
            setPatientId(res.patient.id);

            // ✅ Mengisi form dengan data yang relevan dari backend
            setFormData({
              full_name: res.patient.full_name || "",
              email: res.patient.user?.email || "", // Ambil email dari relasi user
              NIK: res.patient.NIK || "",
              allergies: res.patient.allergies || "",
              disease_histories: res.patient.disease_histories || "",
            });
          } else {
            setError(res.message || "Gagal mengambil data profil pasien.");
          }
        } else {
          navigate("/");
        }
      } catch (err) {
        console.error("Failed to fetch patient profile:", err);
        setError(
          err.response?.data?.message || "Terjadi kesalahan pada server."
        );
      } finally {
        setLoading(false);
      }
    };
    fetchProfile();
  }, [navigate]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prevState) => ({ ...prevState, [name]: value }));
  };

  const handleSave = async (e) => {
    e.preventDefault();
    if (!patientId) return;

    setError("");
    setSuccessMessage("");

    try {
      // Langsung kirim formData karena key-nya sudah sesuai
      const res = await patientService.updateProfile(patientId, formData);

      if (res.success) {
        setIsEditing(false);
        setSuccessMessage("Data berhasil disimpan!");
        setTimeout(() => setSuccessMessage(""), 3000);

        // Perbarui info di localStorage
        const currentUserInfo = authService.getCurrentUser();
        if (currentUserInfo) {
          const updatedUserInfo = {
            ...currentUserInfo,
            name: formData.full_name,
            email: formData.email,
          };
          localStorage.setItem("userInfo", JSON.stringify(updatedUserInfo));
        }
      } else {
        setError(res.message || "Gagal menyimpan data!");
      }
    } catch (err) {
      console.error("Failed to save patient data:", err);
      setError(
        err.response?.data?.message || "Terjadi kesalahan saat menyimpan data!"
      );
    }
  };

  const handleEdit = () => setIsEditing(true);
  const handleViewMedicalRecords = () => navigate(`/rekam-medis`);

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gray-100 p-4">
        <p className="text-gray-700 text-lg">Loading Profile...</p>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-100 p-8 flex flex-col items-center">
      <h1 className="text-4xl md:text-5xl font-bold text-gray-800 mb-8 mt-4">
        Data Diri
      </h1>
      <div className="bg-white p-8 rounded-2xl shadow-lg w-full max-w-4xl">
        <form onSubmit={handleSave} className="space-y-6">
          {error && (
            <div className="bg-red-100 text-red-700 p-3 rounded-lg text-center">
              ⚠️ {error}
            </div>
          )}
          {successMessage && (
            <div className="bg-green-100 text-green-700 p-3 rounded-lg text-center">
              ✅ {successMessage}
            </div>
          )}

          {/* ✅ Form disederhanakan */}
          <div>
            <label
              htmlFor="full_name"
              className="block text-base font-medium mb-2 text-gray-700"
            >
              Nama Lengkap<span className="text-red-500">*</span>
            </label>
            <input
              type="text"
              id="full_name"
              name="full_name"
              value={formData.full_name}
              onChange={handleChange}
              disabled={!isEditing}
              required
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100"
            />
          </div>
          <div>
            <label
              htmlFor="email"
              className="block text-base font-medium mb-2 text-gray-700"
            >
              Email<span className="text-red-500">*</span>
            </label>
            <input
              type="email"
              id="email"
              name="email"
              value={formData.email}
              onChange={handleChange}
              disabled={!isEditing}
              required
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100"
            />
          </div>
          <div>
            <label
              htmlFor="NIK"
              className="block text-base font-medium mb-2 text-gray-700"
            >
              NIK<span className="text-red-500">*</span>
            </label>
            <input
              type="text"
              id="NIK"
              name="NIK"
              value={formData.NIK}
              onChange={handleChange}
              disabled={!isEditing}
              required
              maxLength="16"
              pattern="\d{16}"
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100"
            />
          </div>
          <div>
            <label
              htmlFor="allergies"
              className="block text-base font-medium mb-2 text-gray-700"
            >
              Alergi
            </label>
            <textarea
              id="allergies"
              name="allergies"
              rows="3"
              value={formData.allergies}
              onChange={handleChange}
              disabled={!isEditing}
              placeholder="Contoh: Debu, Makanan Laut, Kacang"
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-y disabled:bg-gray-100"
            ></textarea>
          </div>
          <div>
            <label
              htmlFor="disease_histories"
              className="block text-base font-medium mb-2 text-gray-700"
            >
              Riwayat Penyakit
            </label>
            <textarea
              id="disease_histories"
              name="disease_histories"
              rows="5"
              value={formData.disease_histories}
              onChange={handleChange}
              disabled={!isEditing}
              placeholder="Contoh: Asma, Diabetes"
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-y disabled:bg-gray-100"
            ></textarea>
          </div>

          {isEditing && (
            <button
              type="submit"
              className="w-full py-3 px-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300 ease-in-out mt-6"
            >
              Simpan
            </button>
          )}
        </form>
        {!isEditing && (
          <div className="flex flex-col md:flex-row gap-4 mt-6">
            <button
              type="button"
              onClick={handleEdit}
              className="w-full md:w-1/2 py-3 px-4 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition duration-300 ease-in-out"
            >
              Edit Data
            </button>
            <button
              type="button"
              onClick={handleViewMedicalRecords}
              className="w-full md:w-1/2 py-3 px-4 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition duration-300 ease-in-out"
              disabled={!patientId}
            >
              Lihat Rekam Medis
            </button>
          </div>
        )}
      </div>
    </div>
  );
};

export default Profil;
