FCKConfig.AutoDetectLanguage = false;

// Simple
FCKConfig.ToolbarSets['Simple'] = [
    ['Bold', 'Italic', 'StrikeThrough', '-', 'OrderedList', 'UnorderedList', '-', 'Link', 'Unlink', 'Indent', 'Outdent', 'Source', 'FontFormat']
];

// Full
FCKConfig.ToolbarSets['Full'] = [
    ['FontFormat', 'RemoveFormat', '-', 'Bold', 'Italic', 'Underline', 'StrikeThrough', '-', 'OrderedList', 'UnorderedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyFull'],
    '/',
    ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteWord', '-', 'Undo', 'Redo', '-', 'Link', 'Unlink', 'Anchor', '-', 'Indent', 'Outdent', '-', 'Subscript', 'Superscript', '-', 'TextColor', 'BGColor'],
    '/',
    ['Table', 'SpecialChar', '-', 'ShowBlocks', 'Source', '-', 'FitWindow'] // 'Image',
];