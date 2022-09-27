/**
 * Parameters passed to fetch from File
 * @typedef  {Object} TimelineItemProps
 * @property {string} fileUrl               - URL for the file
 * @property {string} type                  - file type
 * @property {string} creator               - name of the creator
 * @property {string} date                  - created date
 * @property {string} description           - item description
 */

/**
 * Item in the timeline
 * @param {TimelineItemProps} props
 * @returns JSX.Element
 */
function TimelineItem({ fileUrl, type, creator, date, description }) {
	return (
		<div className="timeline-item">
			<div className="timeline-item-content">
				<span className="tag" style={{ background: "red" }}>
					{date}
				</span>
				<img src={fileUrl} />
				<p>{creator}</p>
				<p>{description}</p>
			</div>
		</div>
	);
}

export default TimelineItem;
