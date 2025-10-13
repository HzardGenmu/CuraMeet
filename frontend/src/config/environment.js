const config = {
  development: {
    apiBaseUrl: "http://api.curameet.duckdns.org/api",
  },
  production: {
    apiBaseUrl: "http://api.curameet.duckdns.org/api",
  },
  test: {
    apiBaseUrl: "http://api.curameet.duckdns.org/api",
  },
};

const environment = process.env.NODE_ENV || "development";

export default config[environment];
