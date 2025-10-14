const config = {
  development: {
    apiBaseUrl: "https://api.curameet.duckdns.org/api",
  },
  production: {
    apiBaseUrl: "https://api.curameet.duckdns.org/api",
  },
  test: {
    apiBaseUrl: "https://api.curameet.duckdns.org/api",
  },
};

const environment = process.env.NODE_ENV || "development";

export default config[environment];
