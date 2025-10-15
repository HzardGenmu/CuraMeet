import Sidebar from '../components/Sidebar/Sidebar'; 
import Header from '../components/Header/Header'; 
import { Outlet } from 'react-router-dom';

const DashboardLayout = () => {
  return (
    <div className="flex min-h-screen bg-gray-100">
      <Sidebar />
      <div className="flex-grow flex flex-col ml-64"> {/* Tambahkan ml-64 di sini */}
        <Header /> {/* Jika ada Header untuk pasien juga */}
        <main className="flex-grow p-6">
          <Outlet /> {/* Ini akan merender rute anak Anda */}
        </main>
      </div>
    </div>
  );
};

export default DashboardLayout;