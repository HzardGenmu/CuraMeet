import React, { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { authService } from "../../services/authService";

const Register = () => {
  // PERBAIKAN 2: Konsistensi state dengan payload API
  const [name, setName] = useState("");
  const [nik, setNik] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const navigate = useNavigate();

  const handleSubmit = async (event) => {
    event.preventDefault();
    setError("");

    if (password !== confirmPassword) {
      setError("Password dan konfirmasi password tidak sama.");
      return;
    }

    setLoading(true);
    try {
      const userData = {
        name, // Menggunakan state yang sudah konsisten
        email,
        password,
        password_confirmation: confirmPassword,
        NIK: nik,
        role: "patient", // Secara eksplisit mengirim role default
      };

      const response = await authService.register(userData);

      // PERBAIKAN 3: Logika sukses terpusat di `try`
      if (response.success) {
        // PERBAIKAN 1: Menghilangkan `alert` untuk UX yang lebih baik
        // Mengarahkan ke halaman login dengan pesan sukses (opsional)
        navigate("/login", {
          state: { message: "Registrasi berhasil! Silakan login." },
        });
      } else {
        // Ini akan menangani error logis dari backend (success: false)
         setError(response.message || "Registrasi gagal karena alasan yang tidak diketahui.");
      }

    } catch (err) {
      // PERBAIKAN 3: Penanganan error terpusat di `catch`
      console.error("Registration failed:", err);
      // Menangani error validasi dari Laravel (422) atau error server lainnya
      if (err.response?.data?.errors) {
        const errorMessages = Object.values(err.response.data.errors).flat().join(' ');
        setError(errorMessages);
      } else {
        setError(err.response?.data?.message || "Terjadi kesalahan pada server.");
      }
    } finally {
      setLoading(false);
    }
  };
  
  // ( ... Bagian JSX Anda tidak perlu diubah, sudah sangat baik ... )
  // Saya sertakan kembali untuk kelengkapan
  return (
    <div className="min-h-screen flex flex-col items-center justify-center bg-emerald-300 p-4 box-border">
      <h1 className="text-6xl font-bold text-emerald-800 mb-8 mt-12 md:mt-0">CuraMeet</h1>
      <div className="w-full max-w-md bg-white rounded-xl shadow-xl p-10 text-center custom-shadow">
        <h2 className="text-3xl font-semibold text-gray-800 mb-6">Register</h2>
        <form onSubmit={handleSubmit} className="space-y-6" aria-disabled={loading}>
          {error && (
            <div className="bg-red-100 text-red-700 border border-red-700 px-4 py-2 rounded mb-4 text-sm flex items-center" role="alert">
              <span>⚠️ {error}</span>
            </div>
          )}
          
          <div>
            <label htmlFor="name" className="block text-sm font-medium mb-2 text-gray-700 text-left">
              Nama<span className="text-red-500">*</span>
            </label>
            <input
              type="text"
              id="name"
              value={name}
              onChange={(e) => setName(e.target.value)}
              required
              disabled={loading}
              placeholder="Masukkan nama lengkap Anda"
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base"
            />
          </div>

          <div>
            <label htmlFor="nik" className="block text-sm font-medium mb-2 text-gray-700 text-left">
              NIK<span className="text-red-500">*</span>
            </label>
            <input
              type="text"
              id="nik"
              value={nik}
              onChange={(e) => setNik(e.target.value)}
              required
              disabled={loading}
              maxLength={16}
              pattern="\d{16}"
              title="Masukkan 16 digit NIK"
              placeholder="Masukkan 16 digit NIK Anda"
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base"
            />
          </div>

          <div>
            <label htmlFor="email" className="block text-sm font-medium mb-2 text-gray-700 text-left">
              E-mail<span className="text-red-500">*</span>
            </label>
            <input
              type="email"
              id="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
              disabled={loading}
              autoComplete="username"
              placeholder="Masukkan email Anda"
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base"
            />
          </div>

          <div>
            <label htmlFor="password" className="block text-sm font-medium mb-2 text-gray-700 text-left">
              Password<span className="text-red-500">*</span>
            </label>
            <input
              type="password"
              id="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
              disabled={loading}
              autoComplete="new-password"
              placeholder="Masukkan password Anda"
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base"
            />
          </div>

          <div>
            <label htmlFor="confirmPassword" className="block text-sm font-medium mb-2 text-gray-700 text-left">
              Konfirmasi Password<span className="text-red-500">*</span>
            </label>
            <input
              type="password"
              id="confirmPassword"
              value={confirmPassword}
              onChange={(e) => setConfirmPassword(e.target.value)}
              required
              disabled={loading}
              autoComplete="new-password"
              placeholder="Konfirmasi password Anda"
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base"
            />
          </div>
          
          <button
            type="submit"
            className="w-full py-3 px-4 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition ease-in-out duration-300 disabled:opacity-50 disabled:cursor-not-allowed text-lg"
            disabled={loading}
          >
            {loading ? (
              <span>
                <span className="inline-block w-5 h-5 mr-3 border-2 border-blue-200 border-t-blue-600 rounded-full animate-spin align-middle" /> Mendaftar...
              </span>
            ) : (
              "Register"
            )}
          </button>
        </form>

        <div className="flex flex-col items-center mt-6 space-y-3">
          <Link to="/login" className="text-blue-600 hover:underline text-base font-medium" tabIndex={loading ? -1 : 0}>
            Sudah punya akun? Login
          </Link>
        </div>
      </div>
    </div>
  );
};

export default Register;