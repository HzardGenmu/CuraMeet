import React from "react";

const LogoutLoadingOverlay = ({ isVisible }) => {
  if (!isVisible) return null;

  return (
    <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
      <div className="bg-white p-8 rounded-lg shadow-lg text-center">
        <div className="mb-4 flex justify-center">
          <div className="animate-spin rounded-full h-12 w-12 border-4 border-gray-300 border-t-green-600"></div>
        </div>
        <p className="text-gray-700 font-semibold">Logging out...</p>
        <p className="text-gray-500 text-sm mt-2">Redirecting...</p>
      </div>
    </div>
  );
};

export default LogoutLoadingOverlay;
