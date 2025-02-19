const { createHigherOrderComponent } = wp.compose;
const { Fragment } = wp.element;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, TextControl, ButtonGroup, Button, BaseControl } = wp.components;
const { addFilter } = wp.hooks;

// Define which blocks should have AGCN controls
const ALLOWED_BLOCKS = [
    'core/paragraph',
    'core/group',
    'core/code',
    'core/details',
    'core/embed',
    'core/footnotes',
    'core/image',
    'core/form',
    'core/html',
    'core/list',
    'core/post-content',
    'core/post-excerpt',
    'core/post-featured-image',
    'core/table',
    'core/verse',
    'core/video'
];

const withAGCNControls = createHigherOrderComponent((BlockEdit) => {
    return (props) => {
        if (!ALLOWED_BLOCKS.includes(props.name)) {
            return <BlockEdit {...props} />;
        }

        const { attributes, setAttributes } = props;

        return (
            <Fragment>
                <BlockEdit {...props} />
                <InspectorControls>
                    <PanelBody
                        title="AI Content Notice"
                        initialOpen={false}
                        className="agcn-panel"
                    >
                        <TextControl
                            label="AI Content"
                            help={(
                                <span>
                                    Enter section slug.{' '}
                                    <a href="/wp-admin/options-general.php?page=agcn-settings&tab=content" target="_blank" rel="noopener noreferrer">
                                        Manage Sections
                                    </a>
                                </span>
                            )}
                            value={attributes.aiContent || ''}
                            onChange={(value) => setAttributes({ aiContent: value })}
                        />

                        <BaseControl label="Position">
                            <div style={{ display: 'flex', flexDirection: 'column', gap: '4px' }}>
                                <ButtonGroup>
                                    {['Left', 'Center', 'Right'].map((position) => (
                                        <Button
                                            key={position}
                                            isPrimary={attributes.agcnPosition === position.toLowerCase()}
                                            isSecondary={attributes.agcnPosition !== position.toLowerCase()}
                                            onClick={() => setAttributes({ agcnPosition: position.toLowerCase() })}
                                        >
                                            {position}
                                        </Button>
                                    ))}
                                </ButtonGroup>
                            </div>
                        </BaseControl>
                    </PanelBody>
                </InspectorControls>
            </Fragment>
        );
    };
}, 'withAGCNControls');

addFilter(
    'editor.BlockEdit',
    'agcn/with-inspector-controls',
    withAGCNControls
);