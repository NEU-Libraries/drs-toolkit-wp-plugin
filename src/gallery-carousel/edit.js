/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __, sprintf, setLocaleData } from "@wordpress/i18n";

/**
 * WordPress dependenices
 */
import {
	useBlockProps,
	BlockControls,
	InspectorControls,
	MediaPlaceholder,
	MediaUpload,
	MediaUploadCheck,
} from "@wordpress/block-editor";
import { Component, Fragment } from "@wordpress/element";
import {
	Button,
	DropZone,
	FormFileUpload,
	PanelBody,
	RangeControl,
	TextControl,
	SelectControl,
	ToggleControl,
	ToolbarGroup,
	ToolbarItem,
	withNotices,
	Placeholder,
} from "@wordpress/components";

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import "./editor.scss";

/**
 * External Dependencies
 */
const { filter, pick, get, map } = lodash;
import DRSModal from "../components/DRSModal";

/**
 * Internal dependencies
 */
import SliderImage from "./slider-image";
import { useState } from "@wordpress/element";

const effectOptions = [
	{ value: "fade", label: __("Fade", "drs-tk-gallery-carousel") },
	{ value: "scroll", label: __("Scroll", "drs-tk-gallery-carousel") },
];

const linkOptions = [
	{ value: "url", label: __("Custom URL") },
	{ value: "attachment", label: __("Attachment Page") },
	{ value: "media", label: __("Media File") },
	{ value: "none", label: __("None") },
];
const ALLOWED_MEDIA_TYPES = ["image"];

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return { WPElement } Element to render.
 */
export default function Edit({
	attributes,
	isSelected,
	noticeUI,
	image,
	setAttributes,
}) {
	const [showDRSModal, setShowDRSModal] = useState(false);
	const blockProps = useBlockProps({
		className: attributes.imageCrop ? "is-cropped" : "",
	});

	let selectedImage = null;
	let captionSelected = false;

	function onSelectImages(images) {
		const contentImages = images.map((img) => ({
			url: img.fileUrl,
		}));
		setAttributes({ images: contentImages });
	}

	const setTheAttributes = (attributes) => {
		if (attributes.ids) {
			throw new Error(
				'The "ids" attribute should not be changed directly. It is managed automatically when "images" attribute changes'
			);
		}

		if (attributes.images) {
			attributes = {
				...attributes,
				ids: map(attributes.images, "id"),
			};
		}

		setAttributes(attributes);
	};

	const onSelectImage = (index) => {
		return () => {
			if (selectedImage !== index) {
				selectedImage = index;
				console.log("selectedImage: " + selectedImage);
			}
		};
	};

	const onRemoveImage = (index) => {
		return () => {
			const images = filter(attributes.images, (img, i) => index !== i);
			selectedImage = null;
			setTheAttributes({
				images,
			});
		};
	};

	const setLinkTo = (value) => {
		setTheAttributes({ linkTo: value });
	};

	const setSpeed = (value) => {
		setTheAttributes({ speed: value });
	};

	const setEffect = (value) => {
		setTheAttributes({ effect: value });
	};

	const toggleAutoplay = () => {
		setTheAttributes({ autoplay: !attributes.autoplay });
	};

	const toggleArrows = () => {
		setTheAttributes({ arrows: !attributes.arrows });
	};

	const toggleDots = () => {
		setTheAttributes({ dots: !attributes.dots });
	};

	const toggleImageCrop = () => {
		setTheAttributes({ imageCrop: !attributes.imageCrop });
	};

	const getImageCropHelp = (checked) => {
		return checked
			? __("Thumbnails are cropped to align.")
			: __("Thumbnails are not cropped.");
	};

	const toggleAdaptiveHeight = () => {
		setTheAttributes({ adaptiveHeight: !attributes.adaptiveHeight });
	};

	const togglePauseOnHover = () => {
		setTheAttributes({ pauseOnHover: !attributes.pauseOnHover });
	};

	const toggleTarget = () => {
		setTheAttributes({ target: !attributes.target });
	};

	const setImageAttributes = (index, attrs) => {
		const { images } = attributes;
		if (!images[index]) {
			return;
		}
		setTheAttributes({
			images: [
				...images.slice(0, index),
				{
					...images[index],
					...attrs,
				},
				...images.slice(index + 1),
			],
		});
	};

	const componentDidUpdate = (prevProps) => {
		// Deselect images when deselecting the block
		if (!isSelected && prevProps.isSelected) {
			selectedImage = null;
			captionSelected = false;
		}
	};

	function renderDRSButton() {
		return (
			<div>
				<Button
					onClick={(e) => setShowDRSModal(true)}
					isPressed={showDRSModal}
					variant="primary"
				>
					{__("Select Images")}
				</Button>
				{showDRSModal && (
					<DRSModal
						onClose={(e) => setShowDRSModal(false)}
						onSubmit={onSelectImages}
						multiple={true}
					/>
				)}
			</div>
		);
	}

	const {
		images,
		imageCrop,
		adaptiveHeight,
		autoplay,
		pauseOnHover,
		arrows,
		dots,
		speed,
		effect,
		linkTo,
		target,
	} = attributes;

	const controls = (
		<BlockControls>
			{!!images.length && <ToolbarGroup></ToolbarGroup>}
		</BlockControls>
	);

	const inspectorControls = (
		<InspectorControls>
			<PanelBody title={__("Slider Settings", "drs-tk-gallery-carousel")}>
				<ToggleControl
					label={__("Adaptive Height", "drs-tk-gallery-carousel")}
					checked={!!adaptiveHeight}
					onChange={toggleAdaptiveHeight}
				/>
				<ToggleControl
					label={__("Autoplay", "drs-tk-gallery-carousel")}
					checked={!!autoplay}
					onChange={toggleAutoplay}
				/>
				{autoplay ? (
					<ToggleControl
						label={__("Pause on hover", "drs-tk-gallery-carousel")}
						checked={!!pauseOnHover}
						onChange={togglePauseOnHover}
					/>
				) : (
					""
				)}
				<ToggleControl
					label={__("Show Arrows", "drs-tk-gallery-carousel")}
					checked={!!arrows}
					onChange={toggleArrows}
				/>
				<ToggleControl
					label={__("Show Dots", "drs-tk-gallery-carousel")}
					checked={!!dots}
					onChange={toggleDots}
				/>
				<TextControl
					label={__("Speed", "drs-tk-gallery-carousel")}
					type="number"
					min="100"
					max="500"
					value={speed}
					onChange={setSpeed}
				/>
				<SelectControl
					label={__("Effect", "drs-tk-gallery-carousel")}
					value={effect}
					onChange={setEffect}
					options={effectOptions}
				/>
				<SelectControl
					label={__("Link To")}
					value={linkTo}
					onChange={setLinkTo}
					options={linkOptions}
				/>
				{linkTo !== "none" ? (
					<ToggleControl
						label={__("Open link in new tab", "drs-tk-gallery-carousel")}
						checked={!!target}
						onChange={toggleTarget}
					/>
				) : (
					""
				)}
			</PanelBody>
		</InspectorControls>
	);

	if (images.length === 0) {
		return (
			<div {...blockProps}>
				<Fragment>
					{controls}
					<Placeholder
						icon={image}
						label="DRS Gallery Carousel"
						instructions="Select all the images that you want to display in the carousel"
					>
						{renderDRSButton()}
					</Placeholder>
				</Fragment>
			</div>
		);
	}

	return (
		<Fragment>
			{controls}
			{inspectorControls}
			{noticeUI}
			<ul {...blockProps}>
				{images.map((img, index) => {
					return (
						<li className="blocks-gallery-item" key={img.id || img.url}>
							<SliderImage
								url={img.url}
								alt={img.alt}
								id={img.id}
								onRemove={onRemoveImage(index)}
								onSelect={onSelectImage(index)}
								isSelected={isSelected}
								// isSelected={ isSelected && selectedImage === index }
								setAttributes={(attrs) => setImageAttributes(index, attrs)}
								caption={img.caption}
								link={img.link}
								linkTo={linkTo}
							/>
						</li>
					);
				})}
			</ul>
		</Fragment>
	);
}
