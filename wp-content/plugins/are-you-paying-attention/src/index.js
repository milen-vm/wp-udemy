// Register a block type that appears in editor.
wp.blocks.registerBlockType('myplugin/are-you-paying-attention', {
    title: 'Are You Paying Attention?',
    icon: 'smiley',
    category: 'common',
    attributes: {
        skyColor: {
            type: 'string',
            source: 'text',
            selector: '.skyColor'
        },
        grassColor: {
            type: 'string',
            source: 'text',
            selector: '.grassColor'
        }
    },
    edit: (props) => {
        // Every html element must be created by this method.
        // Shown in editor.
        // return wp.element.createElement('h3', null, 'Test Admin')

        function updateSkyColor(event) {
            props.setAttributes({skyColor: event.target.value})
        }

        function updateGrassColor(event) {
            props.setAttributes({grassColor: event.target.value})
        }

        return (
            <div>
                <input type="text" name="sky color" value={props.attributes.skyColor} onChange={updateSkyColor} />
                <input type="text" name="grass color" value={props.attributes.grassColor} onChange={updateGrassColor} />
            </div>
        )
    },
    save: (props) => {
        // Shown on front end (site page)
        /**
         * The span and the css class are needed for React to extract values of two props from the text
         * saved in database as html string.
         */
        return (
            <>
            <p>Today the sky is <span className="skyColor">{props.attributes.skyColor}</span> and grass is <span className="grassColor">{props.attributes.grassColor}</span>.</p>
            </>
        )
    }
})
