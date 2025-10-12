import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import axios from 'axios'; // 1. Impor axios
import './Register.css';

const Register = () => {
  // State untuk menyimpan nilai dari setiap input field
  const [nama, setNama] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState(''); // State untuk pesan error

  const navigate = useNavigate(); // Hook untuk navigasi

  // 2. Ubah handleSubmit menjadi fungsi async
  const handleSubmit = async (event) => {
    event.preventDefault();
    setError(''); // Hapus pesan error lama saat submit baru

    try {
      // 3. Kirim request POST ke API registrasi Laravel
      const response = await axios.post('http://localhost:8000/api/register', {
        name: nama, // Pastikan key 'name' sesuai dengan yang diharapkan backend
        email: email,
        password: password,
      });

      // 4. Proses respons jika registrasi berhasil
      if (response.data.success) {
        alert('Registrasi berhasil! Silakan login.');
        navigate('/login'); // Arahkan pengguna ke halaman login
      }
    } catch (err) {
      // 5. Tangani error dari API
      if (err.response && err.response.data && err.response.data.message) {
        // Menampilkan pesan error dari backend (jika ada)
        setError(err.response.data.message);
      } else {
        setError('Registrasi gagal. Terjadi kesalahan pada server.');
      }
      console.error(err); // Log error untuk debugging
    }
  };

  return (
    <div className="register-container">
      <h1 className="main-title">CuraMeet</h1>
      <div className="register-card">
        <h2 className="card-title">Register</h2>
        {/* 6. Tampilkan pesan error jika ada */}
        {error && <p className="error-message">{error}</p>}
        <form onSubmit={handleSubmit}>
          {/* ... sisa form tidak berubah ... */}
          <div className="form-group">
            <label htmlFor="nama">Nama<span className="required-star">*</span></label>
            <input
              type="text"
              id="nama"
              value={nama}
              onChange={(e) => setNama(e.target.value)}
              required
            />
          </div>
          <div className="form-group">
            <label htmlFor="email">E-mail<span className="required-star">*</span></label>
            <input
              type="email"
              id="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
            />
          </div>
          <div className="form-group">
            <label htmlFor="password">Password<span className="required-star">*</span></label>
            <input
              type="password"
              id="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
            />
          </div>
          <button type="submit" className="register-button">
            Register
          </button>
        </form>
        <Link to="/login" className="login-link">
          Sudah punya akun?
        </Link>
      </div>
    </div>
  );
};

export default Register;