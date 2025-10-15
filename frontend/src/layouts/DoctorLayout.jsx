// Misalnya di komponen DoctorLayout.jsx atau DoctorDashboard.jsx
import DoctorSidebar from '../components/DoctorSidebar/DoctorSidebar';
import Header from '../components/Header/Header';
import { Outlet } from 'react-router-dom';

const DoctorLayout = () => {
  return (
    <div className="flex min-h-screen bg-gray-100">
      {/* Sidebar tetap fixed di kiri */}
      <DoctorSidebar />

      
      <div className="flex-grow flex flex-col ml-64"> 
        
        <Header /> 

        <main className="flex-grow p-6"> 
          <Outlet />
        </main>
      </div>
    </div>
  );
};

export default DoctorLayout;