import React, { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { authService } from "../../services/authService";


const Register = () => {
  const [nama, setNama] = useState("");
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
      const response = await authService.register({
        name: nama,
        email: email,
        password: password,
        password_confirmation: confirmPassword,
        NIK: nik,
      });
      console.log(response);
      if (response.success !== false) { // Memperbaiki kondisi if (response.success != false)
        alert("Registrasi berhasil.");
        navigate("/login");
      } else {
        // Handle error messages from backend, assuming response.errors might be an object
        const errorMessages = typeof response.errors === 'object' 
                              ? Object.values(response.errors).flat().join(', ')
                              : response.errors || "Registrasi gagal.";
        setError(errorMessages);
      }
    } catch (err) {
      setError(
        err?.response?.data?.message ||
        err?.message ||
        "Terjadi kesalahan. Silakan coba lagi."
      );
    } finally {
      setLoading(false);
    }
  };

  return (
    
    <div className="min-h-screen flex flex-col items-center justify-center bg-emerald-300 p-4 box-border">
      
      <h1 className="text-6xl font-bold text-emerald-800 mb-8 mt-12 md:mt-0">CuraMeet</h1>

      
      <div className="w-full max-w-md bg-white rounded-xl shadow-xl p-10 text-center custom-shadow">
        
        <h2 className="text-3xl font-semibold text-gray-800 mb-6">Register</h2>

        <form onSubmit={handleSubmit} className="space-y-6" aria-disabled={loading}>
          {error && (
            
            <div className="bg-red-100 text-red-700 border border-red-700 px-4 py-2 rounded mb-4 text-sm flex items-center" role="alert" tabIndex={-1}>
              <span>⚠️ {error}</span>
            </div>
          )}

          {/* Form Group untuk Nama */}
          <div>
            <label htmlFor="nama" className="block text-sm font-medium mb-2 text-gray-700 text-left">
              Nama<span className="text-red-500">*</span>
            </label>
            <input
              type="text"
              id="nama"
              value={nama}
              onChange={(e) => setNama(e.target.value)}
              required
              disabled={loading}
              placeholder="Masukkan nama lengkap Anda"
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base"
            />
          </div>

          {/* Form Group untuk NIK */}
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

          {/* Form Group untuk Email */}
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

          {/* Form Group untuk Password */}
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

          {/* Form Group untuk Konfirmasi Password */}
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

          {/* Tombol Register */}
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

        {/* Link untuk Login */}
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