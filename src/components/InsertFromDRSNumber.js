import axios from "axios";

// wordpress imports
import { Button } from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import { useState, useEffect } from "@wordpress/element";
import { keyboardReturn } from "@wordpress/icons";
import { URLPopover } from "@wordpress/block-editor";
import { fetchFromFile } from "../api/DRSApi";

/**
 * Parameters passed to fetch from File
 * @typedef  {Object} InsertFromDRSNumberProps
 * @property {Array<string>} allowedTypes               - Allowed types from media placeholder
 * @property {Function} onSubmit       					- Callback function for onSubmit
 * @property {Function} onClose                			- Callback function for onClose
 */

/**
 * Component to Insert File based on the number passed
 * @param {InsertFromDRSNumberProps} props
 * @returns JSX.Element
 */
function InsertFromDRSNumber({ allowedTypes, onSubmit, onClose }) {
	const [fileUrl, setFileUrl] = useState(""); // store the url of the file
	const [drsNum, setDrsNum] = useState("neu:344525"); // the drs number to be used
	const [submitForm, setSubmitForm] = useState(false); // if the form is submitted or not

	const url = "https://repository.library.northeastern.edu/api/v1/files/";

	useEffect(() => {
		const fetchFileFromDRS = async () => {
			try {
				const response = await fetchFromFile({
					fileId: drsNum,
					allowedTypes: allowedTypes,
				});
				if (response.err) {
					// TODO : display a notice here that the file format is invalid for the allowed type
				} else {
					setFileUrl(response.fileUrl);
				}
			} catch (error) {
				console.log(error);
			}
		};

		if (submitForm) fetchFileFromDRS();
	}, [axios, drsNum, setFileUrl, submitForm]);

	function submitDRSForm(event) {
		event.preventDefault();
		setSubmitForm(true);
	}

	useEffect(() => {
		onSubmit(fileUrl);
	}, [fileUrl]);

	return (
		<URLPopover onClose={onClose}>
			<form
				className="block-editor-media-placeholder__url-input-form"
				onSubmit={(e) => submitDRSForm(e)}
			>
				<input
					className="block-editor-media-placeholder__url-input-field"
					type="text"
					aria-label={__("URL")}
					placeholder={__("Paste or type DRS Number")}
					onChange={(e) => {
						setDrsNum(e.target.value);
					}}
					value={drsNum}
				/>
				<Button
					className="block-editor-media-placeholder__url-input-submit-button"
					icon={keyboardReturn}
					label={__("Apply")}
					type="submit"
				/>
			</form>
		</URLPopover>
	);
}

export default InsertFromDRSNumber;
