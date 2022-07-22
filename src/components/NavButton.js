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

export default NavButton;
