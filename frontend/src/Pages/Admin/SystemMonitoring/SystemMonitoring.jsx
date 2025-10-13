import React, { useState, useEffect } from 'react';
import { Line, Doughnut } from 'react-chartjs-2';
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, ArcElement, Tooltip, Legend, Title } from 'chart.js';
import { IoAlertCircleOutline, IoRocketOutline, IoPulseOutline, IoCodeSlashOutline, IoServerOutline } from 'react-icons/io5';
import './SystemMonitoring.css';

// Register Chart.js components
ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, ArcElement, Tooltip, Legend, Title);

const SystemMonitoring = () => {
  const [activeTab, setActiveTab] = useState('api'); // 'api', 'backend', 'anomaly'
  const [apiData, setApiData] = useState([]);
  const [backendHealth, setBackendHealth] = useState({});
  const [anomalyDetection, setAnomalyDetection] = useState([]);

  // --- Dummy Data Generation ---
  useEffect(() => {
    const generateApiData = () => {
      const labels = Array.from({ length: 10 }, (_, i) => `${i * 5}m ago`);
      const successRates = Array.from({ length: 10 }, () => Math.floor(Math.random() * (100 - 90 + 1)) + 90);
      const errorCounts = Array.from({ length: 10 }, () => Math.floor(Math.random() * 5));
      const avgLatencies = Array.from({ length: 10 }, () => Math.floor(Math.random() * (200 - 50 + 1)) + 50);

      setApiData({ labels, successRates, errorCounts, avgLatencies });
    };

    const generateBackendHealth = () => {
      setBackendHealth({
        cpuUsage: (Math.random() * (80 - 20) + 20).toFixed(1),
        memoryUsage: (Math.random() * (70 - 30) + 30).toFixed(1),
        diskUsage: (Math.random() * (60 - 40) + 40).toFixed(1),
        dbConnections: Math.floor(Math.random() * (50 - 10) + 10),
        serviceStatus: {
          'Auth Service': Math.random() > 0.1 ? 'Up' : 'Down',
          'Data Service': Math.random() > 0.05 ? 'Up' : 'Down',
          'Notification Service': Math.random() > 0.02 ? 'Up' : 'Down',
        },
      });
    };

    const generateAnomalyData = () => {
      const anomalies = [];
      if (Math.random() < 0.2) { // 20% chance of anomaly
        anomalies.push({
          id: Date.now(),
          timestamp: new Date().toLocaleString(),
          type: 'High API Error Rate',
          threshold: '5%',
          actual: `${(Math.random() * (10 - 6) + 6).toFixed(1)}%`,
          severity: 'High',
        });
      }
      if (Math.random() < 0.1) { // 10% chance of another anomaly
        anomalies.push({
          id: Date.now() + 1,
          timestamp: new Date().toLocaleString(),
          type: 'Unusual Login Location',
          threshold: 'Geo-IP',
          actual: 'Jakarta -> London',
          severity: 'Medium',
        });
      }
      if (Math.random() < 0.05) { // 5% chance of severe anomaly
        anomalies.push({
          id: Date.now() + 2,
          timestamp: new Date().toLocaleString(),
          type: 'Critical DB Connection Count',
          threshold: '>= 40',
          actual: `${Math.floor(Math.random() * (60 - 40) + 40)}`,
          severity: 'Critical',
        });
      }
      setAnomalyDetection(anomalies);
    };

    // Initial data load
    generateApiData();
    generateBackendHealth();
    generateAnomalyData();

    // Refresh data every 10 seconds
    const interval = setInterval(() => {
      generateApiData();
      generateBackendHealth();
      generateAnomalyData();
    }, 10000);

    return () => clearInterval(interval);
  }, []);

  // --- Chart Data & Options ---
  const lineChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'top',
        labels: {
          font: {
            family: 'Poppins',
          },
        },
      },
      title: {
        display: false,
        text: 'API Metrics',
      },
    },
    scales: {
      x: {
        ticks: { font: { family: 'Poppins' } },
        grid: {
          display: false
        }
      },
      y: {
        beginAtZero: true,
        ticks: { font: { family: 'Poppins' } },
        grid: {
          color: 'rgba(0, 0, 0, 0.05)'
        }
      },
    },
  };

  const successRateChartData = {
    labels: apiData.labels,
    datasets: [{
      label: 'Success Rate (%)',
      data: apiData.successRates,
      borderColor: '#28a745', // Green
      backgroundColor: 'rgba(40, 167, 69, 0.2)',
      tension: 0.4,
      fill: true,
    }],
  };

  const errorCountChartData = {
    labels: apiData.labels,
    datasets: [{
      label: 'Error Count',
      data: apiData.errorCounts,
      borderColor: '#dc3545', // Red
      backgroundColor: 'rgba(220, 53, 69, 0.2)',
      tension: 0.4,
      fill: true,
    }],
  };

  const latencyChartData = {
    labels: apiData.labels,
    datasets: [{
      label: 'Avg Latency (ms)',
      data: apiData.avgLatencies,
      borderColor: '#007bff', // Blue
      backgroundColor: 'rgba(0, 123, 255, 0.2)',
      tension: 0.4,
      fill: true,
    }],
  };

  const doughnutChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'right',
        labels: {
          font: {
            family: 'Poppins',
          },
        },
      },
      title: {
        display: false,
      },
    },
  };

  const getDoughnutData = (label, value, maxValue, goodColor, badColor) => ({
    labels: [label, 'Remaining'],
    datasets: [{
      data: [value, Math.max(0, maxValue - value)],
      backgroundColor: [value > (maxValue * 0.8) ? badColor : goodColor, '#e9ecef'],
      borderColor: ['white', 'white'],
      hoverBackgroundColor: [value > (maxValue * 0.8) ? badColor : goodColor, '#e9ecef'],
      borderWidth: 1,
    }],
  });

  return (
    <div className="system-monitoring-container">
      <h1 className="page-title">System Monitoring</h1>

      <div className="monitoring-tabs">
        <button
          className={`tab-btn ${activeTab === 'api' ? 'active' : ''}`}
          onClick={() => setActiveTab('api')}
        >
          <IoCodeSlashOutline className="tab-icon" /> API Requests
        </button>
        <button
          className={`tab-btn ${activeTab === 'backend' ? 'active' : ''}`}
          onClick={() => setActiveTab('backend')}
        >
          <IoServerOutline className="tab-icon" /> Backend Health
        </button>
        <button
          className={`tab-btn ${activeTab === 'anomaly' ? 'active' : ''}`}
          onClick={() => setActiveTab('anomaly')}
        >
          <IoAlertCircleOutline className="tab-icon" /> Anomali Traffic
          {anomalyDetection.length > 0 && <span className="anomaly-alert-count">{anomalyDetection.length}</span>}
        </button>
      </div>

      <div className="tab-content">
        {activeTab === 'api' && (
          <div className="api-requests-tab">
            <h2>API Request Overview</h2>
            <p className="last-updated">Terakhir diperbarui: {new Date().toLocaleTimeString()}</p>

            <div className="monitoring-grid">
              <div className="chart-card">
                <h3>Success Rate (%)</h3>
                <div className="chart-wrapper">
                  <Line data={successRateChartData} options={lineChartOptions} />
                </div>
              </div>
              <div className="chart-card">
                <h3>Error Count</h3>
                <div className="chart-wrapper">
                  <Line data={errorCountChartData} options={lineChartOptions} />
                </div>
              </div>
              <div className="chart-card large">
                <h3>Average Latency (ms)</h3>
                <div className="chart-wrapper">
                  <Line data={latencyChartData} options={lineChartOptions} />
                </div>
              </div>
            </div>
          </div>
        )}

        {activeTab === 'backend' && (
          <div className="backend-health-tab">
            <h2>Backend System Health</h2>
            <p className="last-updated">Terakhir diperbarui: {new Date().toLocaleTimeString()}</p>

            <div className="monitoring-grid">
              <div className="chart-card small">
                <h3>CPU Usage (%)</h3>
                <div className="chart-wrapper">
                  <Doughnut data={getDoughnutData('CPU', parseFloat(backendHealth.cpuUsage), 100, '#28a745', '#dc3545')} options={doughnutChartOptions} />
                  <div className="doughnut-center-text">{backendHealth.cpuUsage}%</div>
                </div>
              </div>
              <div className="chart-card small">
                <h3>Memory Usage (%)</h3>
                <div className="chart-wrapper">
                  <Doughnut data={getDoughnutData('Mem', parseFloat(backendHealth.memoryUsage), 100, '#28a745', '#dc3545')} options={doughnutChartOptions} />
                  <div className="doughnut-center-text">{backendHealth.memoryUsage}%</div>
                </div>
              </div>
              <div className="chart-card small">
                <h3>Disk Usage (%)</h3>
                <div className="chart-wrapper">
                  <Doughnut data={getDoughnutData('Disk', parseFloat(backendHealth.diskUsage), 100, '#28a745', '#dc3545')} options={doughnutChartOptions} />
                  <div className="doughnut-center-text">{backendHealth.diskUsage}%</div>
                </div>
              </div>
              <div className="chart-card small">
                <h3>DB Connections</h3>
                <div className="chart-wrapper">
                  <Doughnut data={getDoughnutData('DB', backendHealth.dbConnections, 50, '#28a745', '#dc3545')} options={doughnutChartOptions} />
                  <div className="doughnut-center-text">{backendHealth.dbConnections}</div>
                </div>
              </div>

              <div className="status-card large">
                <h3>Service Status</h3>
                <ul className="service-status-list">
                  {backendHealth.serviceStatus && Object.entries(backendHealth.serviceStatus).map(([service, status]) => (
                    <li key={service} className={status.toLowerCase()}>
                      <span className="status-indicator"></span> {service}: <strong>{status}</strong>
                    </li>
                  ))}
                </ul>
              </div>
            </div>
          </div>
        )}

        {activeTab === 'anomaly' && (
          <div className="anomaly-traffic-tab">
            <h2>Traffic Anomaly Detection</h2>
            <p className="last-updated">Terakhir diperbarui: {new Date().toLocaleTimeString()}</p>

            {anomalyDetection.length > 0 ? (
              <div className="anomaly-list">
                {anomalyDetection.map(anomaly => (
                  <div key={anomaly.id} className={`anomaly-item ${anomaly.severity.toLowerCase()}`}>
                    <IoAlertCircleOutline className="anomaly-icon" />
                    <div className="anomaly-details">
                      <h4>{anomaly.type} <span className={`severity-tag ${anomaly.severity.toLowerCase()}`}>{anomaly.severity}</span></h4>
                      <p><strong>Waktu:</strong> {anomaly.timestamp}</p>
                      <p><strong>Ambang Batas:</strong> {anomaly.threshold}</p>
                      <p><strong>Aktual:</strong> {anomaly.actual}</p>
                    </div>
                  </div>
                ))}
              </div>
            ) : (
              <div className="no-anomaly">
                <IoRocketOutline size={50} />
                <p>Tidak ada anomali terdeteksi saat ini. Sistem berjalan normal.</p>
              </div>
            )}
          </div>
        )}
      </div>
    </div>
  );
};

export default SystemMonitoring;