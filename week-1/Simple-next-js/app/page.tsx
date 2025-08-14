'use client';
import { useAuth } from '@/lib/AuthContext';

export default function Home() {
  const { user } = useAuth();

  return (
    <div className="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-blue-100 to-purple-200">
      <div className="bg-white rounded-xl shadow-lg p-10 max-w-xl w-full text-center">
        <h1 className="text-5xl font-extrabold text-blue-700 mb-6">
           {user ? 'Hello ' + user.name : 'Welcome to the simple.next'}
        </h1>
        <p className="text-lg text-gray-700 mb-8">
          {user ? 'welcome back !' : 'This is a minimal Next.js auth template.'}
        </p>
        <a
          href="https://nextjs.org/docs"
          target="_blank"
          rel="noopener noreferrer"
          className="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors"
        >
          Read the Next.js Docs
        </a>
      </div>
    </div>
  );
}
