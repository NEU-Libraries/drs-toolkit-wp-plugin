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
