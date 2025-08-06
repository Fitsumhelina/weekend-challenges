import React, { useState } from 'react';
import { EditorState } from 'lexical';
import LexicalEditorWithToolbar from '../components/LexicalEditorWithToolbar';
import { Save, Download, FileText, Settings } from 'lucide-react';

const EditorPage: React.FC = () => {
  const [editorState, setEditorState] = useState<EditorState>();
  const [documentTitle, setDocumentTitle] = useState('Untitled Document');
  const [lastSaved, setLastSaved] = useState<Date | null>(null);

  const handleEditorChange = (newEditorState: EditorState) => {
    setEditorState(newEditorState);
  };

  const handleSave = () => {
    if (editorState) {
      // Here you would typically save to your backend or local storage
      const serializedState = JSON.stringify(editorState.toJSON());
      
      // For demo purposes, we'll just save to localStorage
      const savedDocument = {
        title: documentTitle,
        content: serializedState,
        lastModified: new Date().toISOString(),
      };
      
      localStorage.setItem('lexical-document', JSON.stringify(savedDocument));
      setLastSaved(new Date());
      
      // Show success message (you could use a toast library here)
      alert('Document saved successfully!');
    }
  };

  const handleExport = () => {
    if (editorState) {
      // Export as JSON
      const serializedState = JSON.stringify(editorState.toJSON(), null, 2);
      const blob = new Blob([serializedState], { type: 'application/json' });
      const url = URL.createObjectURL(blob);
      
      const link = document.createElement('a');
      link.href = url;
      link.download = `${documentTitle.replace(/\s+/g, '_')}.json`;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      URL.revokeObjectURL(url);
    }
  };

  const loadSavedDocument = () => {
    const saved = localStorage.getItem('lexical-document');
    if (saved) {
      const document = JSON.parse(saved);
      setDocumentTitle(document.title);
      setLastSaved(new Date(document.lastModified));
      
      // You would need to restore the editor state here
      // This is a simplified example
      alert('Document loaded! (Note: Content restoration not implemented in this demo)');
    } else {
      alert('No saved document found!');
    }
  };

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <header className="bg-white border-b border-gray-200 sticky top-0 z-10">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <div className="flex items-center space-x-4">
              <div className="flex items-center space-x-2">
                <FileText className="h-8 w-8 text-blue-600" />
                <h1 className="text-xl font-semibold text-gray-900 hidden sm:block">
                  Rich Text Editor
                </h1>
              </div>
              
              <div className="flex items-center space-x-2">
                <input
                  type="text"
                  value={documentTitle}
                  onChange={(e) => setDocumentTitle(e.target.value)}
                  className="text-sm font-medium text-gray-700 bg-transparent border-none focus:outline-none focus:ring-2 focus:ring-blue-500 px-2 py-1 rounded"
                  placeholder="Document title"
                />
              </div>
            </div>
            
            <div className="flex items-center space-x-2">
              {lastSaved && (
                <span className="text-xs text-gray-500 hidden sm:inline">
                  Saved at {lastSaved.toLocaleTimeString()}
                </span>
              )}
              
              <button
                onClick={loadSavedDocument}
                className="hidden sm:flex items-center space-x-1 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-md transition-colors"
                title="Load saved document"
              >
                <Settings size={16} />
                <span>Load</span>
              </button>
              
              <button
                onClick={handleExport}
                className="hidden sm:flex items-center space-x-1 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-md transition-colors"
                title="Export document"
              >
                <Download size={16} />
                <span>Export</span>
              </button>
              
              <button
                onClick={handleSave}
                className="flex items-center space-x-1 px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors"
                title="Save document"
              >
                <Save size={16} />
                <span>Save</span>
              </button>
            </div>
          </div>
        </div>
      </header>

      {/* Main Content */}
      <main className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div className="mb-6">
          <div className="max-w-4xl mx-auto">
            <div className="text-center mb-8">
              <h2 className="text-3xl font-bold text-gray-900 sm:text-4xl">
                Lexical Rich Text Editor
              </h2>
            </div>
            
            <LexicalEditorWithToolbar
              placeholder="Start typing your document here..."
              onChange={handleEditorChange}
            />
            
            {/* Features */}
            <div className="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
              <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div className="flex items-center mb-3">
                  <div className="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <FileText className="w-4 h-4 text-blue-600" />
                  </div>
                  <h3 className="text-lg font-semibold text-gray-900">Rich Formatting</h3>
                </div>
                <p className="text-gray-600 text-sm">
                  Bold, italic, underline, strikethrough, and inline code formatting with keyboard shortcuts.
                </p>
              </div>
              
              <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div className="flex items-center mb-3">
                  <div className="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <Settings className="w-4 h-4 text-green-600" />
                  </div>
                  <h3 className="text-lg font-semibold text-gray-900">Block Elements</h3>
                </div>
                <p className="text-gray-600 text-sm">
                  Headings, lists, quotes, code blocks, and paragraph formatting for structured content.
                </p>
              </div>
              
              <div className="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div className="flex items-center mb-3">
                  <div className="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                    <Download className="w-4 h-4 text-purple-600" />
                  </div>
                  <h3 className="text-lg font-semibold text-gray-900">Export & Save</h3>
                </div>
                <p className="text-gray-600 text-sm">
                  Save your work locally and export documents in JSON format for backup or sharing.
                </p>
              </div>
            </div>

            {/* Tips */}
            <div className="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
              <h4 className="text-lg font-semibold text-blue-900 mb-3">✨ Tips & Shortcuts</h4>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-800">
                <div>
                  <p><strong>⌘B / Ctrl+B:</strong> Bold text</p>
                  <p><strong>⌘I / Ctrl+I:</strong> Italic text</p>
                  <p><strong>⌘U / Ctrl+U:</strong> Underline text</p>
                </div>
                <div>
                  <p><strong>⌘Z / Ctrl+Z:</strong> Undo</p>
                  <p><strong>⌘Y / Ctrl+Y:</strong> Redo</p>
                  <p><strong>Tab:</strong> Indent lists</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>

      {/* Footer */}
      <footer className="bg-white border-t border-gray-200 mt-12">
        <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <div className="text-center text-sm text-gray-500">
            <p>
              Built with{' '}
              <a 
                href="https://lexical.dev" 
                target="_blank" 
                rel="noopener noreferrer"
                className="text-blue-600 hover:text-blue-800 underline"
              >
                Lexical
              </a>
              {' '}by Meta • Modern Rich Text Editor Framework
            </p>
          </div>
        </div>
      </footer>
    </div>
  );
};

export default EditorPage;