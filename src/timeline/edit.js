/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __, sprintf, setLocaleData } from "@wordpress/i18n";

/**
 * WordPress dependenices
 */
import {
	useBlockProps,
	BlockControls,
	InspectorControls,
	MediaPlaceholder,
	MediaUpload,
	MediaUploadCheck,
} from "@wordpress/block-editor";
import {
	Button,
	DropZone,
	FormFileUpload,
	PanelBody,
	RangeControl,
	TextControl,
	SelectControl,
	ToggleControl,
	ToolbarGroup,
	ToolbarItem,
	withNotices,
	Placeholder,
} from "@wordpress/components";
/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import "./editor.scss";
/**
 * External Dependencies
 */
const { filter, pick, get, map } = lodash;
import DRSModal from "../components/DRSModal";

import { Component, Fragment, useState } from "@wordpress/element";
import TimelineItem from "./timelineItem";
/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return { WPElement } Element to render.
 */
export default function Edit({
	attributes,
	isSelected,
	noticeUI,
	image,
	setAttributes,
}) {
	const [showDRSModal, setShowDRSModal] = useState(false);
	const { files } = attributes;
	function onSelectImages(images) {
		setAttributes({ files: images });
	}

	const blockProps = useBlockProps();

	function renderDRSButton() {
		return (
			<div>
				<Button
					onClick={(e) => setShowDRSModal(true)}
					isPressed={showDRSModal}
					variant="primary"
				>
					{__("Select Images")}
				</Button>
				{showDRSModal && (
					<DRSModal
						onClose={(e) => setShowDRSModal(false)}
						onSubmit={onSelectImages}
						multiple={true}
					/>
				)}
			</div>
		);
	}

	if (files.length === 0) {
		return (
			<div {...blockProps}>
				<Fragment>
					<Placeholder
						icon={image}
						label="DRS Timeline"
						instructions="Select all the images that you want to display in the carousel"
					>
						{renderDRSButton()}
					</Placeholder>
				</Fragment>
			</div>
		);
	}

	return (
		<>
			<div {...blockProps}>
				<div className="timeline-container">
					{files.map((item, index) => (
						<TimelineItem {...item} key={index}></TimelineItem>
					))}
				</div>
			</div>
		</>
	);
}
