import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import './Register.css';

const Register = () => {
  // State untuk menyimpan nilai dari setiap input field
  const [nama, setNama] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  // Fungsi yang dijalankan saat form disubmit
  const handleSubmit = (event) => {
    event.preventDefault(); 
    console.log({
      nama,
      email,
      password,
    });
    alert('Registrasi berhasil.');
  };

  return (
    <div className="register-container">
      <h1 className="main-title">CuraMeet</h1>
      <div className="register-card">
        <h2 className="card-title">Register</h2>
        <form onSubmit={handleSubmit}>
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