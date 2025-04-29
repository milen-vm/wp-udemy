import './index.scss'
import {TextControl, Flex, FlexBlock, FlexItem, Button, Icon} from '@wordpress/components'

// Register a block type that appears in editor.
wp.blocks.registerBlockType('myplugin/are-you-paying-attention', {
    title: 'Are You Paying Attention?',
    icon: 'smiley',
    category: 'common',
    attributes: {
        skyColor: {
            type: 'string',
            // source: 'text',
            // selector: '.skyColor'
        },
        grassColor: {
            type: 'string',
            // source: 'text',
            // selector: '.grassColor'
        }
    },
    edit: EditComponent,
    save: (props) => {
        // Shown on front end (site page)
        /**
         * The span and the css class are needed for React to extract values of two props from the text
         * saved in database as html string. This is not mandatory and can be removed. In that way WP saves
         * values of pros as coment in html. The 'source' and 'selector' must be removed. This way is better.
         */
        return null     // when return null no static html saved in db
    }
    // array of objects of old versions, to avoid a error whit old data in db on changing html format or someting other
    // every time when html is changed, must add new object to deprecated array
    // deprecated: [
    //     {
    //         attributes: {
    //             skyColor: {
    //                 type: 'string',
    //             },
    //             grassColor: {
    //                 type: 'string',
    //             }
    //         },
    //         save: (props) => {
    //             return (
    //                 <>
    //                 <h6>Today the sky is compleatly <span className="skyColor">{props.attributes.skyColor}</span> and grass is <span className="grassColor">{props.attributes.grassColor}</span>.</h6>
    //                 </>
    //             )
    //         },
    //     },
    //     {
    //         attributes: {
    //             skyColor: {
    //                 type: 'string',
    //             },
    //             grassColor: {
    //                 type: 'string',
    //             }
    //         },
    //         save: (props) => {
    //             return (
    //                 <>
    //                 <h3>Today the sky is <span className="skyColor">{props.attributes.skyColor}</span> and grass is <span className="grassColor">{props.attributes.grassColor}</span>.</h3>
    //                 </>
    //             )
    //         },
    //     },
    //     {
    //         attributes: {
    //             skyColor: {
    //                 type: 'string',
    //             },
    //             grassColor: {
    //                 type: 'string',
    //             }
    //         },
    //         save: (props) => {
    //             return (
    //                 <>
    //                 <p>Today the sky is <span className="skyColor">{props.attributes.skyColor}</span> and grass is <span className="grassColor">{props.attributes.grassColor}</span>.</p>
    //                 </>
    //             )
    //         }
    //     }
    // ]
})

function EditComponent(props) {
    // Shown in editor.
    // Every html element must be created by this method.
    // return wp.element.createElement('h3', null, 'Test Admin')

    function updateSkyColor(event) {
        props.setAttributes({skyColor: event.target.value})
    }

    function updateGrassColor(event) {
        props.setAttributes({grassColor: event.target.value})
    }

    return (
        <div className="paying-attention-edit-block">
            <TextControl label="Question:" />
            <p>Answers:</p>
            <Flex>
                <FlexBlock>
                    <TextControl />
                </FlexBlock>
                <FlexItem>
                    <Button>
                        <Icon icon="star-empty" />
                    </Button>
                </FlexItem>
                <FlexItem>
                    <Button>Delete</Button>
                </FlexItem>
            </Flex>
        </div>
    )
}
