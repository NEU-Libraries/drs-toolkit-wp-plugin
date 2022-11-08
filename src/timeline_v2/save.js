/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from "@wordpress/i18n";

/**
 * WordPress dependenices
 */
import { useBlockProps } from "@wordpress/block-editor";
import { Button, Placeholder } from "@wordpress/components";
/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
// import "./editor.scss";
/**
 * External Dependencies
 */

// import DRSModal from "../components/DRSModal";

// import { Fragment, useState, useEffect } from "@wordpress/element";
import { convertToTimelineJSON } from "./utils";
/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return { WPElement } Element to render.
 */
export default function save({ attributes }) {
	// console.log("save", attributes);
	// const { files } = attributes;
	// const options = {
	// 	width: "100%",
	// 	height: "600px",
	// };
	// if (files.length != 0) {
	// 	new TL.Timeline(
	// 		"save-timeline-embed",
	// 		convertToTimelineJSON(files),
	// 		options
	// 	);
	// }

	// const blockProps = useBlockProps.save();
	// if (files.length != 0)
	// 	return (
	// 		<>
	// 			<div>
	// 				<div className="timeline-container">
	// 					<div id="save-timeline-embed">{/* {JSON.stringify(files)} */}</div>
	// 				</div>
	// 			</div>
	// 		</>
	// 	);

	return <></>;
}
