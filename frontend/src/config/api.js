// Ganti dari HTTPS ke HTTP untuk development
const API_BASE_URL = "http://backend.test/api";

export const apiConfig = {
  baseURL: API_BASE_URL,
  timeout: 10000,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
};

export default API_BASE_URL;
