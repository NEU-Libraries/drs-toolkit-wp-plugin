// import "./admin.scss";
import { Icon } from "@wordpress/components";
import { Fragment, render, Component } from "@wordpress/element";
import { __ } from "@wordpress/i18n";

function App() {
	return (
		<Fragment>
			<div className="drstk-plugin__header">
				<div className="drstk-plugin__container">
					<div className="drstk-plugin__title">
						<h1>
							{__("drstk Plugin Settings", "drstk-plugin")}
							<Icon icon="admin-plugins" />
						</h1>
					</div>
				</div>
			</div>
			<div className="drstk-plugin__main"></div>
		</Fragment>
	);
}

document.addEventListener("DOMContentLoaded", () => {
	const htmlOutput = document.getElementById("drstk-plugin-settings");

	if (htmlOutput) {
		render(<App />, htmlOutput);
	}
});
