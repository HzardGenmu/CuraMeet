import React from "react";
import { useNavigate } from "react-router-dom";

const ForbiddenPage = () => {
  const navigate = useNavigate();

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-gray-100">
      <h1 className="text-4xl font-bold text-red-600 mb-4">403 Forbidden</h1>
      <p className="text-lg text-gray-700 mb-6">
        Anda tidak memiliki akses ke halaman ini.
      </p>
      <button
        className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        onClick={() => navigate("/")}
      >
        Kembali ke Beranda
      </button>
    </div>
  );
};

export default ForbiddenPage;
