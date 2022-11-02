function convertToTimelineJSON(files) {
	const timelineJSON = files.map((file) => {
		console.log(file);
		return {
			media: {
				url: file.fileUrl,
				caption: "",
				credit: file.creator,
			},
			start_date: {
				year: file.date,
			},
			text: {
				headline: "",
				text: file.description.toString(),
			},
		};
	});

	return {
		events: timelineJSON,
	};
}

export { convertToTimelineJSON };
