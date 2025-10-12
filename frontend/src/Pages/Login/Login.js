import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import axios from 'axios'; // 1. Impor axios
import './Login.css';

const Login = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState(''); // State untuk pesan error
  const navigate = useNavigate();

  // 2. Ubah handleSubmit menjadi fungsi async
  const handleSubmit = async (event) => {
    event.preventDefault();
    setError(''); // Reset pesan error setiap kali submit

    try {
      // 3. Kirim request POST ke API Laravel Anda
      const response = await axios.post('http://localhost:8000/api/login', {
        email: email,
        password: password,
        // Anda bisa menambahkan 'role' atau 'remember_me' jika diperlukan
        // role: 'user', 
        // remember_me: true
      });

      // 4. Proses respons yang berhasil
      if (response.data.success) {
        alert('Login berhasil!');
        
        // Simpan token atau data user jika ada di respons
        // Contoh: localStorage.setItem('token', response.data.token);

        navigate('/janji-temu'); // Arahkan ke halaman selanjutnya
      }

    } catch (err) {
      // 5. Tangani error dari API
      if (err.response && err.response.status === 401) {
        // Jika status error 401 (Unauthorized) dari backend
        setError('Login gagal. Periksa kembali email dan password Anda.');
      } else {
        // Untuk error lainnya (misal: server mati)
        setError('Terjadi kesalahan pada server. Silakan coba lagi nanti.');
      }
      console.error(err); // Log error untuk debugging
    }
  };

  return (
    <div className="login-container">
      <h1 className="main-title">CuraMeet</h1>
      <div className="login-card">
        <h2 className="card-title">Login</h2>
        {/* Tampilkan pesan error jika ada */}
        {error && <p className="error-message">{error}</p>}
        <form onSubmit={handleSubmit}>
          {/* ... sisa form tidak berubah ... */}
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
           <Link to="/forgot-password" className="forgot-password-link">
             Lupa password?
           </Link>
           <button type="submit" className="login-button">
             Login
           </button>
        </form>
        <Link to="/register" className="register-link">
          Registrasi
        </Link>
      </div>
    </div>
  );
};

export default Login;