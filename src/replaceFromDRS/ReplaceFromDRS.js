import { __ } from "@wordpress/i18n";
import { ToolbarButton } from "@wordpress/components";

import Modal from "../customMediaPlaceholder/DRSModal";

const { createHigherOrderComponent } = wp.compose;
const { BlockControls } = wp.blockEditor;
const { Fragment } = wp.element;

// specify what blocks should contain the replace from drs
const enableReplaceFromDrsOnBlocks = ["core/image", "core/video", "core/audio"];

/**
 * Higher order component to add replace from DRS to the specified blocks
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/filters/block-filters/
 * @see https://reactjs.org/docs/higher-order-components.html
 */
const withReplaceFromDRS = createHigherOrderComponent((BlockEdit) => {
	return (props) => {
		const { attributes, setAttributes, name } = props;
		const { isDRSClose } = attributes;

		// if not the required block
		if (!enableReplaceFromDrsOnBlocks.includes(name))
			return <BlockEdit {...props} />;
		console.log("Okay");
		return (
			<Fragment>
				<BlockEdit {...props} />
				<BlockControls group="other">
					<ToolbarButton onClick={() => setAttributes({ isDRSClose: true })}>
						{__("Replace from DRS")}
					</ToolbarButton>
				</BlockControls>
				{isDRSClose && (
					<Modal
						onClose={() => {
							setAttributes({ isDRSClose: false });
						}}
						onSubmit={(url) => setAttributes({ url })}
					/>
				)}
			</Fragment>
		);
	};
}, "withReplaceFromDRS");

export default withReplaceFromDRS;
