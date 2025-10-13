import apiClient from "./apiService";

export const authService = {
  // ✅ LOGIN - Already fixed
  login: async (email, password, role, rememberMe = false) => {
    const response = await apiClient.post("/login", {
      email,
      password,
      role,
      remember_me: rememberMe,
    });

    console.log("Raw API Response:", response.data);

    // ✅ FIX: Parse corrupted response from backend
    let responseData;

    if (typeof response.data === "string") {
      // Backend returns concatenated string, extract JSON part
      const jsonMatch = response.data.match(/(\{.*\})/);
      if (jsonMatch) {
        try {
          responseData = JSON.parse(jsonMatch[1]);
          console.log("Parsed Response:", responseData);
        } catch (e) {
          console.error("Failed to parse response:", e);
          return { success: false, message: "Invalid response format" };
        }
      } else {
        console.error("No JSON found in response");
        return { success: false, message: "Invalid response format" };
      }
    } else {
      responseData = response.data;
    }

    if (responseData.success === true) {
      if (responseData.token) {
        localStorage.setItem("authToken", responseData.token);
        console.log("Token saved:", responseData.token);
      }
      if (responseData.user) {
        localStorage.setItem("userInfo", JSON.stringify(responseData.user));
        console.log("User saved:", responseData.user);
      }
    }

    return responseData;
  },

  // ✅ REGISTER - Missing function
  register: async (userData) => {
    try {
      const response = await apiClient.post("/register", userData);

      console.log("Raw Register Response:", response.data);

      // Same parsing logic as login for corrupted response
      let responseData;

      if (typeof response.data === "string") {
        const jsonMatch = response.data.match(/(\{.*\})/);
        if (jsonMatch) {
          try {
            responseData = JSON.parse(jsonMatch[1]);
            console.log("Parsed Register Response:", responseData);
          } catch (e) {
            console.error("Failed to parse register response:", e);
            return { success: false, message: "Invalid response format" };
          }
        } else {
          console.error("No JSON found in register response");
          return { success: false, message: "Invalid response format" };
        }
      } else {
        responseData = response.data;
      }

      // Don't auto-login after register, user should login manually
      return responseData;
    } catch (error) {
      console.error("Register error:", error);
      return {
        success: false,
        message: error.response?.data?.message || "Registration failed",
      };
    }
  },

  // ✅ RESET PASSWORD - Missing function
  resetPassword: async (email, newPassword) => {
    try {
      const response = await apiClient.post("/reset-password", {
        email,
        new_password: newPassword,
      });

      // Same parsing logic for corrupted response
      let responseData;

      if (typeof response.data === "string") {
        const jsonMatch = response.data.match(/(\{.*\})/);
        if (jsonMatch) {
          try {
            responseData = JSON.parse(jsonMatch[1]);
          } catch (e) {
            console.error("Failed to parse reset password response:", e);
            return { success: false, message: "Invalid response format" };
          }
        } else {
          return { success: false, message: "Invalid response format" };
        }
      } else {
        responseData = response.data;
      }

      return responseData;
    } catch (error) {
      console.error("Reset password error:", error);
      return {
        success: false,
        message: error.response?.data?.message || "Reset password failed",
      };
    }
  },

  // ✅ LOGOUT - Missing function
  logout: async () => {
    try {
      // Try to call backend logout endpoint
      await apiClient.post("/logout");
    } catch (error) {
      console.error("Logout error:", error);
      // Continue with local logout even if backend fails
    } finally {
      // Always clear local storage
      localStorage.removeItem("authToken");
      localStorage.removeItem("userInfo");
      localStorage.removeItem("isAuthenticated");

      console.log("User logged out successfully");
    }
  },

  // ✅ CHECK AUTHENTICATION - New utility function
  isAuthenticated: () => {
    const token = localStorage.getItem("authToken");
    const userInfo = localStorage.getItem("userInfo");

    return !!(token && userInfo);
  },

  // ✅ GET CURRENT USER - New utility function
  getCurrentUser: () => {
    try {
      const userInfoRaw = localStorage.getItem("userInfo");
      return userInfoRaw ? JSON.parse(userInfoRaw) : null;
    } catch (error) {
      console.error("Error parsing user info:", error);
      return null;
    }
  },

  // ✅ GET TOKEN - New utility function
  getToken: () => {
    return localStorage.getItem("authToken");
  },

  // ✅ REFRESH TOKEN - For future use
  refreshToken: async () => {
    try {
      const currentToken = localStorage.getItem("authToken");
      if (!currentToken) {
        throw new Error("No token available");
      }

      const response = await apiClient.post("/refresh-token");

      // Parse response (same logic as other methods)
      let responseData;
      if (typeof response.data === "string") {
        const jsonMatch = response.data.match(/(\{.*\})/);
        if (jsonMatch) {
          responseData = JSON.parse(jsonMatch[1]);
        } else {
          throw new Error("Invalid response format");
        }
      } else {
        responseData = response.data;
      }

      if (responseData.success && responseData.token) {
        localStorage.setItem("authToken", responseData.token);
        return responseData;
      }

      throw new Error("Token refresh failed");
    } catch (error) {
      console.error("Token refresh error:", error);
      // If refresh fails, logout user
      this.logout();
      throw error;
    }
  },

  // ✅ CHECK USER ROLE - New utility function
  hasRole: (requiredRole) => {
    const user = authService.getCurrentUser();
    return user?.role === requiredRole;
  },

  // ✅ CHECK MULTIPLE ROLES - New utility function
  hasAnyRole: (roles) => {
    const user = authService.getCurrentUser();
    return roles.includes(user?.role);
  },
};
