import React, { useState } from 'react';
import { Link,useNavigate } from 'react-router-dom';
import './Login.css';

const Login = () => {
  // State untuk menyimpan nilai dari setiap input field
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  
  // 2. Inisialisasi hook useNavigate
  const navigate = useNavigate();

  // Fungsi yang dijalankan saat form disubmit
  const handleSubmit = (event) => {
    event.preventDefault(); 
    console.log({
      email,
      password,
    });
    
    // --- Logika Autentikasi Dummy ---
    // Di sini Anda akan melakukan validasi atau mengirim data ke API.
    // Untuk contoh ini, kita asumsikan login selalu berhasil.
    const isLoginSuccessful = true; // Ganti dengan logika autentikasi nyata Anda

    if (isLoginSuccessful) {
      alert('Login berhasil');
      // 3. Arahkan pengguna ke halaman /janji-temu
      navigate('/janji-temu'); 
    } else {
      alert('Login gagal. Periksa email dan password Anda.');
    }
  };
  

  return (
    <div className="login-container"> 
      <h1 className="main-title">CuraMeet</h1>
      <div className="login-card"> 
        <h2 className="card-title">Login</h2>
        <form onSubmit={handleSubmit}>
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