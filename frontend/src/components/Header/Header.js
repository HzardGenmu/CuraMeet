import React from 'react';
import './Header.css';
import { IoNotifications, IoMail, IoPersonCircle } from 'react-icons/io5';

const Header = () => {
  return (
    <header className="dashboard-header">
      <div className="logo">
        CuraMeet
      </div>
      <div className="header-icons">
        <IoNotifications size={24} className="icon" />
        <IoMail size={24} className="icon" />
        <IoPersonCircle size={36} className="icon profile-icon" />
      </div>
    </header>
  );
};

export default Header;