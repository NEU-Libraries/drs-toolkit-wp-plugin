import { registerBlockType } from "@wordpress/blocks";

import "./style.scss";

import Edit from "./edit";
import save from "./save";
import metadata from "./block.json";
import { blockAttributes } from "./blockAttributes";
import { TimelineIcon } from "./icon";

registerBlockType(metadata.name, {
	icon: TimelineIcon,
	attributes: blockAttributes,
	edit: Edit,
	save,
});
