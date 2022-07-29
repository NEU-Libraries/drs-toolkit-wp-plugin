/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from "@wordpress/i18n";

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { useBlockProps } from "@wordpress/block-editor";
import { Placeholder, Button } from "@wordpress/components";
import { image } from "@wordpress/icons";
import { useState } from "@wordpress/element";

import { BlockControls, InspectorControls } from "@wordpress/block-editor";
import {
	ToggleControl,
	PanelBody,
	ToolbarButton,
	ToolbarGroup,
	SelectControl,
} from "@wordpress/components";

import DRSModal from "../components/DRSModal";
import "./editor.scss";

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
	const [showDRSModal, setShowDRSModal] = useState(false);
	const { gallery_images, pauseOnHover, direction } = attributes;

	function onSelectImages(images) {
		const contentImages = images.map((imgUrl) => ({
			url: imgUrl,
		}));
		setAttributes({ gallery_images: contentImages });
	}

	const hasImages = gallery_images.length > 0;

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

	return (
		<div {...useBlockProps()}>
			{hasImages ? (
				<>
					<InspectorControls>
						<PanelBody title={__("General", "scrollable-gallery")} initialOpen>
							<ToggleControl
								checked={pauseOnHover}
								label={__("Pause on hover", "scrollable-gallery")}
								onChange={() =>
									setAttributes({
										pauseOnHover: !pauseOnHover,
									})
								}
							/>
							<SelectControl
								value={direction}
								options={[
									{ value: "right", label: "Right" },
									{ value: "left", label: "Left" },
								]}
								label={__("Direction", "scrollable-gallery")}
								onChange={(newDirection) =>
									setAttributes({ direction: newDirection })
								}
							/>
						</PanelBody>
					</InspectorControls>
					<figure className="scrollable-gallery-inner-container">
						{gallery_images.map((image, index) => (
							<img key={index} src={image.url} />
						))}
					</figure>
				</>
			) : (
				<Placeholder
					icon={image}
					label="DRS Gallery Carousel"
					instructions="Select all the images that you want to display in the carousel"
				>
					{renderDRSButton()}
				</Placeholder>
			)}
		</div>
	);
}
