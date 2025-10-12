import apiClient from "./apiService";

export const authService = {
  // Login
  login: async (email, password, role, rememberMe = false) => {
    const response = await apiClient.post("/login", {
      email,
      password,
      role,
      remember_me: rememberMe,
    });

    if (response.data.success) {
      // Store authentication token if provided
      const token = response.data.token;
      if (token) {
        localStorage.setItem("authToken", token);
      }
    }

    return response.data;
  },

  // Register
  register: async (userData) => {
    const response = await apiClient.post("/register", userData);
    return response.data;
  },

  // Reset Password
  resetPassword: async (email, newPassword) => {
    const response = await apiClient.post("/reset-password", {
      email,
      new_password: newPassword,
    });
    return response.data;
  },

  // Logout
  logout: async () => {
    const response = await apiClient.post("/logout");
    localStorage.removeItem("authToken");
    return response.data;
  },
};
