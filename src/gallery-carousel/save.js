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
import ImageGallery from "react-image-gallery";

/**
 * The save function defines the way in which the different attributes should
 * be combined into the final markup, which is then serialized by the block
 * editor into `post_content`.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#save
 *
 * @return {WPElement} Element to render.
 */
export default function save({ attributes }) {
	const { gallery_images } = attributes;
	console.log(gallery_images);

	return (
		<div {...useBlockProps.save()}>
			{gallery_images ? (
				// <ImageGallery items={gallery_images} />
				gallery_images.map((imgUrl) => <img src={imgUrl.original} />)
			) : (
				<p>nothing here</p>
			)}
		</div>
	);
}
