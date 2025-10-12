import React, { useState, useEffect } from "react";
import { Link, useNavigate } from "react-router-dom";
import { authService } from "../../services/authService";
import "./Login.css";

const Login = () => {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [role, setRole] = useState("patient");
  const [rememberMe, setRememberMe] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  const navigate = useNavigate();

  // Check if already authenticated
  useEffect(() => {
    if (authService.isAuthenticated()) {
      const user = authService.getCurrentUser();
      redirectBasedOnRole(user.role);
    }
  }, []);

  const redirectBasedOnRole = (userRole) => {
    switch (userRole) {
      case "admin":
        navigate("/admin/dashboard");
        break;
      case "doctor":
        navigate("/doctor/dashboard");
        break;
      default:
        navigate("/janji-temu");
    }
  };

  // Ganti baris 20-35 dengan ini:
  const handleSubmit = async (event) => {
    event.preventDefault();
    setLoading(true);
    setError("");

    try {
      const response = await authService.login(
        email,
        password,
        role,
        rememberMe
      );

      console.log("Login response:", response); // 🔍 Debug log

      if (response.success === true) {
        // ✅ Perbaikan kondisi
        // Store both user and token
        if (response.user) {
          localStorage.setItem("userInfo", JSON.stringify(response.user));
        }
        if (response.token) {
          localStorage.setItem("authToken", response.token);
        }

        console.log("Stored - Token:", localStorage.getItem("authToken"));
        console.log("Stored - User:", localStorage.getItem("userInfo"));

        switch (role) {
          case "admin":
            navigate("/admin/dashboard");
            break;
          case "doctor":
            navigate("/doctor/dashboard");
            break;
          default:
            navigate("/janji-temu");
        }
      } else {
        setError(response.message || "Login gagal");
      }
    } catch (error) {
      console.error("Login error:", error);
      setError("Terjadi kesalahan saat login");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="login-container">
      <h1 className="main-title">CuraMeet</h1>
      <div className="login-card">
        <h2 className="card-title">Login</h2>
        <form
          onSubmit={handleSubmit}
          className="login-form"
          aria-disabled={loading}
        >
          {error && (
            <div className="error-message" role="alert" tabIndex={-1}>
              <span>⚠️ {error}</span>
            </div>
          )}

          <div className="form-group">
            <label htmlFor="email">
              Email<span className="required-star">*</span>
            </label>
            <input
              type="email"
              id="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
              disabled={loading}
              autoComplete="username"
              placeholder="Enter your email"
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
              autoComplete="current-password"
              placeholder="Enter your password"
            />
          </div>

          <div className="form-group">
            <label htmlFor="role">
              Role<span className="required-star">*</span>
            </label>
            <select
              id="role"
              value={role}
              onChange={(e) => setRole(e.target.value)}
              required
              disabled={loading}
            >
              <option value="patient">Patient</option>
              <option value="doctor">Doctor</option>
              <option value="admin">Admin</option>
            </select>
          </div>

          <div className="form-group checkbox-group">
            <label>
              <input
                type="checkbox"
                checked={rememberMe}
                onChange={(e) => setRememberMe(e.target.checked)}
                disabled={loading}
              />
              Remember me
            </label>
          </div>

          <button type="submit" className="login-button" disabled={loading}>
            {loading ? (
              <span>
                <span className="spinner" /> Logging in...
              </span>
            ) : (
              "Login"
            )}
          </button>
        </form>
        <Link to="/register" className="login-link" tabIndex={loading ? -1 : 0}>
          Don't have an account? Register
        </Link>
        <Link
          to="/reset-password"
          className="login-link"
          tabIndex={loading ? -1 : 0}
        >
          Forgot password?
        </Link>
      </div>
    </div>
  );
};

export default Login;
