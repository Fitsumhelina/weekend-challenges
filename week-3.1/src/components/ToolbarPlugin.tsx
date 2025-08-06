import React, { useState, useCallback, useEffect } from 'react';
import {
  $getSelection,
  $isRangeSelection,
  FORMAT_TEXT_COMMAND,
  SELECTION_CHANGE_COMMAND,
  FORMAT_ELEMENT_COMMAND,
  UNDO_COMMAND,
  REDO_COMMAND,
  CAN_UNDO_COMMAND,
  CAN_REDO_COMMAND,
  $createParagraphNode,
} from 'lexical';
import {
  $setBlocksType,
} from '@lexical/selection';
import {
  INSERT_ORDERED_LIST_COMMAND,
  INSERT_UNORDERED_LIST_COMMAND,
  REMOVE_LIST_COMMAND,
  $isListNode,
} from '@lexical/list';
import { $createHeadingNode, $createQuoteNode } from '@lexical/rich-text';
import { $createCodeNode } from '@lexical/code';
import { useLexicalComposerContext } from '@lexical/react/LexicalComposerContext';
import { mergeRegister } from '@lexical/utils';
import {
  Bold,
  Italic,
  Underline,
  Strikethrough,
  Code,
  Quote,
  List,
  ListOrdered,
  AlignLeft,
  AlignCenter,
  AlignRight,
  AlignJustify,
  Undo,
  Redo,
  Type,
  Heading1,
  Heading2,
  Heading3,
  ChevronDown,
} from 'lucide-react';

const blockTypeToBlockName = {
  bullet: 'Bulleted List',
  check: 'Check List',
  code: 'Code Block',
  h1: 'Heading 1',
  h2: 'Heading 2',
  h3: 'Heading 3',
  h4: 'Heading 4',
  h5: 'Heading 5',
  h6: 'Heading 6',
  number: 'Numbered List',
  paragraph: 'Normal',
  quote: 'Quote',
};

interface BlockTypeDropdownProps {
  blockType: string;
  formatParagraph: () => void;
  formatHeading: (headingSize: 'h1' | 'h2' | 'h3' | 'h4' | 'h5' | 'h6') => void;
  formatBulletList: () => void;
  formatNumberedList: () => void;
  formatQuote: () => void;
  formatCode: () => void;
}

const BlockTypeDropdown: React.FC<BlockTypeDropdownProps> = ({
  blockType,
  formatParagraph,
  formatHeading,
  formatBulletList,
  formatNumberedList,
  formatQuote,
  formatCode,
}) => {
  const [showDropDown, setShowDropDown] = useState(false);

  return (
    <div className="relative">
      <button
        className="flex items-center gap-2 px-3 py-1.5 text-sm border border-gray-200 rounded hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500"
        onClick={() => setShowDropDown(!showDropDown)}
      >
        {/* <span className="min-w-0 truncate">{blockTypeToBlockName[blockType] || 'Normal'}</span> */}
        <ChevronDown size={14} />
      </button>
      {showDropDown && (
        <>
          <div 
            className="fixed inset-0 z-40" 
            onClick={() => setShowDropDown(false)}
          />
          <div className="absolute top-full left-0 mt-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
            <button
              className="w-full px-3 py-2 text-left text-sm hover:bg-gray-50 flex items-center gap-2"
              onClick={() => {
                formatParagraph();
                setShowDropDown(false);
              }}
            >
              <Type size={16} />
              Normal
            </button>
            <button
              className="w-full px-3 py-2 text-left text-lg font-bold hover:bg-gray-50 flex items-center gap-2"
              onClick={() => {
                formatHeading('h1');
                setShowDropDown(false);
              }}
            >
              <Heading1 size={16} />
              Heading 1
            </button>
            <button
              className="w-full px-3 py-2 text-left text-base font-semibold hover:bg-gray-50 flex items-center gap-2"
              onClick={() => {
                formatHeading('h2');
                setShowDropDown(false);
              }}
            >
              <Heading2 size={16} />
              Heading 2
            </button>
            <button
              className="w-full px-3 py-2 text-left text-sm font-semibold hover:bg-gray-50 flex items-center gap-2"
              onClick={() => {
                formatHeading('h3');
                setShowDropDown(false);
              }}
            >
              <Heading3 size={16} />
              Heading 3
            </button>
            <button
              className="w-full px-3 py-2 text-left text-sm hover:bg-gray-50 flex items-center gap-2"
              onClick={() => {
                formatBulletList();
                setShowDropDown(false);
              }}
            >
              <List size={16} />
              Bullet List
            </button>
            <button
              className="w-full px-3 py-2 text-left text-sm hover:bg-gray-50 flex items-center gap-2"
              onClick={() => {
                formatNumberedList();
                setShowDropDown(false);
              }}
            >
              <ListOrdered size={16} />
              Numbered List
            </button>
            <button
              className="w-full px-3 py-2 text-left text-sm italic hover:bg-gray-50 flex items-center gap-2"
              onClick={() => {
                formatQuote();
                setShowDropDown(false);
              }}
            >
              <Quote size={16} />
              Quote
            </button>
            <button
              className="w-full px-3 py-2 text-left text-sm font-mono hover:bg-gray-50 flex items-center gap-2"
              onClick={() => {
                formatCode();
                setShowDropDown(false);
              }}
            >
              <Code size={16} />
              Code Block
            </button>
          </div>
        </>
      )}
    </div>
  );
};

const ToolbarPlugin: React.FC = () => {
  const [editor] = useLexicalComposerContext();
  const [activeEditor, setActiveEditor] = useState(editor);
  const [blockType, setBlockType] = useState('paragraph');
  const [selectedElementKey, setSelectedElementKey] = useState<string | null>(null);
  const [isBold, setIsBold] = useState(false);
  const [isItalic, setIsItalic] = useState(false);
  const [isUnderline, setIsUnderline] = useState(false);
  const [isStrikethrough, setIsStrikethrough] = useState(false);
  const [isCode, setIsCode] = useState(false);
  const [canUndo, setCanUndo] = useState(false);
  const [canRedo, setCanRedo] = useState(false);

  const updateToolbar = useCallback(() => {
    const selection = $getSelection();
    if ($isRangeSelection(selection)) {
      const anchorNode = selection.anchor.getNode();
      let element =
        anchorNode.getKey() === 'root'
          ? anchorNode
          : anchorNode.getTopLevelElementOrThrow();
      const elementKey = element.getKey();
      const elementDOM = activeEditor.getElementByKey(elementKey);

      setIsBold(selection.hasFormat('bold'));
      setIsItalic(selection.hasFormat('italic'));
      setIsUnderline(selection.hasFormat('underline'));
      setIsStrikethrough(selection.hasFormat('strikethrough'));
      setIsCode(selection.hasFormat('code'));

      const rootElement = editor.getRootElement();
      if (elementDOM !== null && rootElement !== null) {
        setSelectedElementKey(elementKey);

        if ($isListNode(element)) {
          const parentList = element.getParent();
          const type = $isListNode(parentList)
            ? parentList.getListType()
            : element.getListType();
          setBlockType(type);
        } else {
          const type = element.getType();
          if (type in blockTypeToBlockName) {
            setBlockType(type);
          }
        }
      }
    }
  }, [activeEditor, editor]);

  useEffect(() => {
    return editor.registerCommand(
      SELECTION_CHANGE_COMMAND,
      (_payload, newEditor) => {
        updateToolbar();
        setActiveEditor(newEditor);
        return false;
      },
      1,
    );
  }, [editor, updateToolbar]);

  useEffect(() => {
    return mergeRegister(
      editor.registerUpdateListener(({ editorState }) => {
        editorState.read(() => {
          updateToolbar();
        });
      }),
      editor.registerCommand(
        CAN_UNDO_COMMAND,
        (payload) => {
          setCanUndo(payload);
          return false;
        },
        1,
      ),
      editor.registerCommand(
        CAN_REDO_COMMAND,
        (payload) => {
          setCanRedo(payload);
          return false;
        },
        1,
      ),
    );
  }, [editor, updateToolbar]);

  const formatParagraph = () => {
    editor.update(() => {
      const selection = $getSelection();
      if ($isRangeSelection(selection)) {
        $setBlocksType(selection, () => $createParagraphNode());
      }
    });
  };

  const formatHeading = (headingSize: 'h1' | 'h2' | 'h3' | 'h4' | 'h5' | 'h6') => {
    if (blockType !== headingSize) {
      editor.update(() => {
        const selection = $getSelection();
        if ($isRangeSelection(selection)) {
          $setBlocksType(selection, () => $createHeadingNode(headingSize));
        }
      });
    }
  };

  const formatBulletList = () => {
    if (blockType !== 'bullet') {
      editor.dispatchCommand(INSERT_UNORDERED_LIST_COMMAND, undefined);
    } else {
      editor.dispatchCommand(REMOVE_LIST_COMMAND, undefined);
    }
  };

  const formatNumberedList = () => {
    if (blockType !== 'number') {
      editor.dispatchCommand(INSERT_ORDERED_LIST_COMMAND, undefined);
    } else {
      editor.dispatchCommand(REMOVE_LIST_COMMAND, undefined);
    }
  };

  const formatQuote = () => {
    if (blockType !== 'quote') {
      editor.update(() => {
        const selection = $getSelection();
        if ($isRangeSelection(selection)) {
          $setBlocksType(selection, () => $createQuoteNode());
        }
      });
    }
  };

  const formatCode = () => {
    if (blockType !== 'code') {
      editor.update(() => {
        const selection = $getSelection();
        if ($isRangeSelection(selection)) {
          $setBlocksType(selection, () => $createCodeNode());
        }
      });
    }
  };

  const Divider = () => <div className="w-px h-6 bg-gray-300 mx-1" />;

  return (
    <div className="flex flex-wrap items-center gap-1 p-2 border-b border-gray-200 bg-gray-50">
      {/* Undo/Redo */}
      <button
        disabled={!canUndo}
        onClick={() => {
          editor.dispatchCommand(UNDO_COMMAND, undefined);
        }}
        title="Undo (⌘Z)"
        className={`p-2 rounded hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors ${
          !canUndo ? 'text-gray-400' : 'text-gray-600'
        }`}
      >
        <Undo size={16} />
      </button>
      <button
        disabled={!canRedo}
        onClick={() => {
          editor.dispatchCommand(REDO_COMMAND, undefined);
        }}
        title="Redo (⌘Y)"
        className={`p-2 rounded hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors ${
          !canRedo ? 'text-gray-400' : 'text-gray-600'
        }`}
      >
        <Redo size={16} />
      </button>

      <Divider />

      {/* Block Type Dropdown */}
      <BlockTypeDropdown
        blockType={blockType}
        formatParagraph={formatParagraph}
        formatHeading={formatHeading}
        formatBulletList={formatBulletList}
        formatNumberedList={formatNumberedList}
        formatQuote={formatQuote}
        formatCode={formatCode}
      />

      <Divider />

      {/* Text Formatting */}
      <button
        onClick={() => {
          editor.dispatchCommand(FORMAT_TEXT_COMMAND, 'bold');
        }}
        className={`p-2 rounded hover:bg-gray-100 transition-colors ${
          isBold ? 'bg-blue-100 text-blue-600' : 'text-gray-600'
        }`}
        title="Bold (⌘B)"
      >
        <Bold size={16} />
      </button>

      <button
        onClick={() => {
          editor.dispatchCommand(FORMAT_TEXT_COMMAND, 'italic');
        }}
        className={`p-2 rounded hover:bg-gray-100 transition-colors ${
          isItalic ? 'bg-blue-100 text-blue-600' : 'text-gray-600'
        }`}
        title="Italic (⌘I)"
      >
        <Italic size={16} />
      </button>

      <button
        onClick={() => {
          editor.dispatchCommand(FORMAT_TEXT_COMMAND, 'underline');
        }}
        className={`p-2 rounded hover:bg-gray-100 transition-colors ${
          isUnderline ? 'bg-blue-100 text-blue-600' : 'text-gray-600'
        }`}
        title="Underline (⌘U)"
      >
        <Underline size={16} />
      </button>

      <button
        onClick={() => {
          editor.dispatchCommand(FORMAT_TEXT_COMMAND, 'strikethrough');
        }}
        className={`p-2 rounded hover:bg-gray-100 transition-colors ${
          isStrikethrough ? 'bg-blue-100 text-blue-600' : 'text-gray-600'
        }`}
        title="Strikethrough"
      >
        <Strikethrough size={16} />
      </button>

      <button
        onClick={() => {
          editor.dispatchCommand(FORMAT_TEXT_COMMAND, 'code');
        }}
        className={`p-2 rounded hover:bg-gray-100 transition-colors ${
          isCode ? 'bg-blue-100 text-blue-600' : 'text-gray-600'
        }`}
        title="Code"
      >
        <Code size={16} />
      </button>

      <Divider />

      {/* Alignment */}
      <button
        onClick={() => {
          editor.dispatchCommand(FORMAT_ELEMENT_COMMAND, 'left');
        }}
        className="p-2 rounded hover:bg-gray-100 text-gray-600 transition-colors"
        title="Left Align"
      >
        <AlignLeft size={16} />
      </button>

      <button
        onClick={() => {
          editor.dispatchCommand(FORMAT_ELEMENT_COMMAND, 'center');
        }}
        className="p-2 rounded hover:bg-gray-100 text-gray-600 transition-colors"
        title="Center Align"
      >
        <AlignCenter size={16} />
      </button>

      <button
        onClick={() => {
          editor.dispatchCommand(FORMAT_ELEMENT_COMMAND, 'right');
        }}
        className="p-2 rounded hover:bg-gray-100 text-gray-600 transition-colors"
        title="Right Align"
      >
        <AlignRight size={16} />
      </button>

      <button
        onClick={() => {
          editor.dispatchCommand(FORMAT_ELEMENT_COMMAND, 'justify');
        }}
        className="p-2 rounded hover:bg-gray-100 text-gray-600 transition-colors"
        title="Justify"
      >
        <AlignJustify size={16} />
      </button>
    </div>
  );
};

export default ToolbarPlugin;