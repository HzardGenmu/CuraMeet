import React, { useState } from "react";
import {
  IoClose,
  IoCalendar,
  IoTime,
  IoPerson,
  IoDocumentText,
} from "react-icons/io5";

const AppointmentFormModal = ({ isOpen, onClose, onSubmit, doctors }) => {
  // State untuk mengelola input form
  const [doctorId, setDoctorId] = useState("");
  const [date, setDate] = useState("");
  const [time, setTime] = useState("");
  const [notes, setNotes] = useState("");
  const [error, setError] = useState("");

  if (!isOpen) {
    return null;
  }

  const handleSubmit = (e) => {
    e.preventDefault();
    setError("");

    // Validasi sederhana, sekarang termasuk 'notes'
    if (!doctorId || !date || !time || !notes) {
      setError("Harap lengkapi semua field yang wajib diisi.");
      return;
    }

    // Mengirim data kembali ke komponen induk (JanjiTemu.jsx)
    onSubmit({
      doctorId,
      date,
      time,
      notes,
    });
  };

  return (
    // Latar belakang modal
    <div
      className="fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center z-50"
      onClick={onClose}
    >
      {/* Konten Modal */}
      <div
        className="bg-white p-6 sm:p-8 rounded-xl w-11/12 max-w-lg shadow-2xl transform transition-all"
        onClick={(e) => e.stopPropagation()}
      >
        {/* Header Modal */}
        <div className="flex justify-between items-center border-b border-gray-200 pb-4 mb-6">
          <h2 className="text-2xl font-semibold text-gray-800">
            Buat Janji Temu Baru
          </h2>
          <button
            onClick={onClose}
            className="text-gray-500 hover:text-gray-800 transition p-1 rounded-full"
          >
            <IoClose size={28} />
          </button>
        </div>

        {/* Body Modal berisi Form */}
        <form onSubmit={handleSubmit} className="space-y-6">
          {error && (
            <div className="bg-red-100 text-red-700 p-3 rounded-lg text-center text-sm">
              ⚠️ {error}
            </div>
          )}

          {/* Input Dokter */}
          <div>
            <label
              htmlFor="doctor"
              className="block text-sm font-medium mb-2 text-gray-700"
            >
              Pilih Dokter <span className="text-red-500">*</span>
            </label>
            <div className="relative">
              <IoPerson className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
              <select
                id="doctor"
                value={doctorId}
                onChange={(e) => setDoctorId(e.target.value)}
                required
                className="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="" disabled>
                  -- Pilih seorang dokter --
                </option>
                {doctors.map((doctor) => (
                  <option key={doctor.id} value={doctor.id}>
                    {doctor.full_name} - {doctor.specialist}
                  </option>
                ))}
              </select>
            </div>
          </div>

          {/* Input Tanggal & Waktu */}
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label
                htmlFor="date"
                className="block text-sm font-medium mb-2 text-gray-700"
              >
                Tanggal <span className="text-red-500">*</span>
              </label>
              <div className="relative">
                <IoCalendar className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                <input
                  type="date"
                  id="date"
                  value={date}
                  onChange={(e) => setDate(e.target.value)}
                  required
                  className="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
            </div>
            <div>
              <label
                htmlFor="time"
                className="block text-sm font-medium mb-2 text-gray-700"
              >
                Waktu <span className="text-red-500">*</span>
              </label>
              <div className="relative">
                <IoTime className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                <input
                  type="time"
                  id="time"
                  value={time}
                  onChange={(e) => setTime(e.target.value)}
                  required
                  className="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
            </div>
          </div>

          {/* Input Catatan */}
          <div>
            <label
              htmlFor="notes"
              className="block text-sm font-medium mb-2 text-gray-700"
            >
              Catatan <span className="text-red-500">*</span>
            </label>
            <div className="relative">
              <IoDocumentText className="absolute left-3 top-4 text-gray-400" />
              <textarea
                id="notes"
                rows="3"
                value={notes}
                onChange={(e) => setNotes(e.target.value)}
                placeholder="Contoh: Saya mengalami demam selama 3 hari."
                className="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-y"
                pattern="^[^<>]*$"
                required
              ></textarea>
            </div>
          </div>

          {/* Tombol Aksi */}
          <div className="flex justify-end gap-4 pt-4">
            <button
              type="button"
              className="px-6 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition"
              onClick={onClose}
            >
              Batal
            </button>
            <button
              type="submit"
              className="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition"
            >
              Jadwalkan
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default AppointmentFormModal;
