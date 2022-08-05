export const blockAttributes = {
	images: {
		type: "array",
		default: [],
		source: "query",
		selector: "ul.wp-block-drs-tk-gallery-carousel .blocks-gallery-item",
		query: {
			url: {
				type: "string",
				source: "attribute",
				selector: "img",
				attribute: "src",
			},
			link: {
				source: "attribute",
				selector: "img",
				attribute: "data-link",
			},
			alt: {
				source: "attribute",
				selector: "img",
				attribute: "alt",
				default: "",
			},
			id: {
				type: "number",
				source: "attribute",
				selector: "img",
				attribute: "data-id",
			},
			caption: {
				type: "string",
				source: "html",
				selector: "figcaption",
			},
		},
	},
	ids: {
		type: "array",
		default: [],
	},

	imageCrop: {
		type: "boolean",
		default: true,
	},
	adaptiveHeight: {
		type: "boolean",
		default: false,
	},
	autoplay: {
		type: "boolean",
		default: true,
	},
	pauseOnHover: {
		type: "boolean",
		default: false,
	},
	arrows: {
		type: "boolean",
		default: false,
	},
	dots: {
		type: "boolean",
		default: false,
	},
	speed: {
		type: "string",
		default: "300",
	},
	effect: {
		type: "string",
		default: "fade",
	},
	linkTo: {
		type: "string",
		default: "none",
	},
	target: {
		type: "boolean",
		default: true,
	},
};
