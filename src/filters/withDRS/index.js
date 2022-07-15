/**
 * @module WithDRS
 *
 * This file adds another button to the core blocks an option to replace from DRS
 */

import { __ } from "@wordpress/i18n";
import { ToolbarButton } from "@wordpress/components";

// destructuring
const { createHigherOrderComponent } = wp.compose;
const { BlockControls } = wp.blockEditor;
const { Fragment } = wp.element;

import Modal from "../../components/DRSModal";

// specify what blocks should contain the replace from drs
const enableReplaceFromDrsOnBlocks = ["core/image", "core/video", "core/audio"];

/**
 * Higher order component to add replace from DRS to the specified blocks
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/filters/block-filters/
 * @see https://reactjs.org/docs/higher-order-components.html
 */
const withDRS = createHigherOrderComponent((BlockEdit) => {
	return (props) => {
		const { attributes, setAttributes, name } = props;
		const { isDRSClose } = attributes;

		// if not the required block
		if (!enableReplaceFromDrsOnBlocks.includes(name))
			return <BlockEdit {...props} />; // passing all the props to child
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
}, "withDRS");

export default withDRS;
