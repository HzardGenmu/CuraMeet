// authService.js

import apiClient from "./apiService";

// Fungsi helper untuk parsing respons yang mungkin corrupt
const parseResponse = (response) => {
  if (typeof response.data === "string") {
    const jsonMatch = response.data.match(/(\{.*\})/s); // 's' flag for multiline
    if (jsonMatch && jsonMatch[1]) {
      try {
        return JSON.parse(jsonMatch[1]);
      } catch (e) {
        console.error("Failed to parse JSON from string response:", e);
        throw new Error("Invalid response format from server.");
      }
    } else {
      console.error("No valid JSON object found in string response.");
      throw new Error("Invalid response format from server.");
    }
  }
  return response.data;
};

export const authService = {
  login: async (email, password, role, rememberMe = false) => {
    const response = await apiClient.post("/auth/login", {
      email,
      password,
      role,
      remember_me: rememberMe,
    });

    const responseData = parseResponse(response);

    if (responseData.success === true) {
      // PENYESUAIAN: Dibuat lebih robust, karena standar Laravel seringkali `access_token`.
      const token = responseData.access_token || responseData.token;
      if (token) {
        localStorage.setItem("authToken", token);
      }
      if (responseData.user) {
        localStorage.setItem("userInfo", JSON.stringify(responseData.user));
      }
    }
    return responseData;
  },

  register: async (userData) => {
    const response = await apiClient.post("/auth/register", userData);
    return parseResponse(response);
  },

  resetPassword: async (email, newPassword) => {
    // FIX: Endpoint disesuaikan dengan api.php -> prefix('auth')
    const response = await apiClient.post("/auth/password/reset", {
      email,
      new_password: newPassword,
    });
    return parseResponse(response);
  },

  logout: async () => {
    try {
      const token = localStorage.getItem("authToken");
      // Kirim token jika backend butuh
      await apiClient.post(
        "/auth/logout",
        {},
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );
    } catch (error) {
      console.error(
        "Backend logout failed, proceeding with local logout:",
        error
      );
    } finally {
      localStorage.removeItem("authToken");
      localStorage.removeItem("userInfo");
    }
  },

  refreshToken: async () => {
    try {
      // FIX: Endpoint disesuaikan dengan api.php -> prefix('auth')
      const response = await apiClient.post("/auth/token/refresh");
      const responseData = parseResponse(response);

      const token = responseData.access_token || responseData.token;
      if (responseData.success && token) {
        localStorage.setItem("authToken", token);
        return responseData;
      }
      throw new Error("Token refresh failed logically.");
    } catch (error) {
      console.error("Token refresh error:", error);
      await authService.logfout(); // Await logout to ensure it completes
      throw error;
    }
  },

  // --- Utility Functions (No changes needed, already good) ---
  isAuthenticated: () => {
    const token = localStorage.getItem("authToken");
    return !!token;
  },

  getCurrentUser: () => {
    try {
      const userInfoRaw = localStorage.getItem("userInfo");
      return userInfoRaw ? JSON.parse(userInfoRaw) : null;
    } catch (error) {
      console.error("Error parsing user info:", error);
      return null;
    }
  },

  getToken: () => {
    return localStorage.getItem("authToken");
  },

  hasRole: (requiredRole) => {
    const user = authService.getCurrentUser();
    return user?.role === requiredRole;
  },

  hasAnyRole: (roles) => {
    const user = authService.getCurrentUser();
    return roles.includes(user?.role);
  },
  getRole: () => {
    const user = authService.getCurrentUser();
    return user?.role || null;
  },
};
