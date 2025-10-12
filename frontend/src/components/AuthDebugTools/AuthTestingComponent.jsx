import React, { useState } from "react";
import { authService } from "../../services/authService";
import { authValidator } from "../../utils/authValidator";
import "./AuthTestingComponent.css";

const AuthTestingComponent = () => {
  const [testResults, setTestResults] = useState([]);
  const [isRunning, setIsRunning] = useState(false);
  const [selectedRole, setSelectedRole] = useState("patient");
  const [testCredentials, setTestCredentials] = useState({
    email: "test@example.com",
    password: "password123",
  });

  const addTestResult = (result) => {
    setTestResults((prev) => [
      {
        ...result,
        timestamp: new Date().toISOString(),
        id: Date.now() + Math.random(),
      },
      ...prev,
    ]);
  };

  const clearResults = () => {
    setTestResults([]);
  };

  // Test: Login Flow
  const testLoginFlow = async () => {
    setIsRunning(true);
    addTestResult({
      test: "Login Flow",
      status: "running",
      message: "Starting login test...",
    });

    try {
      const response = await authService.login(
        testCredentials.email,
        testCredentials.password,
        selectedRole,
        false
      );

      const authStatus = authValidator.getAuthStatus();

      if (response.success && authStatus.isAuthenticated) {
        addTestResult({
          test: "Login Flow",
          status: "success",
          message: "Login successful and auth data stored correctly",
          details: {
            hasToken: authStatus.hasToken,
            hasUserInfo: authStatus.hasUserInfo,
            role: authStatus.userRole,
          },
        });
      } else if (response.success) {
        addTestResult({
          test: "Login Flow",
          status: "warning",
          message: "Login API succeeded but auth data is incomplete",
          details: {
            hasToken: authStatus.hasToken,
            hasUserInfo: authStatus.hasUserInfo,
            inconsistencies: authStatus.inconsistencies,
          },
        });
      } else {
        addTestResult({
          test: "Login Flow",
          status: "error",
          message: response.message || "Login failed",
        });
      }
    } catch (error) {
      addTestResult({
        test: "Login Flow",
        status: "error",
        message: error.response?.data?.message || error.message,
      });
    }

    setIsRunning(false);
  };

  // Test: Logout Flow
  const testLogoutFlow = async () => {
    setIsRunning(true);
    addTestResult({
      test: "Logout Flow",
      status: "running",
      message: "Starting logout test...",
    });

    try {
      const beforeAuth = authValidator.getAuthStatus();

      await authService.logout();

      const afterAuth = authValidator.getAuthStatus();

      if (!afterAuth.hasToken && beforeAuth.hasToken) {
        addTestResult({
          test: "Logout Flow",
          status: "success",
          message: "Logout successful and token cleared",
          details: {
            beforeHasToken: beforeAuth.hasToken,
            afterHasToken: afterAuth.hasToken,
          },
        });
      } else {
        addTestResult({
          test: "Logout Flow",
          status: "warning",
          message: "Logout completed but token may not be fully cleared",
          details: {
            beforeHasToken: beforeAuth.hasToken,
            afterHasToken: afterAuth.hasToken,
          },
        });
      }
    } catch (error) {
      addTestResult({
        test: "Logout Flow",
        status: "error",
        message: error.message,
      });
    }

    setIsRunning(false);
  };

  // Test: Token Storage
  const testTokenStorage = () => {
    addTestResult({
      test: "Token Storage",
      status: "running",
      message: "Checking token storage...",
    });

    const token = localStorage.getItem("authToken");

    if (token && authValidator.validateTokenFormat(token)) {
      addTestResult({
        test: "Token Storage",
        status: "success",
        message: "Valid token found in localStorage",
        details: {
          tokenLength: token.length,
          tokenPreview: `${token.substring(0, 10)}...`,
        },
      });
    } else if (token) {
      addTestResult({
        test: "Token Storage",
        status: "warning",
        message: "Token exists but format may be invalid",
        details: {
          tokenLength: token.length,
        },
      });
    } else {
      addTestResult({
        test: "Token Storage",
        status: "info",
        message: "No token found in localStorage",
      });
    }
  };

  // Test: User Info Consistency
  const testUserInfoConsistency = () => {
    addTestResult({
      test: "User Info Consistency",
      status: "running",
      message: "Checking user info consistency...",
    });

    const authStatus = authValidator.getAuthStatus();

    if (authStatus.inconsistencies.length === 0 && authStatus.isAuthenticated) {
      addTestResult({
        test: "User Info Consistency",
        status: "success",
        message: "Auth data is consistent",
        details: authStatus,
      });
    } else if (authStatus.inconsistencies.length > 0) {
      addTestResult({
        test: "User Info Consistency",
        status: "error",
        message: "Inconsistencies detected in auth data",
        details: {
          inconsistencies: authStatus.inconsistencies,
        },
      });
    } else {
      addTestResult({
        test: "User Info Consistency",
        status: "info",
        message: "No authentication data found",
      });
    }
  };

  // Test: Session Persistence
  const testSessionPersistence = () => {
    addTestResult({
      test: "Session Persistence",
      status: "running",
      message: "Checking session persistence...",
    });

    const authData = authValidator.getAuthData();
    const hasToken = authData.authToken !== null;
    const hasUserInfo = authData.userInfo !== null;

    if (hasToken && hasUserInfo) {
      addTestResult({
        test: "Session Persistence",
        status: "success",
        message: "Session data persists in localStorage",
        details: {
          authToken: hasToken,
          userInfo: hasUserInfo,
        },
      });
    } else if (hasToken || hasUserInfo) {
      addTestResult({
        test: "Session Persistence",
        status: "warning",
        message: "Partial session data found",
        details: {
          authToken: hasToken,
          userInfo: hasUserInfo,
        },
      });
    } else {
      addTestResult({
        test: "Session Persistence",
        status: "info",
        message: "No session data found",
      });
    }
  };

  // Test: Role-based Data
  const testRoleBasedData = () => {
    addTestResult({
      test: "Role-based Data",
      status: "running",
      message: "Checking role-based data...",
    });

    const userInfo = authValidator.getParsedUserInfo();

    if (userInfo && userInfo.role) {
      const validRoles = ["patient", "doctor", "admin"];
      const isValidRole = validRoles.includes(userInfo.role);

      if (isValidRole) {
        addTestResult({
          test: "Role-based Data",
          status: "success",
          message: `Valid role found: ${userInfo.role}`,
          details: {
            role: userInfo.role,
            userId: userInfo.id,
          },
        });
      } else {
        addTestResult({
          test: "Role-based Data",
          status: "error",
          message: `Invalid role: ${userInfo.role}`,
          details: {
            role: userInfo.role,
            validRoles: validRoles,
          },
        });
      }
    } else {
      addTestResult({
        test: "Role-based Data",
        status: "info",
        message: "No user info or role found",
      });
    }
  };

  // Run all tests
  const runAllTests = async () => {
    clearResults();
    setIsRunning(true);

    testTokenStorage();
    testUserInfoConsistency();
    testSessionPersistence();
    testRoleBasedData();

    setIsRunning(false);
  };

  const getResultIcon = (status) => {
    switch (status) {
      case "success":
        return "✓";
      case "error":
        return "✗";
      case "warning":
        return "⚠";
      case "running":
        return "⟳";
      case "info":
      default:
        return "ℹ";
    }
  };

  return (
    <div className="auth-testing-component">
      <div className="auth-testing-header">
        <h2>🧪 Authentication Testing</h2>
      </div>

      <div className="auth-testing-content">
        {/* Test Configuration */}
        <section className="test-section">
          <h3>Test Configuration</h3>
          <div className="config-grid">
            <div className="config-item">
              <label>Test Email:</label>
              <input
                type="email"
                value={testCredentials.email}
                onChange={(e) =>
                  setTestCredentials({ ...testCredentials, email: e.target.value })
                }
                placeholder="test@example.com"
              />
            </div>
            <div className="config-item">
              <label>Test Password:</label>
              <input
                type="password"
                value={testCredentials.password}
                onChange={(e) =>
                  setTestCredentials({
                    ...testCredentials,
                    password: e.target.value,
                  })
                }
                placeholder="password"
              />
            </div>
            <div className="config-item">
              <label>Test Role:</label>
              <select
                value={selectedRole}
                onChange={(e) => setSelectedRole(e.target.value)}
              >
                <option value="patient">Patient</option>
                <option value="doctor">Doctor</option>
                <option value="admin">Admin</option>
              </select>
            </div>
          </div>
        </section>

        {/* Test Actions */}
        <section className="test-section">
          <h3>Test Actions</h3>
          <div className="test-actions">
            <button
              onClick={testLoginFlow}
              disabled={isRunning}
              className="btn-test"
            >
              Test Login Flow
            </button>
            <button
              onClick={testLogoutFlow}
              disabled={isRunning}
              className="btn-test"
            >
              Test Logout Flow
            </button>
            <button
              onClick={testTokenStorage}
              disabled={isRunning}
              className="btn-test"
            >
              Test Token Storage
            </button>
            <button
              onClick={testUserInfoConsistency}
              disabled={isRunning}
              className="btn-test"
            >
              Test User Info
            </button>
            <button
              onClick={testSessionPersistence}
              disabled={isRunning}
              className="btn-test"
            >
              Test Session Persistence
            </button>
            <button
              onClick={testRoleBasedData}
              disabled={isRunning}
              className="btn-test"
            >
              Test Role Data
            </button>
            <button
              onClick={runAllTests}
              disabled={isRunning}
              className="btn-test btn-primary"
            >
              Run All Tests
            </button>
            <button
              onClick={clearResults}
              disabled={isRunning}
              className="btn-test btn-secondary"
            >
              Clear Results
            </button>
          </div>
        </section>

        {/* Test Results */}
        <section className="test-section">
          <h3>Test Results ({testResults.length})</h3>
          <div className="test-results">
            {testResults.length === 0 ? (
              <div className="empty-state">
                <p>No test results yet.</p>
                <p className="hint">Run some tests to see results here.</p>
              </div>
            ) : (
              testResults.map((result) => (
                <div key={result.id} className={`test-result ${result.status}`}>
                  <div className="result-header">
                    <span className="result-icon">{getResultIcon(result.status)}</span>
                    <span className="result-test">{result.test}</span>
                    <span className={`result-status ${result.status}`}>
                      {result.status.toUpperCase()}
                    </span>
                    <span className="result-time">
                      {new Date(result.timestamp).toLocaleTimeString()}
                    </span>
                  </div>
                  <div className="result-message">{result.message}</div>
                  {result.details && (
                    <details className="result-details">
                      <summary>View Details</summary>
                      <pre>{JSON.stringify(result.details, null, 2)}</pre>
                    </details>
                  )}
                </div>
              ))
            )}
          </div>
        </section>
      </div>
    </div>
  );
};

export default AuthTestingComponent;
