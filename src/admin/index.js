import "./admin.scss";
import api from "@wordpress/api";
import {
	Button,
	Icon,
	Panel,
	PanelBody,
	PanelRow,
	Placeholder,
	SelectControl,
	Spinner,
	TextControl,
	ToggleControl,
} from "@wordpress/components";
import {
	Fragment,
	render,
	Component,
	useState,
	useEffect,
} from "@wordpress/element";
import { dispatch, useDispatch, useSelect } from "@wordpress/data";
import { __ } from "@wordpress/i18n";
import { store as noticesStore } from "@wordpress/notices";

import withInsertFromDRS from "../filters/withInsertFromDRS";

const Notices = () => {
	const notices = useSelect(
		(select) =>
			select(noticesStore)
				.getNotices()
				.filter((notice) => notice.type === "snackbar"),
		[]
	);
	const { removeNotice } = useDispatch(noticesStore);
	return (
		<SnackbarList
			className="edit-site-notices"
			notices={notices}
			onRemove={removeNotice}
		/>
	);
};

function App() {
	const [collectionId, setCollectionId] = useState("");
	const [isAPILoaded, setIsAPILoaded] = useState(false);

	useEffect(async () => {
		await api.loadPromise;
		const settings = new api.models.Settings();

		if (!isAPILoaded) {
			const response = await settings.fetch();
			setCollectionId(response["ceres_drstk_plugin_collection_id"]);
			setIsAPILoaded(true);
		}
	}, []);

	if (!isAPILoaded) {
		return (
			<Placeholder>
				<Spinner />
			</Placeholder>
		);
	}

	return (
		<Fragment>
			<div className="ceres-plugin__header">
				<div className="ceres-plugin__container">
					<div className="ceres-plugin__title">
						<h1>{__("DRS Toolkit Plugin Settings", "ceres-plugin")} </h1>
					</div>
				</div>
			</div>
			<div className="ceres-plugin__main">
				<Panel>
					<PanelBody
						title={__("DRS Collection Id", "ceres-plugin")}
						icon="admin-plugins"
					>
						<TextControl
							help={__("This is a collection id", "ceres-plugin")}
							label={__("Collection Id", "ceres-plugin")}
							onChange={(value) => setCollectionId(value)}
							value={collectionId}
						/>
					</PanelBody>
					<Button
						isPrimary
						isLarge
						onClick={() => {
							const settings = new api.models.Settings({
								["ceres_drstk_plugin_collection_id"]: collectionId,
							});

							settings.save();

							dispatch("core/notices").createNotice(
								"success",
								__("Settings Saved", "ceres-plugin"),
								{
									type: "snackbar",
									isDismissible: true,
								}
							);
						}}
					>
						{__("Save", "ceres-plugin")}
					</Button>
				</Panel>
			</div>
		</Fragment>
	);
}

document.addEventListener("DOMContentLoaded", () => {
	const htmlOutput = document.getElementById("ceres-plugin-settings");

	if (htmlOutput) {
		render(<App />, htmlOutput);
	}
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
