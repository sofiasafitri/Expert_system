import {
    ClassicEditor,
    AccessibilityHelp,
    Autoformat,
    AutoLink,
    Autosave,
    BalloonToolbar,
    BlockQuote,
    Bold,
    Essentials,
    Heading,
    HorizontalLine,
    Indent,
    IndentBlock,
    Italic,
    Link,
    List,
    Paragraph,
    SelectAll,
    SpecialCharacters,
    SpecialCharactersArrows,
    SpecialCharactersCurrency,
    SpecialCharactersEssentials,
    SpecialCharactersLatin,
    SpecialCharactersMathematical,
    SpecialCharactersText,
    Subscript,
    Superscript,
    Table,
    TableToolbar,
    TextTransformation,
    Underline,
    Undo
} from 'ckeditor5';

import translations from 'ckeditor5/translations/id.js';

const editorConfig = {
    toolbar: {
        items: [
            'undo',
            'redo',
            '|',
            'heading',
            '|',
            'bold',
            'italic',
            'underline',
            '|',
            'link',
            'insertTable',
            'blockQuote',
            '|',
            'bulletedList',
            'numberedList',
            'outdent',
            'indent'
        ],
        shouldNotGroupWhenFull: false
    },
    plugins: [
        AccessibilityHelp,
        Autoformat,
        AutoLink,
        Autosave,
        BalloonToolbar,
        BlockQuote,
        Bold,
        Essentials,
        Heading,
        HorizontalLine,
        Indent,
        IndentBlock,
        Italic,
        Link,
        List,
        Paragraph,
        SelectAll,
        SpecialCharacters,
        SpecialCharactersArrows,
        SpecialCharactersCurrency,
        SpecialCharactersEssentials,
        SpecialCharactersLatin,
        SpecialCharactersMathematical,
        SpecialCharactersText,
        Subscript,
        Superscript,
        Table,
        TableToolbar,
        TextTransformation,
        Underline,
        Undo
    ],
    balloonToolbar: ['bold', 'italic', '|', 'link', '|', 'bulletedList', 'numberedList'],
    heading: {
        options: [{
            model: 'paragraph',
            title: 'Paragraph',
            class: 'ck-heading_paragraph'
        },
        {
            model: 'heading1',
            view: 'h1',
            title: 'Heading 1',
            class: 'ck-heading_heading1'
        },
        {
            model: 'heading2',
            view: 'h2',
            title: 'Heading 2',
            class: 'ck-heading_heading2'
        },
        {
            model: 'heading3',
            view: 'h3',
            title: 'Heading 3',
            class: 'ck-heading_heading3'
        },
        {
            model: 'heading4',
            view: 'h4',
            title: 'Heading 4',
            class: 'ck-heading_heading4'
        },
        {
            model: 'heading5',
            view: 'h5',
            title: 'Heading 5',
            class: 'ck-heading_heading5'
        },
        {
            model: 'heading6',
            view: 'h6',
            title: 'Heading 6',
            class: 'ck-heading_heading6'
        }
        ]
    },
    language: 'id',
    link: {
        addTargetToExternalLinks: true,
        defaultProtocol: 'https://',
        decorators: {
            toggleDownloadable: {
                mode: 'manual',
                label: 'Downloadable',
                attributes: {
                    download: 'file'
                }
            }
        }
    },
    menuBar: {
        isVisible: true
    },
    table: {
        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
    },
    translations: [translations]
};

// Menambahkan initialData jika kondisi terpenuhi
if (document.querySelector('[data-form-page="create"]')) {
    editorConfig.initialData = '<h4>Deskripsi:</h4>\n<p>*Isi deskripsi</p>\n<h4>Saran:</h4>\n<p>*Isi saran</p>\n';
};

ClassicEditor.create(document.querySelector('#description'), editorConfig)
    .catch(error => {
        console.error(error);
    });