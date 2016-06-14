<?php
/**
 * Backbone Templates
 * This file contains all of the HTML used in our modal and the workflow itself.
 *
 * Each template is wrapped in a script block ( note the type is set to "text/html" ) and given an ID prefixed with
 * 'tmpl'. The wp.template method retrieves the contents of the script block and converts these blocks into compiled
 * templates to be used and reused in your application.
 */


/**
 * The Modal Window, including sidebar and content area.
 * Add menu items to ".navigation-bar nav ul"
 * Add content to ".backbone_modal-main article"
 */
?>
<script type="text/html" id='tmpl-drstk-modal-window'>
	<div class="backbone_modal">
		<a class="backbone_modal-close dashicons dashicons-no" href="#"
		   title="<?php echo __( 'Close', 'backbone_modal' ); ?>"><span
				class="screen-reader-text"><?php echo __( 'Close', 'backbone_modal' ); ?></span></a>

		<div class="backbone_modal-content">
			<div class="navigation-bar">
				<nav>
					<ul></ul>
				</nav>
			</div>
			<section class="backbone_modal-main" role="main">
				<header><h1><?php echo __( 'Add Toolkit Shortcodes', 'backbone_modal' ); ?></h1></header>
				<article></article>
				<footer>
					<div class="inner text-right">
						<button id="btn-cancel"
						        class="button button-large"><?php echo __( 'Cancel', 'backbone_modal' ); ?></button>
						<button id="btn-ok"
						        class="button button-primary button-large"><?php echo __( 'Insert Shortcode', 'backbone_modal' ); ?></button>
					</div>
				</footer>
			</section>
		</div>
	</div>
</script>

<?php
/**
 * The Modal Backdrop
 */
?>
<script type="text/html" id='tmpl-drstk-modal-backdrop'>
	<div class="backbone_modal-backdrop">&nbsp;</div>
</script>
<?php
/**
 * Base template for a navigation-bar menu item ( and the only *real* template in the file ).
 */
?>
<script type="text/html" id='tmpl-drstk-modal-menu-item'>
	<li class="nav-item"><a href="{{ data.url }}">{{ data.name }}</a></li>
</script>
<?php
/**
 * A menu item separator.
 */
?>
<script type="text/html" id='tmpl-drstk-modal-menu-item-separator'>
	<li class="separator">&nbsp;</li>
</script>
<?php
/**
* A template for tab content
*/
?>
<script type='text/html' id='tmpl-drstk-modal-tab-content'>
	<div class="wrap">
		<h1 class="title">{{data.title}}</h1>
		<h2 class="nav-tab-wrapper">
			<a class="nav-tab nav-tab-active" href="#drs">DRS Items</a>
			<a class="nav-tab" href="#dpla">DPLA Items</a>
			<a class="nav-tab" href="#selected">Selected Items</a>
			<a class="nav-tab" href="#settings">Settings</a>
		</h2>
		<br/>
		<div class="pane" id="drs">
			<label for="search">Search for an item: </label><input type="text" name="search" id="search-{{data.type}}" /><button class="themebutton search-button">Search</button>
			<div class="drs-items">Loading...</div><ol id="sortable-{{data.type}}-list"></ol><div class="drs-pagination"></div>
		</div>
		<div class="pane" id="dpla">
			<label for="search">Search for an item: </label><input type="text" name="search" id="search-{{data.type}}" /><button class="themebutton search-button">Search</button>
			<div class="dpla-items">Loading...</div><ol id="sortable-{{data.type}}-list"></ol><div class="dpla-pagination"></div>
		</div>
		<div class="pane" id="selected">
			<div class="selected-items">Loading...</div><ol id="sortable-{{data.type}}-list"></ol><div class="selected-pagination"></div>
		</div>
		<div class="pane" id="settings">
		</div>
	</div>
</script>
