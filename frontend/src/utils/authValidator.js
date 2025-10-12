/**
 * Auth Validator Utility
 * Validates consistency of authentication data across localStorage and session
 */

export const authValidator = {
  /**
   * Get all auth-related data from localStorage
   */
  getAuthData: () => {
    return {
      authToken: localStorage.getItem("authToken"),
      userInfo: localStorage.getItem("userInfo"),
      rememberMe: localStorage.getItem("rememberMe"),
    };
  },

  /**
   * Parse user info if it exists
   */
  getParsedUserInfo: () => {
    const userInfo = localStorage.getItem("userInfo");
    try {
      return userInfo ? JSON.parse(userInfo) : null;
    } catch (error) {
      console.error("Error parsing userInfo:", error);
      return null;
    }
  },

  /**
   * Validate that auth token exists
   */
  hasValidToken: () => {
    const token = localStorage.getItem("authToken");
    return token !== null && token !== undefined && token !== "";
  },

  /**
   * Validate that user info exists and is properly formatted
   */
  hasValidUserInfo: () => {
    const userInfo = authValidator.getParsedUserInfo();
    return userInfo !== null && typeof userInfo === "object";
  },

  /**
   * Check if authentication is complete and consistent
   */
  isAuthComplete: () => {
    const hasToken = authValidator.hasValidToken();
    const hasUserInfo = authValidator.hasValidUserInfo();
    return hasToken && hasUserInfo;
  },

  /**
   * Get auth status with detailed information
   */
  getAuthStatus: () => {
    const authData = authValidator.getAuthData();
    const userInfo = authValidator.getParsedUserInfo();

    return {
      isAuthenticated: authValidator.isAuthComplete(),
      hasToken: authValidator.hasValidToken(),
      hasUserInfo: authValidator.hasValidUserInfo(),
      tokenLength: authData.authToken?.length || 0,
      userRole: userInfo?.role || null,
      userId: userInfo?.id || null,
      userEmail: userInfo?.email || null,
      inconsistencies: authValidator.findInconsistencies(),
    };
  },

  /**
   * Find inconsistencies in auth data
   */
  findInconsistencies: () => {
    const inconsistencies = [];
    const hasToken = authValidator.hasValidToken();
    const hasUserInfo = authValidator.hasValidUserInfo();

    if (hasToken && !hasUserInfo) {
      inconsistencies.push("Token exists but userInfo is missing");
    }
    if (!hasToken && hasUserInfo) {
      inconsistencies.push("UserInfo exists but token is missing");
    }

    return inconsistencies;
  },

  /**
   * Clear all auth data from localStorage
   */
  clearAuthData: () => {
    localStorage.removeItem("authToken");
    localStorage.removeItem("userInfo");
    localStorage.removeItem("rememberMe");
  },

  /**
   * Validate token format (basic check)
   */
  validateTokenFormat: (token) => {
    if (!token || typeof token !== "string") return false;
    // Basic validation - check if it's not empty and has reasonable length
    return token.length > 10;
  },

  /**
   * Get debug info for logging
   */
  getDebugInfo: () => {
    const authData = authValidator.getAuthData();
    const userInfo = authValidator.getParsedUserInfo();
    
    return {
      timestamp: new Date().toISOString(),
      authToken: authData.authToken ? `${authData.authToken.substring(0, 10)}...` : null,
      userInfo: userInfo,
      localStorage: {
        itemCount: localStorage.length,
        keys: Object.keys(localStorage),
      },
    };
  },
};

export default authValidator;
