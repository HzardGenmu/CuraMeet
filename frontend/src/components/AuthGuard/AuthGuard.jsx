import React from "react";
import { Navigate } from "react-router-dom";
import { authService } from "../../services/authService";

const AuthGuard = ({ children, requiredRole = null }) => {
  const isAuthenticated = authService.isAuthenticated();
  const currentUser = authService.getCurrentUser();

  if (!isAuthenticated) {
    return <Navigate to="/forbidden" replace />;
  }

  if (requiredRole && currentUser?.role !== requiredRole) {
    return <Navigate to="/forbidden" replace />;
  }

  return children;
};

export default AuthGuard;
