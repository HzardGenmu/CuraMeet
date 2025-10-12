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
  // Check if user is authenticated
  isAuthenticated: () => {
    const token = localStorage.getItem("authToken");
    const userInfo = localStorage.getItem("userInfo");

    return token && userInfo;
  },

  // Get current user
  getCurrentUser: () => {
    const userInfoRaw = localStorage.getItem("userInfo");
    return userInfoRaw ? JSON.parse(userInfoRaw) : null;
  },

  // Logout
  logout: async () => {
    try {
      await apiClient.post("/logout");
    } catch (error) {
      console.error("Logout error:", error);
    } finally {
      // Clear all auth data
      localStorage.removeItem("authToken");
      localStorage.removeItem("userInfo");
      localStorage.removeItem("isAuthenticated");
    }
  },
};
