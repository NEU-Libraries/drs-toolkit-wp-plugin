import { registerBlockType } from "@wordpress/blocks";

import "./style.scss";

import Edit from "./edit";
import save from "./save";
import metadata from "./block.json";
import withInsertFromDRS from "../filters/withInsertFromDRS";

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
