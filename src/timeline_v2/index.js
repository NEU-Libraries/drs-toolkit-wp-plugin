import { registerBlockType } from "@wordpress/blocks";

import "./style.scss";

import Edit from "./edit";
import metadata from "./block.json";

import { TimelineIcon } from "./icon";

registerBlockType(metadata.name, {
	icon: TimelineIcon,
	attributes: metadata.attributes,
	edit: Edit,
});
