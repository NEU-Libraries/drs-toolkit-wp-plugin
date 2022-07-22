import { registerBlockType } from "@wordpress/blocks";

import "./style.scss";

import Edit from "./edit";
import save from "./save";
import metadata from "./block.json";
import withInsertFromDRS from "./filters/withInsertFromDRS";

registerBlockType(metadata.name, {
	edit: Edit,
	save,
});

wp.hooks.addFilter(
	"editor.MediaPlaceholder",
	"drs-tk/replace-media-placeholder",
	withInsertFromDRS
);

wp.hooks.addFilter(
	"editor.MediaReplaceFlow",
	"drs-tk/replace-media-placeholder",
	withInsertFromDRS
);

wp.hooks.addFilter(
	"media.crossOrigin",
	"drs-tk/with-cors-media",
	// The callback accepts a second `mediaSrc` argument which references
	// the url to actual foreign media, useful if you want to decide
	// the value of crossOrigin based upon it.
	(crossOrigin, mediaSrc) => {
		if (mediaSrc.startsWith("https://repository.library.northeastern.edu")) {
			return "use-credentials";
		}
		return crossOrigin;
	}
);
