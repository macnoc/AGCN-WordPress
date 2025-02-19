const {
  createHigherOrderComponent
} = wp.compose;
const {
  Fragment
} = wp.element;
const {
  InspectorControls
} = wp.blockEditor;
const {
  PanelBody,
  TextControl,
  ButtonGroup,
  Button,
  BaseControl
} = wp.components;
const {
  addFilter
} = wp.hooks;

// Define which blocks should have AGCN controls
const ALLOWED_BLOCKS = ['core/paragraph', 'core/group', 'core/code', 'core/details', 'core/embed', 'core/footnotes', 'core/image', 'core/form', 'core/html', 'core/list', 'core/post-content', 'core/post-excerpt', 'core/post-featured-image', 'core/table', 'core/verse', 'core/video'];
const withAGCNControls = createHigherOrderComponent(BlockEdit => {
  return props => {
    if (!ALLOWED_BLOCKS.includes(props.name)) {
      return /*#__PURE__*/React.createElement(BlockEdit, props);
    }
    const {
      attributes,
      setAttributes
    } = props;
    return /*#__PURE__*/React.createElement(Fragment, null, /*#__PURE__*/React.createElement(BlockEdit, props), /*#__PURE__*/React.createElement(InspectorControls, null, /*#__PURE__*/React.createElement(PanelBody, {
      title: "AI Content Notice",
      initialOpen: false,
      className: "agcn-panel"
    }, /*#__PURE__*/React.createElement(TextControl, {
      label: "AI Content",
      help: /*#__PURE__*/React.createElement("span", null, "Enter section slug.", ' ', /*#__PURE__*/React.createElement("a", {
        href: "/wp-admin/options-general.php?page=agcn-settings&tab=content",
        target: "_blank",
        rel: "noopener noreferrer"
      }, "Manage Sections")),
      value: attributes.aiContent || '',
      onChange: value => setAttributes({
        aiContent: value
      })
    }), /*#__PURE__*/React.createElement(BaseControl, {
      label: "Position"
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: 'flex',
        flexDirection: 'column',
        gap: '4px'
      }
    }, /*#__PURE__*/React.createElement(ButtonGroup, null, ['Left', 'Center', 'Right'].map(position => /*#__PURE__*/React.createElement(Button, {
      key: position,
      isPrimary: attributes.agcnPosition === position.toLowerCase(),
      isSecondary: attributes.agcnPosition !== position.toLowerCase(),
      onClick: () => setAttributes({
        agcnPosition: position.toLowerCase()
      })
    }, position))))))));
  };
}, 'withAGCNControls');
addFilter('editor.BlockEdit', 'agcn/with-inspector-controls', withAGCNControls);