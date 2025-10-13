const config = {
  development: {
    apiBaseUrl: "http://backend.test/api",
  },
  production: {
    apiBaseUrl: "https://backend.test/api",
  },
  test: {
    apiBaseUrl: "https://backend.test/api",
  },
};

const environment = process.env.NODE_ENV || "development";

export default config[environment];
