import api from './api';
import { User, LoginData, RegisterData, AuthResponse } from '@/types';

export const authService = {
  async register(data: RegisterData): Promise<AuthResponse> {
    const response = await api.post('/api/register', data, { withCredentials: true });
    return response.data;
  },

  async login(data: LoginData): Promise<AuthResponse> {
    const response = await api.post('/api/login', data, { withCredentials: true });
    return response.data;
  },

  async logout(): Promise<void> {
    await api.post('/api/logout', {});
  },

  async getUser(): Promise<User> {
    try {
      const response = await api.get('/api/user', { withCredentials: true });
      return response.data;
    } catch (error: any) {
      if (error.response && error.response.status === 401) {
        return null as any;
      }
      throw error;
    }
  },

  async checkAuth(): Promise<User | null> {
    try {
      const user = await this.getUser();
      return user;
    } catch (error) {
      return null;
    }
  }
};