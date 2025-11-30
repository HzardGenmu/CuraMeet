import React from "react";

import { IoNotifications, IoMail, IoPersonCircle } from "react-icons/io5";

const Header = () => {
  return (
    <header className="flex justify-between items-center py-4 px-6 bg-white border-b border-gray-200 shadow-sm">
      <div className="text-2xl font-bold text-emerald-600">CuraMeet</div>

      {/* <div className="flex items-center space-x-6"> 
        <IoNotifications size={24} className="text-gray-600 cursor-pointer hover:text-emerald-600 transition duration-200" />
        <IoMail size={24} className="text-gray-600 cursor-pointer hover:text-emerald-600 transition duration-200" />
        <IoPersonCircle size={36} className="text-gray-500 cursor-pointer hover:text-emerald-600 transition duration-200" /> 
      </div> */}
    </header>
  );
};

export default Header;
