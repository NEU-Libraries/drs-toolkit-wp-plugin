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
 * @return { WPElement } Element to render.
 */
export default function save({ attributes }) {
	const blockProps = useBlockProps.save();
	return (
		<div {...blockProps}>
			{attributes.files.map((item, index) => (
				<div
					className="data-timeline-item"
					key={index}
					data-creator={item.creator}
					data-date={item.date}
					data-description={item.description.toString()}
					data-fileUrl={item.fileUrl}
					data-thumbnail={item.thumbnail}
					data-id={item.id}
				></div>
			))}
			<div
				id="timeline-embed"
				style={{ width: "600px", height: "600px" }}
			></div>
		</div>
	);
}
