import React, { useState, useEffect } from 'react';
import { Line, Doughnut } from 'react-chartjs-2';
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, ArcElement, Tooltip, Legend, Title } from 'chart.js';
import { IoAlertCircleOutline, IoRocketOutline, IoPulseOutline, IoCodeSlashOutline, IoServerOutline } from 'react-icons/io5';


// Register Chart.js components
ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, ArcElement, Tooltip, Legend, Title);

const SystemMonitoring = () => {
  const [activeTab, setActiveTab] = useState('api'); // 'api', 'backend', 'anomaly'
  const [apiData, setApiData] = useState({});
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

  // --- Chart Data & Options (using Tailwind color names if defined) ---
  const lineChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'top',
        labels: {
          font: {
            family: 'Poppins', // Pastikan font ini tersedia
          },
        },
      },
      title: {
        display: false,
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
      borderColor: '#28a745', // Tailwind: green-600
      backgroundColor: 'rgba(40, 167, 69, 0.2)', // Tailwind: green-600 with opacity
      tension: 0.4,
      fill: true,
    }],
  };

  const errorCountChartData = {
    labels: apiData.labels,
    datasets: [{
      label: 'Error Count',
      data: apiData.errorCounts,
      borderColor: '#dc3545', // Tailwind: red-600
      backgroundColor: 'rgba(220, 53, 69, 0.2)', // Tailwind: red-600 with opacity
      tension: 0.4,
      fill: true,
    }],
  };

  const latencyChartData = {
    labels: apiData.labels,
    datasets: [{
      label: 'Avg Latency (ms)',
      data: apiData.avgLatencies,
      borderColor: '#007bff', // Tailwind: blue-600
      backgroundColor: 'rgba(0, 123, 255, 0.2)', // Tailwind: blue-600 with opacity
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
    <div className="p-8 bg-gray-100 min-h-screen">
      <div className="bg-white rounded-xl shadow-lg p-6 lg:p-8">
        <h1 className="text-3xl font-semibold mb-8 text-gray-800 text-center">System Monitoring</h1>

        <div className="flex justify-center border-b-2 border-gray-200 mb-8 flex-wrap">
          <button
            className={`flex items-center gap-2 px-5 py-3 text-lg font-medium text-gray-600 cursor-pointer transition-all duration-300 ease-in-out relative outline-none
                        ${activeTab === 'api' ? 'text-emerald-600 border-b-3 border-emerald-600 font-semibold' : 'hover:text-emerald-600'}`}
            onClick={() => setActiveTab('api')}
          >
            <IoCodeSlashOutline className="text-xl" /> API Requests
          </button>
          <button
            className={`flex items-center gap-2 px-5 py-3 text-lg font-medium text-gray-600 cursor-pointer transition-all duration-300 ease-in-out relative outline-none
                        ${activeTab === 'backend' ? 'text-emerald-600 border-b-3 border-emerald-600 font-semibold' : 'hover:text-emerald-600'}`}
            onClick={() => setActiveTab('backend')}
          >
            <IoServerOutline className="text-xl" /> Backend Health
          </button>
          <button
            className={`flex items-center gap-2 px-5 py-3 text-lg font-medium text-gray-600 cursor-pointer transition-all duration-300 ease-in-out relative outline-none
                        ${activeTab === 'anomaly' ? 'text-emerald-600 border-b-3 border-emerald-600 font-semibold' : 'hover:text-emerald-600'}`}
            onClick={() => setActiveTab('anomaly')}
          >
            <IoAlertCircleOutline className="text-xl" /> Anomali Traffic
            {anomalyDetection.length > 0 && (
              <span className="ml-2 bg-red-600 text-white text-xs font-bold rounded-full w-5 h-5 flex justify-center items-center">
                {anomalyDetection.length}
              </span>
            )}
          </button>
        </div>

        <div className="py-4">
          {activeTab === 'api' && (
            <div className="api-requests-tab">
              <h2 className="text-2xl text-gray-700 mb-6 pb-2 border-b border-gray-200">API Request Overview</h2>
              <p className="text-right text-sm text-gray-600 mb-6 -mt-4">Terakhir diperbarui: {new Date().toLocaleTimeString()}</p>

              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div className="bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-200">
                  <h3 className="text-lg text-gray-700 mb-4 pb-2 border-b border-dashed border-gray-200 text-center">Success Rate (%)</h3>
                  <div className="relative h-52 w-full"> {/* Set a fixed height for chart consistency */}
                    <Line data={successRateChartData} options={lineChartOptions} />
                  </div>
                </div>
                <div className="bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-200">
                  <h3 className="text-lg text-gray-700 mb-4 pb-2 border-b border-dashed border-gray-200 text-center">Error Count</h3>
                  <div className="relative h-52 w-full">
                    <Line data={errorCountChartData} options={lineChartOptions} />
                  </div>
                </div>
                <div className="md:col-span-2 lg:col-span-1 bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-200">
                  <h3 className="text-lg text-gray-700 mb-4 pb-2 border-b border-dashed border-gray-200 text-center">Average Latency (ms)</h3>
                  <div className="relative h-52 w-full">
                    <Line data={latencyChartData} options={lineChartOptions} />
                  </div>
                </div>
              </div>
            </div>
          )}

          {activeTab === 'backend' && (
            <div className="backend-health-tab">
              <h2 className="text-2xl text-gray-700 mb-6 pb-2 border-b border-gray-200">Backend System Health</h2>
              <p className="text-right text-sm text-gray-600 mb-6 -mt-4">Terakhir diperbarui: {new Date().toLocaleTimeString()}</p>

              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div className="bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-200 flex flex-col items-center text-center">
                  <h3 className="text-lg text-gray-700 mb-4 pb-2 border-b border-dashed border-gray-200 w-full">CPU Usage (%)</h3>
                  <div className="relative h-32 w-32 mb-2"> {/* Fixed size for doughnut */}
                    <Doughnut data={getDoughnutData('CPU', parseFloat(backendHealth.cpuUsage), 100, '#28a745', '#dc3545')} options={doughnutChartOptions} />
                    <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-2xl font-bold text-gray-800 pointer-events-none">
                      {backendHealth.cpuUsage}%
                    </div>
                  </div>
                </div>
                <div className="bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-200 flex flex-col items-center text-center">
                  <h3 className="text-lg text-gray-700 mb-4 pb-2 border-b border-dashed border-gray-200 w-full">Memory Usage (%)</h3>
                  <div className="relative h-32 w-32 mb-2">
                    <Doughnut data={getDoughnutData('Mem', parseFloat(backendHealth.memoryUsage), 100, '#28a745', '#dc3545')} options={doughnutChartOptions} />
                    <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-2xl font-bold text-gray-800 pointer-events-none">
                      {backendHealth.memoryUsage}%
                    </div>
                  </div>
                </div>
                <div className="bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-200 flex flex-col items-center text-center">
                  <h3 className="text-lg text-gray-700 mb-4 pb-2 border-b border-dashed border-gray-200 w-full">Disk Usage (%)</h3>
                  <div className="relative h-32 w-32 mb-2">
                    <Doughnut data={getDoughnutData('Disk', parseFloat(backendHealth.diskUsage), 100, '#28a745', '#dc3545')} options={doughnutChartOptions} />
                    <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-2xl font-bold text-gray-800 pointer-events-none">
                      {backendHealth.diskUsage}%
                    </div>
                  </div>
                </div>
                <div className="bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-200 flex flex-col items-center text-center">
                  <h3 className="text-lg text-gray-700 mb-4 pb-2 border-b border-dashed border-gray-200 w-full">DB Connections</h3>
                  <div className="relative h-32 w-32 mb-2">
                    <Doughnut data={getDoughnutData('DB', backendHealth.dbConnections, 50, '#28a745', '#dc3545')} options={doughnutChartOptions} />
                    <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-2xl font-bold text-gray-800 pointer-events-none">
                      {backendHealth.dbConnections}
                    </div>
                  </div>
                </div>

                <div className="col-span-full lg:col-span-4 bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-200">
                  <h3 className="text-xl text-gray-700 mb-4 pb-2 border-b border-dashed border-gray-200">Service Status</h3>
                  <ul className="list-none p-0 m-0">
                    {backendHealth.serviceStatus && Object.entries(backendHealth.serviceStatus).map(([service, status]) => (
                      <li key={service} className={`flex items-center mb-2 text-base text-gray-700 ${status.toLowerCase()}`}>
                        <span className={`w-3 h-3 rounded-full mr-3 inline-block
                                        ${status.toLowerCase() === 'up' ? 'bg-green-600' : 'bg-red-600'}`}></span>
                        {service}: <strong className={`ml-1 ${status.toLowerCase() === 'up' ? 'text-green-600' : 'text-red-600'}`}>{status}</strong>
                      </li>
                    ))}
                  </ul>
                </div>
              </div>
            </div>
          )}

          {activeTab === 'anomaly' && (
            <div className="anomaly-traffic-tab">
              <h2 className="text-2xl text-gray-700 mb-6 pb-2 border-b border-gray-200">Traffic Anomaly Detection</h2>
              <p className="text-right text-sm text-gray-600 mb-6 -mt-4">Terakhir diperbarui: {new Date().toLocaleTimeString()}</p>

              {anomalyDetection.length > 0 ? (
                <div className="flex flex-col gap-4">
                  {anomalyDetection.map(anomaly => (
                    <div
                      key={anomaly.id}
                      className={`flex items-start p-4 md:p-6 rounded-lg shadow-md border-l-4 bg-white
                                  ${anomaly.severity.toLowerCase() === 'high' ? 'border-amber-400' : ''}
                                  ${anomaly.severity.toLowerCase() === 'medium' ? 'border-orange-500' : ''}
                                  ${anomaly.severity.toLowerCase() === 'critical' ? 'border-red-600' : ''}`}
                    >
                      <IoAlertCircleOutline
                        className={`text-3xl md:text-4xl mr-4 flex-shrink-0
                                    ${anomaly.severity.toLowerCase() === 'high' ? 'text-amber-400' : ''}
                                    ${anomaly.severity.toLowerCase() === 'medium' ? 'text-orange-500' : ''}
                                    ${anomaly.severity.toLowerCase() === 'critical' ? 'text-red-600' : 'text-gray-500'}`}
                      />
                      <div>
                        <h4 className="flex items-center gap-2 text-xl font-semibold text-gray-800 mb-1">
                          {anomaly.type}
                          <span
                            className={`px-2 py-1 rounded-md text-xs font-semibold text-white
                                        ${anomaly.severity.toLowerCase() === 'high' ? 'bg-amber-400 text-gray-900' : ''}
                                        ${anomaly.severity.toLowerCase() === 'medium' ? 'bg-orange-500' : ''}
                                        ${anomaly.severity.toLowerCase() === 'critical' ? 'bg-red-600' : ''}`}
                          >
                            {anomaly.severity}
                          </span>
                        </h4>
                        <p className="text-gray-600 text-sm mb-1"><strong>Waktu:</strong> {anomaly.timestamp}</p>
                        <p className="text-gray-600 text-sm mb-1"><strong>Ambang Batas:</strong> {anomaly.threshold}</p>
                        <p className="text-gray-600 text-sm"><strong>Aktual:</strong> {anomaly.actual}</p>
                      </div>
                    </div>
                  ))}
                </div>
              ) : (
                <div className="text-center p-12 text-gray-600 text-lg">
                  <IoRocketOutline size={50} className="text-emerald-600 mx-auto mb-4" />
                  <p>Tidak ada anomali terdeteksi saat ini. Sistem berjalan normal.</p>
                </div>
              )}
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default SystemMonitoring;