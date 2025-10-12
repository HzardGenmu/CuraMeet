import React, { useState, useEffect } from "react";
import { authValidator } from "../../utils/authValidator";
import "./DebugPanel.css";

const DebugPanel = () => {
  const [authStatus, setAuthStatus] = useState(null);
  const [localStorageData, setLocalStorageData] = useState({});
  const [refreshInterval, setRefreshInterval] = useState(1000);
  const [isAutoRefresh, setIsAutoRefresh] = useState(true);

  const refreshData = () => {
    const status = authValidator.getAuthStatus();
    const authData = authValidator.getAuthData();
    const debugInfo = authValidator.getDebugInfo();

    setAuthStatus(status);
    setLocalStorageData({
      authToken: authData.authToken,
      userInfo: authData.userInfo,
      parsedUserInfo: authValidator.getParsedUserInfo(),
      debugInfo,
    });
  };

  useEffect(() => {
    refreshData();

    if (isAutoRefresh) {
      const interval = setInterval(refreshData, refreshInterval);
      return () => clearInterval(interval);
    }
  }, [isAutoRefresh, refreshInterval]);

  const handleClearAuth = () => {
    if (
      window.confirm(
        "Are you sure you want to clear all authentication data? This will log you out."
      )
    ) {
      authValidator.clearAuthData();
      refreshData();
      console.log("🔒 Auth data cleared");
    }
  };

  const getStatusIndicator = (isValid) => {
    return (
      <span className={`status-indicator ${isValid ? "valid" : "invalid"}`}>
        {isValid ? "✓" : "✗"}
      </span>
    );
  };

  return (
    <div className="debug-panel">
      <div className="debug-panel-header">
        <h2>🔍 Authentication Debug Panel</h2>
        <div className="debug-panel-controls">
          <label>
            <input
              type="checkbox"
              checked={isAutoRefresh}
              onChange={(e) => setIsAutoRefresh(e.target.checked)}
            />
            Auto-refresh
          </label>
          <select
            value={refreshInterval}
            onChange={(e) => setRefreshInterval(Number(e.target.value))}
            disabled={!isAutoRefresh}
          >
            <option value={500}>0.5s</option>
            <option value={1000}>1s</option>
            <option value={2000}>2s</option>
            <option value={5000}>5s</option>
          </select>
          <button onClick={refreshData} className="btn-refresh">
            Refresh
          </button>
          <button onClick={handleClearAuth} className="btn-clear">
            Clear Auth
          </button>
        </div>
      </div>

      <div className="debug-panel-content">
        {/* Auth Status Section */}
        <section className="debug-section">
          <h3>Auth Status</h3>
          <div className="debug-grid">
            <div className="debug-item">
              <span className="label">Authenticated:</span>
              <span className="value">
                {getStatusIndicator(authStatus?.isAuthenticated)}
                {authStatus?.isAuthenticated ? "Yes" : "No"}
              </span>
            </div>
            <div className="debug-item">
              <span className="label">Has Token:</span>
              <span className="value">
                {getStatusIndicator(authStatus?.hasToken)}
                {authStatus?.hasToken ? "Yes" : "No"}
              </span>
            </div>
            <div className="debug-item">
              <span className="label">Has User Info:</span>
              <span className="value">
                {getStatusIndicator(authStatus?.hasUserInfo)}
                {authStatus?.hasUserInfo ? "Yes" : "No"}
              </span>
            </div>
            <div className="debug-item">
              <span className="label">Token Length:</span>
              <span className="value">{authStatus?.tokenLength || 0}</span>
            </div>
          </div>
        </section>

        {/* User Info Section */}
        <section className="debug-section">
          <h3>User Information</h3>
          <div className="debug-grid">
            <div className="debug-item">
              <span className="label">User ID:</span>
              <span className="value">{authStatus?.userId || "N/A"}</span>
            </div>
            <div className="debug-item">
              <span className="label">Email:</span>
              <span className="value">{authStatus?.userEmail || "N/A"}</span>
            </div>
            <div className="debug-item">
              <span className="label">Role:</span>
              <span className="value">
                <span className={`role-badge role-${authStatus?.userRole}`}>
                  {authStatus?.userRole || "N/A"}
                </span>
              </span>
            </div>
          </div>
        </section>

        {/* Inconsistencies Section */}
        {authStatus?.inconsistencies?.length > 0 && (
          <section className="debug-section warning">
            <h3>⚠️ Inconsistencies Detected</h3>
            <ul className="inconsistencies-list">
              {authStatus.inconsistencies.map((issue, index) => (
                <li key={index}>{issue}</li>
              ))}
            </ul>
          </section>
        )}

        {/* LocalStorage Details */}
        <section className="debug-section">
          <h3>LocalStorage Data</h3>
          <div className="debug-item full-width">
            <span className="label">Auth Token:</span>
            <div className="value code">
              {localStorageData.authToken ? (
                <code>
                  {localStorageData.authToken.substring(0, 50)}
                  {localStorageData.authToken.length > 50 ? "..." : ""}
                </code>
              ) : (
                <span className="empty">null</span>
              )}
            </div>
          </div>
          <div className="debug-item full-width">
            <span className="label">User Info (raw):</span>
            <div className="value code">
              {localStorageData.userInfo ? (
                <pre>{localStorageData.userInfo}</pre>
              ) : (
                <span className="empty">null</span>
              )}
            </div>
          </div>
          <div className="debug-item full-width">
            <span className="label">User Info (parsed):</span>
            <div className="value code">
              {localStorageData.parsedUserInfo ? (
                <pre>{JSON.stringify(localStorageData.parsedUserInfo, null, 2)}</pre>
              ) : (
                <span className="empty">null</span>
              )}
            </div>
          </div>
        </section>

        {/* Debug Info Section */}
        <section className="debug-section">
          <h3>Debug Information</h3>
          <div className="debug-item full-width">
            <div className="value code">
              <pre>{JSON.stringify(localStorageData.debugInfo, null, 2)}</pre>
            </div>
          </div>
        </section>
      </div>
    </div>
  );
};

export default DebugPanel;
