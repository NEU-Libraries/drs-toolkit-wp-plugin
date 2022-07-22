import { Button } from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";

const { createHigherOrderComponent } = wp.compose;

import InsertFromDRSNumber from "../../components/InsertFromDRSNumber";
import DRSModal from "../../components/DRSModal";

const withInsertFromDRS = createHigherOrderComponent((Element) => {
	return function (props) {
		const { allowedTypes, onSelectURL } = props;
		const [isDRSInputVisible, setIsDRSInputVisible] = useState(false);
		const [isDRSNumberVisible, setIsDRSNumberVisble] = useState(false);

		const openDRSModal = () => {
			setIsDRSInputVisible(true);
		};

		const closeDRSModal = () => {
			setIsDRSInputVisible(false);
		};

		const openDRSNumberInput = () => {
			setIsDRSNumberVisble(true);
		};

		const closeDRSNumberInput = () => {
			setIsDRSNumberVisble(false);
		};
		function renderDRSInputUI() {
			return (
				<div className="block-editor-media-placeholder__url-input-container">
					<Button
						className="block-editor-media-placeholder__button"
						onClick={openDRSNumberInput}
						isPressed={isDRSNumberVisible}
						variant="tertiary"
					>
						{__("Insert from DRS Number")}
					</Button>
					{isDRSNumberVisible && (
						<InsertFromDRSNumber
							allowedTypes={allowedTypes}
							onClose={closeDRSNumberInput}
							onSubmit={onSelectURL}
						/>
					)}
				</div>
			);
		}

		function renderDRSSelectionUI() {
			return (
				<div className="block-editor-media-placeholder__url-input-container">
					<Button
						className="block-editor-media-placeholder__button"
						onClick={openDRSModal}
						isPressed={isDRSInputVisible}
						variant="tertiary"
					>
						{__("Insert from DRS")}
					</Button>
					{isDRSInputVisible && (
						<DRSModal
							onClose={closeDRSModal}
							onSubmit={onSelectURL}
							allowedTypes={allowedTypes}
						/>
					)}
				</div>
			);
		}

		return !props.multiple ? (
			<Element {...props}>
				{renderDRSSelectionUI()}
				{renderDRSInputUI()}
			</Element>
		) : (
			<Element {...props} />
		);
	};
}, "withInsertFromDRS");

export default withInsertFromDRS;
