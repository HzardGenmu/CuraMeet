const config = {
  development: {
    apiBaseUrl: "http://localhost:9000/api",
  },
  production: {
    apiBaseUrl: "http://localhost:9000/api",
  },
  test: {
    apiBaseUrl: "http://localhost:9000/api",
  },
};

const environment = process.env.NODE_ENV || "development";

export default config[environment];
