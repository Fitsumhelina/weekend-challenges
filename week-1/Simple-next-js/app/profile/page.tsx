'use client';

import { useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { useAuth } from '@/lib/AuthContext';

export default function Profile() {
  const { user, loading, logout, refreshUser } = useAuth();
  const router = useRouter();

  useEffect(() => {
    if (!loading && !user) {
      router.push('/login');
    }
  }, [user, loading, router]);

  const handleLogout = async () => {
    try {
      await logout();
      router.push('/');
    } catch (error) {
      console.error('Logout failed:', error);
    }
  };

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-blue-500"></div>
      </div>
    );
  }

  if (!user) {
    return null; 
  }

  return (
    <div className="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-3xl mx-auto">
        <div className="bg-white shadow-lg rounded-lg overflow-hidden">
          {/* Header */}
          <div className="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-8">
            <h1 className="text-3xl font-bold text-white">Profile</h1>
            <p className="text-blue-100 mt-2">Manage your account information</p>
          </div>

          {/* Profile Content */}
          <div className="px-6 py-8">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              {/* User Information */}
              <div className="space-y-6">
                <div>
                  <h2 className="text-xl font-semibold text-gray-900 mb-4">
                    Personal Information
                  </h2>
                  
                  <div className="space-y-4">
                    <div className="border-b border-gray-200 pb-4">
                      <label className="block text-sm font-medium text-gray-500 uppercase tracking-wide">
                        Name
                      </label>
                      <p className="mt-1 text-lg text-gray-900">{user.name}</p>
                    </div>
                    
                    <div className="border-b border-gray-200 pb-4">
                      <label className="block text-sm font-medium text-gray-500 uppercase tracking-wide">
                        Email
                      </label>
                      <p className="mt-1 text-lg text-gray-900">{user.email}</p>
                    </div>
                    
                    <div className="border-b border-gray-200 pb-4">
                      <label className="block text-sm font-medium text-gray-500 uppercase tracking-wide">
                        User ID
                      </label>
                      <p className="mt-1 text-lg text-gray-900">#{user.id}</p>
                    </div>
                    
                    <div className="border-b border-gray-200 pb-4">
                      <label className="block text-sm font-medium text-gray-500 uppercase tracking-wide">
                        Email Verified
                      </label>
                      <p className="mt-1 text-lg">
                        {user.email_verified_at ? (
                          <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Verified
                          </span>
                        ) : (
                          <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Not Verified
                          </span>
                        )}
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              {/* Account Details */}
              <div className="space-y-6">
                <div>
                  <h2 className="text-xl font-semibold text-gray-900 mb-4">
                    Account Details
                  </h2>
                  
                  <div className="space-y-4">
                    <div className="border-b border-gray-200 pb-4">
                      <label className="block text-sm font-medium text-gray-500 uppercase tracking-wide">
                        Member Since
                      </label>
                      <p className="mt-1 text-lg text-gray-900">
                        {new Date(user.created_at).toLocaleDateString('en-US', {
                          year: 'numeric',
                          month: 'long',
                          day: 'numeric'
                        })}
                      </p>
                    </div>
                    
                    <div className="border-b border-gray-200 pb-4">
                      <label className="block text-sm font-medium text-gray-500 uppercase tracking-wide">
                        Last Updated
                      </label>
                      <p className="mt-1 text-lg text-gray-900">
                        {new Date(user.updated_at).toLocaleDateString('en-US', {
                          year: 'numeric',
                          month: 'long',
                          day: 'numeric'
                        })}
                      </p>
                    </div>
                  </div>
                </div>

                {/* Account Actions */}
                <div className="bg-gray-50 rounded-lg p-6">
                  <h3 className="text-lg font-medium text-gray-900 mb-4">
                    Account Actions
                  </h3>
                  
                  <div className="space-y-3">
                    <button
                      onClick={handleLogout}
                      className="w-full bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                    >
                      Logout
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Additional Profile Stats */}
        <div className="mt-8 bg-white shadow rounded-lg">
          <div className="px-6 py-4">
            <h3 className="text-lg font-medium text-gray-900 mb-4">
              Profile Statistics
            </h3>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div className="text-center p-4 bg-blue-50 rounded-lg">
                <div className="text-2xl font-bold text-blue-600">
                  {Math.floor((Date.now() - new Date(user.created_at).getTime()) / (1000 * 60 * 60 * 24))}
                </div>
                <div className="text-sm text-gray-600">Days as Member</div>
              </div>
              
              <div className="text-center p-4 bg-green-50 rounded-lg">
                <div className="text-2xl font-bold text-green-600">
                  {user.email_verified_at ? '✓' : '✗'}
                </div>
                <div className="text-sm text-gray-600">Email Status</div>
              </div>
              
              <div className="text-center p-4 bg-purple-50 rounded-lg">
                <div className="text-2xl font-bold text-purple-600">
                  {user.name.split(' ').length}
                </div>
                <div className="text-sm text-gray-600">Name Parts</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}