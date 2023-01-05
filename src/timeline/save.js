import TimelineItem from "./timelineItem";
import { useBlockProps } from "@wordpress/block-editor";
/**
 * The save function defines the way in which the different attributes should be
 * combined into the final markup, which is then serialized by Gutenberg into
 * `post_content`.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#save
 *
 * @return {WPElement} Element to render.
 *
 */
export default function save({ attributes }) {
	const blockProps = useBlockProps.save();
	const { files } = attributes;
	return (
		<>
			<div {...blockProps}>
				<div className="timeline-container">
					{files.map((item, index) => (
						<div className="timeline-item" key={index}>
							<div className="timeline-item-content">
								<span className="tag" style={{ background: "red" }}>
									{item.date}
								</span>
								<img src={item.fileUrl} />
								<p>{item.creator}</p>
								<p>{item.description.toString()}</p>
							</div>
						</div>
					))}
				</div>
			</div>
		</>
	);
}
