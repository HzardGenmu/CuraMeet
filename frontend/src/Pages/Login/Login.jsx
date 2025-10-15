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
    
    <div className="min-h-screen flex flex-col items-center justify-center bg-emerald-300 p-4 box-border"> {/* Warna hijau muda disesuaikan */}
     
      <h1 className="text-6xl font-bold text-emerald-800 mb-8 mt-12 md:mt-0">CuraMeet</h1> {/* Ukuran dan warna disesuaikan */}

      
      <div className="w-full max-w-md bg-white rounded-xl shadow-xl p-10 text-center custom-shadow"> {/* custom-shadow akan dibuat di tailwind.config.js */}
        
        <h2 className="text-3xl font-semibold text-gray-800 mb-6">Login</h2> {/* Ukuran dan warna disesuaikan */}

        <form
          onSubmit={handleSubmit}
          className="space-y-6" 
          aria-disabled={loading}
        >
          {error && (
            
            <div className="bg-red-100 text-red-700 border border-red-700 px-4 py-2 rounded mb-4 text-sm flex items-center" role="alert" tabIndex={-1}>
              <span>⚠️ {error}</span>
            </div>
          )}

          
          <div>
            
            <label htmlFor="email" className="block text-sm font-medium mb-2 text-gray-700 text-left">
              Email<span className="text-red-500">*</span>
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
              autoComplete="current-password"
              placeholder="Enter your password"
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base"
            />
          </div>

          <div>
            <label htmlFor="role" className="block text-sm font-medium mb-2 text-gray-700 text-left">
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

          
          <div className="flex items-center text-left"> {/* text-left ditambahkan untuk label */}
            <input
              type="checkbox"
              checked={rememberMe}
              onChange={(e) => setRememberMe(e.target.checked)}
              disabled={loading}
              className="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
            />
            <label className="text-sm text-gray-700">Remember me</label>
          </div>

         
          <button
            type="submit"
            className="w-full py-3 px-4 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition ease-in-out duration-300 disabled:opacity-50 disabled:cursor-not-allowed text-lg" // Ukuran font dan transisi disesuaikan
            disabled={loading}
          >
            {loading ? (
              <span>
                
                <span className="inline-block w-5 h-5 mr-3 border-2 border-blue-200 border-t-blue-600 rounded-full animate-spin align-middle" /> Logging in...
              </span>
            ) : (
              "Login"
            )}
          </button>
        </form>

        <div className="flex flex-col items-center mt-6 space-y-3"> {/* space-y-2 menjadi space-y-3 dan mt-4 menjadi mt-6 */}
          
          <Link to="/register" className="text-blue-600 hover:underline text-base font-medium" tabIndex={loading ? -1 : 0}>
            Don't have an account? Register
          </Link>
          <Link
            to="/reset-password"
            className="text-blue-600 hover:underline text-base font-medium"
            tabIndex={loading ? -1 : 0}
          >
            Forgot password?
          </Link>
        </div>
      </div>
    </div>
  );
};

export default Login;