import React, { useState } from 'react';
import { EditorState } from 'lexical';
import { LexicalComposer } from '@lexical/react/LexicalComposer';
import { RichTextPlugin } from '@lexical/react/LexicalRichTextPlugin';
import { ContentEditable } from '@lexical/react/LexicalContentEditable';
import { HistoryPlugin } from '@lexical/react/LexicalHistoryPlugin';
import { OnChangePlugin } from '@lexical/react/LexicalOnChangePlugin';
import { AutoFocusPlugin } from '@lexical/react/LexicalAutoFocusPlugin';
import { ListPlugin } from '@lexical/react/LexicalListPlugin';
import { LinkPlugin } from '@lexical/react/LexicalLinkPlugin';
import { LexicalErrorBoundary } from '@lexical/react/LexicalErrorBoundary';
import { HeadingNode, QuoteNode } from '@lexical/rich-text';
import { ListItemNode, ListNode } from '@lexical/list';
import { CodeHighlightNode, CodeNode } from '@lexical/code';
import { AutoLinkNode, LinkNode } from '@lexical/link';
import ToolbarPlugin from './ToolbarPlugin';

const theme = {
  text: {
    bold: 'font-bold',
    italic: 'italic',
    underline: 'underline',
    strikethrough: 'line-through',
    code: 'bg-gray-100 px-1 py-0.5 rounded font-mono text-sm',
  },
  heading: {
    h1: 'text-3xl font-bold my-4 leading-tight',
    h2: 'text-2xl font-semibold my-3 leading-tight',
    h3: 'text-xl font-medium my-2 leading-tight',
    h4: 'text-lg font-medium my-2 leading-tight',
    h5: 'text-base font-medium my-1 leading-tight',
    h6: 'text-sm font-medium my-1 leading-tight',
  },
  list: {
    nested: {
      listitem: 'list-none',
    },
    ol: 'list-decimal list-inside my-2 space-y-1',
    ul: 'list-disc list-inside my-2 space-y-1',
    listitem: 'my-1',
  },
  quote: 'border-l-4 border-gray-300 pl-4 italic text-gray-600 my-4 bg-gray-50 py-2 rounded-r',
  code: 'bg-gray-900 text-gray-100 p-4 rounded-md font-mono text-sm my-4 block overflow-x-auto',
  paragraph: 'my-2 leading-relaxed',
  link: 'text-blue-600 underline hover:text-blue-800 cursor-pointer',
};

interface LexicalEditorWithToolbarProps {
  placeholder?: string;
  onChange?: (editorState: EditorState) => void;
  initialValue?: string;
}

const LexicalEditorWithToolbar: React.FC<LexicalEditorWithToolbarProps> = ({
  placeholder = "Enter some rich text...",
  onChange,
  initialValue = '',
}) => {
  const [editorState, setEditorState] = useState<EditorState>();
  const [wordCount, setWordCount] = useState(0);
  const [charCount, setCharCount] = useState(0);

  const initialConfig = {
    namespace: 'LexicalEditor',
    theme,
    onError: (error: Error) => {
      console.error('Lexical error:', error);
    },
    nodes: [
      HeadingNode,
      ListNode,
      ListItemNode,
      QuoteNode,
      CodeNode,
      CodeHighlightNode,
      LinkNode,
      AutoLinkNode,
    ],
    editorState: initialValue || undefined,
  };

  const handleOnChange = (editorState: EditorState) => {
    setEditorState(editorState);
    
    // Count words and characters
    editorState.read(() => {
      const textContent = editorState.read(() => {
        const root = editorState._nodeMap.get('root');
        return root ? root.getTextContent() : '';
      });
      
      const words = textContent.trim() 
        ? textContent.trim().split(/\s+/).length 
        : 0;
      const characters = textContent.length;
      
      setWordCount(words);
      setCharCount(characters);
    });

    // Call parent onChange if provided
    if (onChange) {
      onChange(editorState);
    }
  };

  return (
    <div className="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden max-w-5xl mx-auto">
      <LexicalComposer initialConfig={initialConfig}>
        <ToolbarPlugin />
        <div className="relative">
          <RichTextPlugin
            contentEditable={
              <ContentEditable 
                className="min-h-96 p-6 focus:outline-none resize-none text-base leading-relaxed max-w-none
                  [&_*]:max-w-none
                  [&_pre]:whitespace-pre-wrap
                  [&_pre]:break-words
                  [&_code]:break-words"
                spellCheck={true}
              />
            }
            placeholder={
              <div className="absolute top-6 left-6 text-gray-400 pointer-events-none select-none">
                {placeholder}
              </div>
            }
            ErrorBoundary={LexicalErrorBoundary}
            />
          <OnChangePlugin onChange={handleOnChange} />
          <HistoryPlugin />
          <AutoFocusPlugin />
          <ListPlugin />
          <LinkPlugin />
        </div>
        
        {/* Status Bar */}
        <div className="border-t border-gray-200 bg-gray-50 px-4 py-2 flex justify-between items-center text-sm text-gray-600">
          <div className="flex items-center gap-4">
            <span>Words: {wordCount}</span>
            <span>Characters: {charCount}</span>
          </div>
          
          <div className="flex items-center gap-2 text-xs text-gray-500">
            <span>Lexical Editor</span>
          </div>
        </div>
      </LexicalComposer>
    </div>
  );
};

export default LexicalEditorWithToolbar;