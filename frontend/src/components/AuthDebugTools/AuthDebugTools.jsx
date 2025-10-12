import React, { useState } from "react";
import AuthTestingComponent from "./AuthTestingComponent";
import DebugPanel from "./DebugPanel";
import NetworkInspector from "./NetworkInspector";
import "./AuthDebugTools.css";

const AuthDebugTools = () => {
  const [activeTab, setActiveTab] = useState("testing");

  return (
    <div className="auth-debug-tools">
      <div className="auth-debug-tools-header">
        <h1>🔐 Authentication Debug Tools</h1>
        <p className="subtitle">
          Developer tools for testing and debugging authentication system
        </p>
      </div>

      <div className="auth-debug-tools-tabs">
        <button
          className={`tab-button ${activeTab === "testing" ? "active" : ""}`}
          onClick={() => setActiveTab("testing")}
        >
          🧪 Testing
        </button>
        <button
          className={`tab-button ${activeTab === "debug" ? "active" : ""}`}
          onClick={() => setActiveTab("debug")}
        >
          🔍 Debug Panel
        </button>
        <button
          className={`tab-button ${activeTab === "network" ? "active" : ""}`}
          onClick={() => setActiveTab("network")}
        >
          🌐 Network
        </button>
      </div>

      <div className="auth-debug-tools-content">
        {activeTab === "testing" && <AuthTestingComponent />}
        {activeTab === "debug" && <DebugPanel />}
        {activeTab === "network" && <NetworkInspector />}
      </div>
    </div>
  );
};

export default AuthDebugTools;
