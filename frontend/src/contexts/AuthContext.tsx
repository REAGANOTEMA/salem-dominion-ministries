import React, { createContext, useContext, useState, useEffect, ReactNode } from 'react';
import { API_ENDPOINTS, fetchAPI } from '@/utils/api';

interface User {
  id?: number;
  firstName?: string;
  lastName?: string;
  email: string;
  phone?: string;
  role?: string;
  profileImage?: string;
}

interface RegisterData {
  firstName: string;
  lastName: string;
  email: string;
  phone: string;
  password: string;
}

interface AuthContextType {
  user: User | null;
  isAuthenticated: boolean;
  login: (email: string, password: string) => Promise<boolean>;
  register: (userData: RegisterData) => Promise<boolean>;
  logout: () => void;
  loading: boolean;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};

interface AuthProviderProps {
  children: ReactNode;
}

export const AuthProvider: React.FC<AuthProviderProps> = ({ children }) => {
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const token = localStorage.getItem('token');
    if (token) {
      // Verify token and get user data
      fetchUserData();
    } else {
      setLoading(false);
    }
  }, []);

  const fetchUserData = async () => {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        setLoading(false);
        return;
      }

      // Use the new API configuration
      const data = await fetchAPI<{ success: boolean; user: User }>(API_ENDPOINTS.AUTH_VERIFY, {
        headers: {
          'Authorization': `Bearer ${token}`,
        },
      });

      if (data.success) {
        setUser(data.user);
      }
    } catch (error) {
      console.error('Error fetching user data:', error);
      localStorage.removeItem('token');
    } finally {
      setLoading(false);
    }
  };

  const login = async (email: string, password: string): Promise<boolean> => {
    try {
      // Use the new API configuration
      const data = await fetchAPI<{ success: boolean; token: string; user: User }>(API_ENDPOINTS.AUTH_LOGIN, {
        method: 'POST',
        body: JSON.stringify({ email, password }),
      });

      if (data.success) {
        localStorage.setItem('token', data.token);
        setUser(data.user);
        return true;
      }
      return false;
    } catch (error) {
      console.error('Login error:', error);
      return false;
    }
  };

  const register = async (userData: RegisterData): Promise<boolean> => {
    try {
      // Use the new API configuration
      const data = await fetchAPI<{ success: boolean }>(API_ENDPOINTS.AUTH_REGISTER, {
        method: 'POST',
        body: JSON.stringify(userData),
      });

      if (data.success) {
        // Auto-login after successful registration
        const loginSuccess = await login(userData.email, userData.password);
        return loginSuccess;
      }
      return false;
    } catch (error) {
      console.error('Registration error:', error);
      return false;
    }
  };

  const logout = () => {
    localStorage.removeItem('token');
    setUser(null);
  };

  const value: AuthContextType = {
    user,
    isAuthenticated: !!user,
    login,
    register,
    logout,
    loading,
  };

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};