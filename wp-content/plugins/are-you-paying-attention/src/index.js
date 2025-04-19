// Register a block type that appears in editor.
wp.blocks.registerBlockType('myplugin/are-you-paying-attention', {
    title: 'Are You Paying Attention?',
    icon: 'smiley',
    category: 'common',
    edit: () => {
        // Every html element must be created by this method.
        // Shown in editor.
        // return wp.element.createElement('h3', null, 'Test Admin')
        return (
            <div>
                <p>Hi, paragraph</p>
                <h4>heading 4</h4>
            </div>
        )
    },
    save: () => {
        // Shown on front end (site page)
        return (
            <>
            <h2>h2</h2>
            <h3>h3</h3>
            </>
        )
    }
})
