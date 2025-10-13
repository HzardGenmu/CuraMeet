import React, { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { authService } from "../../services/authService";
import "./Register.css";

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
      if (response.success != false) {
        alert("Registrasi berhasil.");
        navigate("/login");
      } else {
        setError(response.errors || "Registrasi gagal.");
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
    <div className="register-container">
      <h1 className="main-title">CuraMeet</h1>
      <div className="register-card">
        <h2 className="card-title">Register</h2>
        <form onSubmit={handleSubmit}>
          {error && (
            <div className="error-message" role="alert" tabIndex={-1}>
              <span>⚠️ {error}</span>
            </div>
          )}
          <div className="form-group">
            <label htmlFor="nama">
              Nama<span className="required-star">*</span>
            </label>
            <input
              type="text"
              id="nama"
              value={nama}
              onChange={(e) => setNama(e.target.value)}
              required
              disabled={loading}
            />
          </div>
          <div className="form-group">
            <label htmlFor="nik">
              NIK<span className="required-star">*</span>
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
            />
          </div>
          <div className="form-group">
            <label htmlFor="email">
              E-mail<span className="required-star">*</span>
            </label>
            <input
              type="email"
              id="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
              disabled={loading}
            />
          </div>
          <div className="form-group">
            <label htmlFor="password">
              Password<span className="required-star">*</span>
            </label>
            <input
              type="password"
              id="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
              disabled={loading}
            />
          </div>
          <div className="form-group">
            <label htmlFor="confirmPassword">
              Konfirmasi Password<span className="required-star">*</span>
            </label>
            <input
              type="password"
              id="confirmPassword"
              value={confirmPassword}
              onChange={(e) => setConfirmPassword(e.target.value)}
              required
              disabled={loading}
            />
          </div>
          <button type="submit" className="register-button" disabled={loading}>
            {loading ? "Mendaftar..." : "Register"}
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
