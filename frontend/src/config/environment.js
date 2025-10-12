const config = {
  development: {
    apiBaseUrl: "http://localhost:8000/api",
  },
  production: {
    apiBaseUrl: "http://api.curameet.duckdns.org/api",
  },
  test: {
    apiBaseUrl: "http://localhost:8000/api",
  },
};

const environment = process.env.NODE_ENV || "development";

export default config[environment];
