'use client';

import { createContext, useContext, useState, useEffect, ReactNode } from 'react';
import { User } from '@/types';
import { authService } from './auth';

interface AuthContextType {
  user: User | null;
  loading: boolean;
  login: (email: string, password: string) => Promise<void>;
  register: (name: string, email: string, password: string, passwordConfirmation: string) => Promise<void>;
  logout: () => Promise<void>;
  refreshUser: () => Promise<void>;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export function AuthProvider({ children }: { children: ReactNode }) {
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const storedUser = localStorage.getItem('auth_user');
    if (storedUser) {
      setUser(JSON.parse(storedUser));
      setLoading(false); 
    } else {
      checkAuth();
    }
  }, []);

  useEffect(() => {
    if (user) {
      localStorage.setItem('auth_user', JSON.stringify(user));
    } else {
      localStorage.removeItem('auth_user');
    }
  }, [user]);

  const checkAuth = async () => {
    try {
      const user = await authService.checkAuth();
      setUser(user);
    } catch (error) {
      setUser(null);
    } finally {
      setLoading(false);
    }
  };

  const login = async (email: string, password: string) => {
    const response = await authService.login({ email, password });
    setUser(response.user);
    localStorage.setItem('auth_user', JSON.stringify(response.user));
  };

  const register = async (name: string, email: string, password: string, passwordConfirmation: string) => {
    const response = await authService.register({ 
      name, 
      email, 
      password, 
      password_confirmation: passwordConfirmation 
    });
    setUser(response.user);
    localStorage.setItem('auth_user', JSON.stringify(response.user));
  };

  const logout = async () => {
    setUser(null);
    localStorage.removeItem('auth_user');
  };

  const refreshUser = async () => {
    try {
      const user = await authService.getUser();
      setUser(user);
    } catch (error) {
      setUser(null);
    }
  };

  return (
    <AuthContext.Provider value={{ user, loading, login, register, logout, refreshUser }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
}