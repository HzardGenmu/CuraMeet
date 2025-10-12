import { useState, useCallback } from "react";

export const useApi = () => {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const callApi = useCallback(async (apiFunction, ...args) => {
    try {
      setLoading(true);
      setError(null);
      const result = await apiFunction(...args);
      return result;
    } catch (error) {
      console.error("API Error:", error);
      setError(
        error.response?.data?.message || error.message || "An error occurred"
      );
      throw error;
    } finally {
      setLoading(false);
    }
  }, []);

  return { loading, error, callApi };
};
