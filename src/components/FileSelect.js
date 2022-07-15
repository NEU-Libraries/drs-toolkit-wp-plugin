function FileSelect({ file, selected, onSelect, type }) {
	const classes = selected
		? "attachment save-ready selected details"
		: "attachment save-ready ";
	return (
		<>
			<li
				className={classes}
				onClick={() => {
					onSelect(file);
				}}
			>
				<div className="attachment-preview js--select-attachment type-image subtype-png landscape">
					<div className="thumbnail">
						<div className="centered">
							<img src={file.thumbnail} alt="" />
						</div>
					</div>
					<button type="button" className="check" tabindex="-1">
						<span className="media-modal-icon"></span>
					</button>
				</div>
			</li>
		</>
	);
}

export default FileSelect;
