import { useState, useEffect } from "@wordpress/element";
import { Modal, TextControl } from "@wordpress/components";
import "./modal.scss";
import { fetchFromFile, fetchFromSearch } from "../DRSApi";

const DRSModal = ({ onClose, onSubmit, allowedTypes }) => {
	const [collectionId, setCollectionId] = useState("neu:rx913q686"); // id of the collection
	const [pagination, setPagination] = useState({}); // pagination details fetched from the search api
	const [searchParams, setSearchParams] = useState({ per_page: 20 }); // params to be passed to search api
	const [selectedFile, setSelectedFile] = useState({});
	const [urls, setUrls] = useState([]); // store the files list

	async function submitURL(e) {
		try {
			e.preventDefault(); // restricts reloading
			const { fileUrl } = await fetchFromFile({
				fileId: selectedFile.id,
				allowedTypes: allowedTypes,
			});
			onSubmit(fileUrl);
			onClose();
		} catch (error) {
			console.log(error);
		}
	}

	function onSelectFile(file) {
		setSelectedFile(file);
	}

	useEffect(async () => {
		try {
			console.log(searchParams);
			const data = await fetchFromSearch({ collectionId, searchParams }); // check DRS API
			setPagination(data.pagination.table);

			const objList = data.response.response.docs; // docs from the returned data

			const result = [];
			// iterating over the objList and adding to url list
			objList.forEach((object) => {
				if (object.active_fedora_model_ssi === "CoreFile") {
					const fileData = {};
					fileData.description = object.abstract_tesim;
					fileData.creator = object.creator_ssi;
					fileData.date = object.date_ssi;
					fileData.id = object.id;
					fileData.thumbnail =
						"https://repository.library.northeastern.edu/" +
						object.fields_thumbnail_list_tesim[
							object.fields_thumbnail_list_tesim.length - 1
						];
					result.push(fileData);
				}
			});
			console.log(result);

			setUrls(result);
		} catch (error) {
			console.log(error);
		}
	}, [fetchFromSearch, collectionId, searchParams, setUrls]);
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
									selected={selectedFile.id === _file.id}
									onSelect={onSelectFile}
									type="Image"
								/>
							))}
						</ul>
					</div>
					<div className="media-sidebar">
						<h2>File Details</h2>
						{selectedFile == undefined || selectedFile == null ? (
							<p>Select a image</p>
						) : (
							Object.entries(selectedFile).map(([key, value]) => (
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
							<NavButton
								symbol={"<"}
								onClick={(e) => {
									console.log("Pressed the button");
									setSearchParams({
										...searchParams,
										page: pagination["current_page"] - 1,
									});
								}}
								disabledCondition={!pagination["first_page?"]}
							/>

							<p className="padding-top-5 media-button media-button-select button-large">
								{pagination["current_page"]} of {pagination["num_pages"]}
							</p>
							<NavButton
								symbol={">"}
								onClick={(e) => {
									setSearchParams({
										...searchParams,
										page: pagination["current_page"] + 1,
									});
								}}
								disabledCondition={!pagination["last_page?"]}
							/>
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
							<img src={file.thumbnail} alt="" />
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

function NavButton({ symbol, onClick, disabledCondition }) {
	return disabledCondition ? (
		<button
			type="button"
			className="button media-button button-secondary button-large media-button-select"
			disabled
		>
			{symbol}
		</button>
	) : (
		<button
			type="button"
			className="button media-button button-secondary button-large media-button-select"
			onClick={onClick}
		>
			{symbol}
		</button>
	);
}

export default DRSModal;
