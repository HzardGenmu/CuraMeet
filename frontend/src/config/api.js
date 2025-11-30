// Ganti dari HTTPS ke HTTP untuk development
const API_BASE_URL = "https://api.curameet-secure.duckdns.org/api";

export const apiConfig = {
  baseURL: API_BASE_URL,
  timeout: 10000,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
};

export default API_BASE_URL;
