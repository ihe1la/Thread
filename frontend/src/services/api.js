import axios from 'axios';

const API_URL = process.env.REACT_APP_API_URL;

const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json'
  }
});

api.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export const login = (username, password) => {
  return api.post('/api/users/login', { username, password });
};

export const getThreads = () => {
  return api.get('/api/threads');
};

export const createThread = (title, content) => {
  return api.post('/api/threads', { title, content });
};

export const getSecurityToggles = () => {
  return api.get('/api/admin');
};

export const updateSecurityToggles = (toggles) => {
  return api.put('/api/admin', toggles);
};

export const registerOAuthClient = (redirectUri) => {
  return api.post('/api/oauth/register', { redirect_uri: redirectUri });
};

export const authorizeOAuth = (clientId, redirectUri) => {
  return api.post('/api/oauth/authorize', { client_id: clientId, redirect_uri: redirectUri });
};