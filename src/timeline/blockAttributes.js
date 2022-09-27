export const blockAttributes = {
	files: {
		type: "array",
		default: [],
		source: "query",
		selector: "ul.wp-block-drs-tk-timeline .blocks-timeline",
		query: {
			fileUrl: {
				type: "string",
				source: "attribute",
				selector: "img",
				attribute: "src",
			},
			description: {
				type: "string",
				source: "attribute",
				selector: "p",
			},
			creator: {
				type: "string",
				source: "attribute",
				selector: "p",
			},
			date: {
				type: "string",
				source: "attribute",
				selector: "p",
			},
		},
	},
};
