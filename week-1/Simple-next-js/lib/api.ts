import axios from 'axios';

const API_URL = process.env.NEXT_PUBLIC_API_URL

const api = axios.create({
  baseURL: API_URL,
  withCredentials: true,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    "X-Requested-With": "XMLHttpRequest",
  },
});

// Request interceptor to get CSRF token
api.interceptors.request.use(async (config) => {
  // Get CSRF token for state-changing requests
  if (['post','get'].includes(config.method?.toLowerCase() || '')) {
    try {
      await axios.get(`${API_URL}/sanctum/csrf-cookie`, {
        withCredentials: true,
      });
    } catch (error) {
      console.error('Failed to get CSRF token:', error);
    }
  }
  return config;
});


export default api;