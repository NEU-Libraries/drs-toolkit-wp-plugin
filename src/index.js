import { registerBlockType } from "@wordpress/blocks";

import "./style.scss";

import Edit from "./edit";
import save from "./save";
import metadata from "./block.json";
import cMediaPlaceholder from "./customMediaPlaceholder/MediaPlaceholder";
import withReplaceFromDRS from "./replaceFromDRS/ReplaceFromDRS";

registerBlockType(metadata.name, {
	edit: Edit,
	save,
});

function replaceMediaPlaceholder() {
	return cMediaPlaceholder;
}

wp.hooks.addFilter(
	"editor.MediaPlaceholder",
	"drs-tk/replace-media-placeholder",
	replaceMediaPlaceholder
);

wp.hooks.addFilter(
	"editor.BlockEdit",
	"drs-tk/replace-from-drs",
	withReplaceFromDRS
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
