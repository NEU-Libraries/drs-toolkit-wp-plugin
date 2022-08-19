const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const path = require("path");

module.exports = {
	...defaultConfig,
	entry: {
		"gallery-carousel": path.resolve(process.cwd(), "src", "gallery-carousel"),
		admin: path.resolve(process.cwd(), "src", "admin.js"),
	},
};
