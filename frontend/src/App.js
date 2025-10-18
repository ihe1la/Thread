import React, { useState } from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Navigation from './components/Navigation';
import Login from './components/Login';
import ThreadList from './components/ThreadList';
import CreateThread from './components/CreateThread';
import AdminPanel from './components/AdminPanel';

function App() {
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [isAdmin, setIsAdmin] = useState(false);

  const handleLogin = (token) => {
    localStorage.setItem('token', token);
    const tokenData = JSON.parse(atob(token));
    setIsAuthenticated(true);
    setIsAdmin(tokenData.is_admin);
  };

  const handleLogout = () => {
    localStorage.removeItem('token');
    setIsAuthenticated(false);
    setIsAdmin(false);
  };

  return (
    <Router>
      <div className="App">
        <Navigation isAuthenticated={isAuthenticated} isAdmin={isAdmin} onLogout={handleLogout} />
        <div className="container mt-4">
          <Routes>
            <Route path="/login" element={<Login onLogin={handleLogin} />} />
            <Route path="/threads" element={
              isAuthenticated ? <ThreadList /> : <Navigate to="/login" />
            } />
            <Route path="/create-thread" element={
              isAuthenticated ? <CreateThread /> : <Navigate to="/login" />
            } />
            <Route path="/admin" element={
              (isAuthenticated && isAdmin) ? <AdminPanel /> : <Navigate to="/login" />
            } />
            <Route path="/" element={<Navigate to="/threads" />} />
          </Routes>
        </div>
      </div>
    </Router>
  );
}

export default App;