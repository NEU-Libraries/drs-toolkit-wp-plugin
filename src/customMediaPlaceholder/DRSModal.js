import { useState, useEffect } from "@wordpress/element";
import "./modal.scss";

const Modal = ({ onClose, onSubmit }) => {
	const [imageUrl, setImageUrl] = useState("");
	const urls = [
		"https://cdn.vox-cdn.com/thumbor/23dWY86RxkdF7ZegvfnY8gFjR7s=/1400x1400/filters:format(jpeg)/cdn.vox-cdn.com/uploads/chorus_asset/file/19157811/ply0947_fall_reviews_2019_tv_anime.jpg",
		"https://thecinemaholic.com/wp-content/uploads/2021/01/nezuu-e1638963260523.jpg",
		"https://static.marriedgames.com.br/82bae879-naruto-classico-e-naruto-shippuden-fillers.jpg",
		"https://img3.hulu.com/user/v3/artwork/9c91ffa3-dc20-48bf-8bc5-692e37c76d88?base_image_bucket_name=image_manager&base_image=e1731d26-8837-40b9-85e4-1ad0a95014a8&size=550x825&format=jpeg",
	];

	const submitURL = (e) => {
		e.preventDefault();
		if (imageUrl !== "") onSubmit(imageUrl);
		onClose();
	};
	return (
		<>
			<div className="darkBG" onClick={onClose} />
			<div className="centered">
				<div className="media-modal wp-core-ui modal-half">
					<button className="media-modal-close" onClick={onClose}>
						<span className="media-modal-icon"></span>
					</button>
					<div className="media-modal-content">
						<div className="media-frame mode-select wp-core-ui hide-menu">
							<div className="media-frame-title">
								<h1>DRS Items</h1>
							</div>
						</div>
						<div className="media-frame-content left-0">
							<div className="attachments-browser">
								<div className="media-toolbar">
									<div className="media-toolbar-secondary">
										<h2 className="media-attachments-filter-heading filter-heading">
											Filter
										</h2>
									</div>
								</div>
								<ul className="attachments ui-sortable ui-sortable-disabled">
									{urls.map((tempUrl, index) => (
										<ImageSelect
											url={tempUrl}
											key={index}
											selected={tempUrl == imageUrl}
											onSelect={setImageUrl}
										/>
									))}
								</ul>
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
					</div>
				</div>
			</div>
		</>
	);
};

function ImageSelect({ url, selected, onSelect }) {
	const classes = selected
		? "attachment save-ready selected details"
		: "attachment save-ready ";
	return (
		<>
			<li
				className={classes}
				onClick={() => {
					onSelect(url);
				}}
			>
				<div className="attachment-preview js--select-attachment type-image subtype-png landscape">
					<div className="thumbnail">
						<div className="centered">
							<img src={url} alt="" />
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

export default Modal;
