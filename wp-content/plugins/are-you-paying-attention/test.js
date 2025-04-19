// Register a block type that appears in editor.
wp.blocks.registerBlockType('myplugin/are-you-paying-attention', {
    title: 'Are You Paying Attention?',
    icon: 'smiley',
    category: 'common',
    edit: () => {
        // Every html element must be created by this method.
        // Shown in editor.
        return wp.element.createElement('h3', null, 'Test Admin')
    },
    save: () => {
        // Shown on front end (site page)
        return wp.element.createElement('h1', null, 'Test Front end')
    }
})
