/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from "@wordpress/i18n";

import { useBlockProps } from "@wordpress/block-editor";

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
	const {
		images,
		imageCrop,
		autoplay,
		pauseOnHover,
		arrows,
		dots,
		speed,
		effect,
		linkTo,
		target,
		adaptiveHeight,
	} = attributes;

	let blockProps = useBlockProps.save({
		className: imageCrop ? "is-cropped" : "",
		"data-autoplay": autoplay,
		"data-speed": speed,
		"data-effect": effect,
		"data-arrows": arrows,
		"data-dots": dots,
	});

	// v2.0
	if (adaptiveHeight)
		blockProps = { ...blockProps, "data-adaptiveHeight": adaptiveHeight };
	if (pauseOnHover)
		blockProps = { ...blockProps, "data-pauseOnHover": pauseOnHover };

	return (
		<ul {...blockProps}>
			{images.map((image) => {
				let href;

				switch (linkTo) {
					case "media":
						href = image.url;
						break;
					case "attachment":
						href = image.link;
						break;
					case "url":
						href = image.link;
						break;
				}

				const img = (
					<img
						src={image.url}
						alt={image.alt}
						data-id={image.id}
						data-link={image.link}
						className={image.id ? `wp-image-${image.id}` : null}
					/>
				);

				return (
					<li key={image.id || image.url} className="blocks-gallery-item">
						<figure>
							{href ? (
								<a
									href={href}
									target={target ? "_blank" : "_self"}
									rel="noopener"
								>
									{img}
								</a>
							) : (
								img
							)}
							{image.caption && image.caption.length > 0 && (
								<figcaption>{image.caption}</figcaption>
							)}
						</figure>
					</li>
				);
			})}
		</ul>
	);
}
