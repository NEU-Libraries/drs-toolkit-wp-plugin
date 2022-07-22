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

import ImageGallery from "react-image-gallery";

import DRSModal from "../components/DRSModal";

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
	const { gallery_images } = attributes;

	function onSelectImages(images) {
		const contentImages = images.map((imgUrl) => ({
			original: imgUrl,
			thumbnail: imgUrl,
		}));
		setAttributes({ gallery_images: contentImages });
	}

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
			{gallery_images && gallery_images.length > 0 ? (
				<ImageGallery items={gallery_images} />
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
