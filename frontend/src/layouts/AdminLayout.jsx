import AdminSidebar from '../components/AdminSidebar/AdminSidebar';
import Header from '../components/Header/Header'; // Pastikan path ini benar
import { Outlet } from 'react-router-dom';

const AdminLayout = () => {
  return (
    <div className="flex min-h-screen bg-gray-100">
      {/* Sidebar tetap fixed di kiri */}
      <AdminSidebar />

      
      <div className="flex-grow flex flex-col ml-64"> 
        
        <Header /> 

        <main className="flex-grow p-6"> 
          <Outlet />
        </main>
      </div>
    </div>
  );
};

export default AdminLayout;