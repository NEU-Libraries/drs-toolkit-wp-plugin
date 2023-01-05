import { registerBlockType } from "@wordpress/blocks";

import "./style.scss";

import Edit from "./edit";
import metadata from "./block.json";

import { TimelineIcon } from "./icon";
import save from "./save";

registerBlockType(metadata.name, {
	icon: TimelineIcon,
	attributes: metadata.attributes,
	edit: Edit,
	save: save,
});
