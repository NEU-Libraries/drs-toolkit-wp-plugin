import { registerBlockType } from "@wordpress/blocks";

import "./style.scss";

import Edit from "./edit";
import save from "./save";
import metadata from "./block.json";
import { blockAttributes } from "./blockAttributes";
import icons from "./icons";
import withInsertFromDRS from "../filters/withInsertFromDRS";

registerBlockType(metadata.name, {
	icon: icons.imageSlider,
	attributes: blockAttributes,
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
