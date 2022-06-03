import { MenuItem } from "@wordpress/components";
import { __ } from "@wordpress/i18n";

import Modal from "../customMediaPlaceholder/DRSModal";

const { createHigherOrderComponent } = wp.compose;
const { BlockControls } = wp.blockEditor;
const { Fragment } = wp.element;

const withReplaceFromDRS = createHigherOrderComponent((BlockEdit) => {
	return (props) => {
		const { attributes, setAttributes, onReplace } = props;
		const { isDRSClose } = attributes;
		console.log("isDRS");
		console.log(isDRSClose);
		console.log(props);

		if ("core/image" === props.name) {
			return (
				<Fragment>
					<BlockEdit {...props} />
					<BlockControls>
						<MenuItem onClick={() => setAttributes({ isDRSClose: true })}>
							{__("Replace from DRS")}
						</MenuItem>
					</BlockControls>
					{isDRSClose && (
						<Modal
							onClose={() => {
								setAttributes({ isDRSClose: false });
							}}
							onSubmit={(url) => setAttributes({ url })}
						/>
					)}
				</Fragment>
			);
		}

		return <BlockEdit {...props} />;
	};
}, "withReplaceFromDRS");

export default withReplaceFromDRS;
