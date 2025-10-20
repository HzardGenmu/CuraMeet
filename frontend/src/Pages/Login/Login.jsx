import React, { useState, useEffect } from "react";
import { Link, useNavigate } from "react-router-dom";
import { authService } from "../../services/authService";

const Login = () => {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [role, setRole] = useState("patient");
  const [rememberMe, setRememberMe] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  const navigate = useNavigate();

  const redirectBasedOnRole = (userRole) => {
    switch (userRole) {
      case "admin":
        navigate("/admin/");
        break;
      case "doctor":
        navigate("/dokter/");
        break;
      default:
        navigate("/janji-temu");
    }
  };

  useEffect(() => {
    if (authService.isAuthenticated()) {
      const user = authService.getCurrentUser();
      if (user) {
        redirectBasedOnRole(user.role);
      }
    }
  }, [navigate]);

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

      if (response.success === true && response.user) {
        redirectBasedOnRole(response.user.role);
      } else {
        setError(
          response.message || "Login gagal. Periksa kembali kredensial Anda."
        );
      }
    } catch (err) {
      console.error("Login error:", err);
      const errorMessage =
        err.response?.data?.message || "Terjadi kesalahan pada server.";
      setError(errorMessage);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex flex-col items-center justify-center bg-emerald-300 p-4 box-border">
      <h1 className="text-6xl font-bold text-emerald-800 mb-8 mt-12 md:mt-0">
        CuraMeet
      </h1>
      <div className="w-full max-w-md bg-white rounded-xl shadow-xl p-10 text-center">
        <h2 className="text-3xl font-semibold text-gray-800 mb-6">Login</h2>
        <form
          onSubmit={handleSubmit}
          className="space-y-6"
          aria-disabled={loading}
          noValidate // Opsional: Menonaktifkan semua validasi HTML5 pada form
        >
          {error && (
            <div
              className="bg-red-100 text-red-700 border border-red-700 px-4 py-2 rounded mb-4 text-sm"
              role="alert"
            >
              <span>⚠️ {error}</span>
            </div>
          )}

          <div>
            <label
              htmlFor="email"
              className="block text-sm font-medium mb-2 text-gray-700 text-left"
            >
              Email<span className="text-red-500">*</span>
            </label>
            <input
              // ✅ PERUBAHAN: Mengubah 'type' dari "email" menjadi "text"
              // Ini akan menonaktifkan validasi format email bawaan browser.
              type="text"
              id="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
              disabled={loading}
              autoComplete="username"
              placeholder="Enter your email"
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base"
            />
          </div>

          <div>
            <label
              htmlFor="password"
              className="block text-sm font-medium mb-2 text-gray-700 text-left"
            >
              Password<span className="text-red-500">*</span>
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
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base"
            />
          </div>

          <div>
            <label
              htmlFor="role"
              className="block text-sm font-medium mb-2 text-gray-700 text-left"
            >
              Role<span className="text-red-500">*</span>
            </label>
            <select
              id="role"
              value={role}
              onChange={(e) => setRole(e.target.value)}
              required
              disabled={loading}
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base"
            >
              <option value="patient">Patient</option>
              <option value="doctor">Doctor</option>
              <option value="admin">Admin</option>
            </select>
          </div>

          <div className="flex items-center text-left">
            <input
              type="checkbox"
              id="rememberMe"
              checked={rememberMe}
              onChange={(e) => setRememberMe(e.target.checked)}
              disabled={loading}
              className="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
            />
            <label htmlFor="rememberMe" className="text-sm text-gray-700">
              Remember me
            </label>
          </div>

          <button
            type="submit"
            className="w-full py-3 px-4 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition ease-in-out duration-300 disabled:opacity-50"
            disabled={loading}
          >
            {loading ? "Logging in..." : "Login"}
          </button>
        </form>

        <div className="flex flex-col items-center mt-6 space-y-3">
          <Link
            to="/register"
            className="text-blue-600 hover:underline text-base font-medium"
          >
            Don't have an account? Register
          </Link>
          <Link
            to="/reset-password"
            className="text-blue-600 hover:underline text-base font-medium"
          >
            Forgot password?
          </Link>
        </div>
      </div>
    </div>
  );
};

export default Login;
