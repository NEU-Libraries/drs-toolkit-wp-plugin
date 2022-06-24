import { useState, useEffect } from "@wordpress/element";
import { Modal, TextControl } from "@wordpress/components";
import "./modal.scss";
import { Button } from "@wordpress/components";
import { fetchFromFile, fetchFromSearch } from "../DRSApi";

const DRSModal = ({ onClose, onSubmit }) => {
	const [imageUrl, setImageUrl] = useState("");
	const [collectionId, setCollectionId] = useState("neu:rx913q686");
	const [file, setFile] = useState({});
	const [urls, setUrls] = useState([]);

	const submitURL = (e) => {
		e.preventDefault();
		if (imageUrl !== "") onSubmit(imageUrl);
		onClose();
	};

	function onSelectFile(file) {
		setImageUrl(file.fileUrl);
		setFile(file);
	}

	console.log(file);

	useEffect(async () => {
		try {
			const data = await fetchFromSearch({ collectionId });
			const urlst = Object.keys(data.response.highlighting);

			const requests = [];
			for (let i = 0; i < urlst.length; i++) {
				requests.push(
					fetchFromFile({
						format: "Image",
						fileId: urlst[i],
						fileFormat: "Image",
					})
				);
			}

			const responses = await Promise.allSettled(requests);
			const result = [];
			responses.forEach((item) => {
				if (item.status === "rejected") return;
				result.push(item.value);
			});
			setUrls(result);
		} catch (error) {
			console.log(error);
		}
	}, [fetchFromSearch, collectionId]);
	return (
		<>
			<Modal
				className="media-modal wp-core-ui"
				onRequestClose={onClose}
				title="DRS Items"
			>
				<div className="media-frame-content left-0 border-top-none">
					<div className="attachments-browser">
						<div className=" media-toolbar ">
							<div className="media-toolbar-secondary ">
								<TextControl
									label="Collection Id"
									value={collectionId}
									onChange={(value) => setCollectionId(value)}
									className="search"
								></TextControl>
							</div>
						</div>

						<ul className="attachments ui-sortable ui-sortable-disabled ">
							{urls.map((_file, index) => (
								<FileSelect
									file={_file}
									key={index}
									selected={_file.fileUrl == imageUrl}
									onSelect={onSelectFile}
									type="Image"
								/>
							))}
						</ul>
					</div>
					<div className="media-sidebar">
						<h2>File Details</h2>
						{file.mods == undefined || file.mods == null ? (
							<p>Select a image</p>
						) : (
							Object.entries(file.mods).map(([key, value]) => (
								<div>
									<span className="modal-file-desc-head">{key}</span>
									<p className="modal-file-desc-val">{value}</p>
								</div>
							))
						)}
					</div>
				</div>

				<div className="media-frame-toolbar left-0">
					<div className="media-toolbar">
						<div className="media-toolbar-primary search-form">
							<button
								type="button"
								className="button media-button button-primary button-large media-button-select"
								onClick={(e) => submitURL(e)}
							>
								Select
							</button>
						</div>
					</div>
				</div>
			</Modal>
		</>
	);
};

function FileSelect({ file, selected, onSelect, type }) {
	const classes = selected
		? "attachment save-ready selected details"
		: "attachment save-ready ";
	return (
		<>
			<li
				className={classes}
				onClick={() => {
					onSelect(file);
				}}
			>
				<div className="attachment-preview js--select-attachment type-image subtype-png landscape">
					<div className="thumbnail">
						<div className="centered">
							<img src={file.fileUrl} alt="" />
						</div>
					</div>
					<button type="button" className="check" tabindex="-1">
						<span className="media-modal-icon"></span>
					</button>
				</div>
			</li>
		</>
	);
}

function Sidebar(file) {
	const [details, setDetails] = useState({});

	useEffect(() => {
		setDetails(file.mods);
	}, [file]);
	console.log(file);

	useEffect(() => {
		console.log("component updated");
	});

	return details == undefined || details === null ? (
		<div className="media-sidebar">Select A Image</div>
	) : (
		<div className="media-sidebar">Test</div>
	);
}

export default DRSModal;
