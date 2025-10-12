import React, { useState, useEffect } from "react";
import axios from "axios";
import "./NetworkInspector.css";

const NetworkInspector = () => {
  const [requests, setRequests] = useState([]);
  const [filter, setFilter] = useState("all");
  const [maxRequests, setMaxRequests] = useState(50);

  useEffect(() => {
    // Intercept axios requests
    const requestInterceptor = axios.interceptors.request.use(
      (config) => {
        const timestamp = new Date().toISOString();
        const token = localStorage.getItem("authToken");
        
        const requestLog = {
          id: Date.now() + Math.random(),
          timestamp,
          method: config.method?.toUpperCase(),
          url: config.url,
          baseURL: config.baseURL,
          fullURL: `${config.baseURL || ""}${config.url || ""}`,
          hasAuthHeader: !!config.headers?.Authorization,
          authHeader: config.headers?.Authorization ? 
            `${config.headers.Authorization.substring(0, 20)}...` : null,
          hasToken: !!token,
          tokenPreview: token ? `${token.substring(0, 10)}...` : null,
          headers: config.headers,
          type: "request",
          status: "pending",
        };

        setRequests((prev) => {
          const updated = [requestLog, ...prev];
          return updated.slice(0, maxRequests);
        });

        // Store request id in config for response matching
        config._requestId = requestLog.id;
        
        return config;
      },
      (error) => {
        console.error("Request interceptor error:", error);
        return Promise.reject(error);
      }
    );

    // Intercept axios responses
    const responseInterceptor = axios.interceptors.response.use(
      (response) => {
        const requestId = response.config._requestId;
        
        setRequests((prev) =>
          prev.map((req) =>
            req.id === requestId
              ? {
                  ...req,
                  status: "success",
                  statusCode: response.status,
                  responseData: response.data,
                  responseTime: new Date().toISOString(),
                }
              : req
          )
        );

        return response;
      },
      (error) => {
        const requestId = error.config?._requestId;
        
        setRequests((prev) =>
          prev.map((req) =>
            req.id === requestId
              ? {
                  ...req,
                  status: "error",
                  statusCode: error.response?.status,
                  errorMessage: error.message,
                  responseTime: new Date().toISOString(),
                }
              : req
          )
        );

        return Promise.reject(error);
      }
    );

    return () => {
      axios.interceptors.request.eject(requestInterceptor);
      axios.interceptors.response.eject(responseInterceptor);
    };
  }, [maxRequests]);

  const clearRequests = () => {
    setRequests([]);
  };

  const filteredRequests = requests.filter((req) => {
    if (filter === "all") return true;
    if (filter === "auth") return req.hasAuthHeader;
    if (filter === "no-auth") return !req.hasAuthHeader;
    if (filter === "success") return req.status === "success";
    if (filter === "error") return req.status === "error";
    return true;
  });

  const getStatusBadge = (status, statusCode) => {
    let className = "status-badge";
    if (status === "success") className += " success";
    else if (status === "error") className += " error";
    else className += " pending";

    return (
      <span className={className}>
        {status.toUpperCase()}
        {statusCode && ` (${statusCode})`}
      </span>
    );
  };

  const getMethodBadge = (method) => {
    const className = `method-badge method-${method?.toLowerCase()}`;
    return <span className={className}>{method}</span>;
  };

  return (
    <div className="network-inspector">
      <div className="network-inspector-header">
        <h2>🌐 Network Inspector</h2>
        <div className="network-inspector-controls">
          <select value={filter} onChange={(e) => setFilter(e.target.value)}>
            <option value="all">All Requests</option>
            <option value="auth">With Auth Header</option>
            <option value="no-auth">Without Auth Header</option>
            <option value="success">Successful</option>
            <option value="error">Failed</option>
          </select>
          <select
            value={maxRequests}
            onChange={(e) => setMaxRequests(Number(e.target.value))}
          >
            <option value={25}>Last 25</option>
            <option value={50}>Last 50</option>
            <option value={100}>Last 100</option>
          </select>
          <button onClick={clearRequests} className="btn-clear">
            Clear All
          </button>
        </div>
      </div>

      <div className="network-inspector-content">
        <div className="requests-summary">
          <div className="summary-item">
            <span className="summary-label">Total:</span>
            <span className="summary-value">{requests.length}</span>
          </div>
          <div className="summary-item">
            <span className="summary-label">With Auth:</span>
            <span className="summary-value">
              {requests.filter((r) => r.hasAuthHeader).length}
            </span>
          </div>
          <div className="summary-item">
            <span className="summary-label">Success:</span>
            <span className="summary-value success">
              {requests.filter((r) => r.status === "success").length}
            </span>
          </div>
          <div className="summary-item">
            <span className="summary-label">Failed:</span>
            <span className="summary-value error">
              {requests.filter((r) => r.status === "error").length}
            </span>
          </div>
        </div>

        <div className="requests-list">
          {filteredRequests.length === 0 ? (
            <div className="empty-state">
              <p>No network requests captured yet.</p>
              <p className="hint">
                Make some API calls to see them appear here.
              </p>
            </div>
          ) : (
            filteredRequests.map((req) => (
              <details key={req.id} className="request-item">
                <summary className="request-summary">
                  <div className="request-header">
                    <span className="request-time">
                      {new Date(req.timestamp).toLocaleTimeString()}
                    </span>
                    {getMethodBadge(req.method)}
                    <span className="request-url">{req.fullURL}</span>
                    {getStatusBadge(req.status, req.statusCode)}
                    {req.hasAuthHeader && (
                      <span className="auth-indicator" title="Has Authorization Header">
                        🔒
                      </span>
                    )}
                  </div>
                </summary>
                <div className="request-details">
                  <div className="detail-section">
                    <h4>Request Details</h4>
                    <div className="detail-grid">
                      <div className="detail-item">
                        <span className="detail-label">Method:</span>
                        <span className="detail-value">{req.method}</span>
                      </div>
                      <div className="detail-item">
                        <span className="detail-label">URL:</span>
                        <span className="detail-value">{req.fullURL}</span>
                      </div>
                      <div className="detail-item">
                        <span className="detail-label">Timestamp:</span>
                        <span className="detail-value">{req.timestamp}</span>
                      </div>
                      <div className="detail-item">
                        <span className="detail-label">Has Auth Header:</span>
                        <span className="detail-value">
                          {req.hasAuthHeader ? "✓ Yes" : "✗ No"}
                        </span>
                      </div>
                      {req.hasAuthHeader && (
                        <div className="detail-item full-width">
                          <span className="detail-label">Auth Header:</span>
                          <span className="detail-value code">
                            {req.authHeader}
                          </span>
                        </div>
                      )}
                      <div className="detail-item">
                        <span className="detail-label">Has Token in Storage:</span>
                        <span className="detail-value">
                          {req.hasToken ? "✓ Yes" : "✗ No"}
                        </span>
                      </div>
                      {req.hasToken && (
                        <div className="detail-item full-width">
                          <span className="detail-label">Token Preview:</span>
                          <span className="detail-value code">
                            {req.tokenPreview}
                          </span>
                        </div>
                      )}
                    </div>
                  </div>

                  {req.status !== "pending" && (
                    <div className="detail-section">
                      <h4>Response Details</h4>
                      <div className="detail-grid">
                        <div className="detail-item">
                          <span className="detail-label">Status:</span>
                          <span className="detail-value">
                            {getStatusBadge(req.status, req.statusCode)}
                          </span>
                        </div>
                        <div className="detail-item">
                          <span className="detail-label">Response Time:</span>
                          <span className="detail-value">{req.responseTime}</span>
                        </div>
                        {req.errorMessage && (
                          <div className="detail-item full-width">
                            <span className="detail-label">Error:</span>
                            <span className="detail-value error">
                              {req.errorMessage}
                            </span>
                          </div>
                        )}
                      </div>
                    </div>
                  )}

                  <div className="detail-section">
                    <h4>Headers</h4>
                    <pre className="code-block">
                      {JSON.stringify(req.headers, null, 2)}
                    </pre>
                  </div>

                  {req.responseData && (
                    <div className="detail-section">
                      <h4>Response Data</h4>
                      <pre className="code-block">
                        {JSON.stringify(req.responseData, null, 2)}
                      </pre>
                    </div>
                  )}
                </div>
              </details>
            ))
          )}
        </div>
      </div>
    </div>
  );
};

export default NetworkInspector;
