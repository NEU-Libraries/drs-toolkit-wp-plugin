/**
 * Backbone Application File
 * @package drstk.backbone_modal
 */


var drstk = {
	backbone_modal: {
		__instance: undefined
	}
};

drstk.Item = Backbone.Model.extend({
	title: '',
	pid: '',
	thumbnail: '',
	repo: '',
	color: ''
});

drstk.Setting = Backbone.Model.extend({
	name: '',
	value: [],
	choices: {},
	label: '',
	helper: '',
	tag: ''
});

drstk.Items = Backbone.Collection.extend({
	model: drstk.Item
});

drstk.Settings = Backbone.Collection.extend({
	model: drstk.Setting
});

drstk.Shortcode = Backbone.Model.extend({
	defaults:{
		type: '',
		items: new drstk.Items(),
		settings: new drstk.Settings(),
	},
	initialize: function() {
    this.set('items', new drstk.Items());
		this.set('settings',  new drstk.Settings());
  },
	parse: function(response){
		response.items = new drstk.Items(response.items);
		response.settings = new drstk.Settings(response.settings);
		return response;
	},
	set: function(attributes, options) {
    if (attributes.items !== undefined && !(attributes.items instanceof drstk.Items)) {
        attributes.items = new drstk.Items(attributes.items);
    }
		if (attributes.settings !== undefined && !(attributes.settings instanceof drstk.Settings)) {
        attributes.settings = new drstk.Settings(attributes.settings);
    }
    return Backbone.Model.prototype.set.call(this, attributes, options);
	}
});

drstk.ItemView = Backbone.View.extend({
	tagName: 'li',
	item_template: _.template("<label for='tile-<%=pid%>'><img src='<%=thumbnail%>' /><br/><input id='tile-<%=pid%>' type='checkbox' class='tile <%=repo%>' value='<%=pid%>'/><span class='title'><%=title%></span></label>"),
	item_noimg_template: _.template("<label for='tile-<%=pid%>'><span class='dashicons dashicons-format-image'></span><br/><input id='tile-<%=pid%>' type='checkbox' class='tile <%=repo%>' value='<%=pid%>'/><span class='title'><%=title%></span></label>"),
	initialize: function(){
		this.render();
	},
	render: function(){
		if (this.model.attributes.thumbnail === undefined){
			this.$el.html( this.item_noimg_template(this.model.toJSON()));
		} else {
			this.$el.html( this.item_template(this.model.toJSON()));
		}
	}
});

drstk.SettingView = Backbone.View.extend({
	checkbox_template: wp.template( "drstk-setting-checkbox" ),
	select_template: wp.template( "drstk-setting-select" ),
	url_template: wp.template( "drstk-setting-url" ),
	text_template: wp.template( "drstk-setting-text" ),
	number_template: wp.template( "drstk-setting-number" ),
	tagName: 'tr',
	initialize: function(){
		this.render();
	},
	render: function(){
		if (this.model.attributes.tag == 'select'){
			this.$el.html( this.select_template(this.model.toJSON()));
		} else if (this.model.attributes.tag == 'checkbox'){
			this.$el.html( this.checkbox_template(this.model.toJSON()));
		} else if (this.model.attributes.tag == 'url'){
			this.$el.html( this.url_template(this.model.toJSON()));
		} else if (this.model.attributes.tag == 'text'){
			this.$el.html( this.text_template(this.model.toJSON()));
		} else if (this.model.attributes.tag == 'number'){
			this.$el.html( this.number_template(this.model.toJSON()));
		}
	},
})

/**
 * Primary Modal Application Class
 */
drstk.backbone_modal.Application = Backbone.View.extend(
	{
		id: "backbone_modal_dialog",
		events: {
			"click .backbone_modal-close": "closeModal",
			"click #btn-cancel": "closeModal",
			"click #btn-ok": "insertShortcode",
			"click .navigation-bar a": "navigate",
			"click .backbone_modal-main article table .button": "navigate",
			"change .tile": "selectItem",
			"click .tablenav-pages a": "paginate",
			"click .nav-tab": "navigateShortcode",
			"click .search-button": "search",
			"change #settings input": "settingsChange",
			"change #settings select": "settingsChange",
			"change #selected select[name='color']": "changeColor",
			"click #local #wp_media": "addMediaItems",
		},

		/**
		 * Simple object to store any UI elements we need to use over the life of the application.
		 */
		ui: {
			nav: undefined,
			content: undefined
		},

		/**
		 * Container to store our compiled templates. Not strictly necessary in such a simple example
		 * but might be useful in a larger one.
		 */
		templates: {},

		shortcode: null,
		geo_count: 0,
		time_count: 0,

		search_q: '',
		search_page: 1,
		search_params: {q:this.search_q, page:this.search_page},
		current_tab: 1,  // store our current tab as a variable for easy lookup
		tabs: {        // dictionary of key/value pairs for our tabs
			1: 'single',
			2: 'tile',
			3: 'slider',
			4: 'media',
			5: 'map',
			6: 'timeline'
		},
		colors: ["red", "green", "blue", "yellow", "orange"],

		/**
		 * Instantiates the Template object and triggers load.
		 */
		initialize: function () {
			"use strict";

			_.bindAll( this, 'render', 'preserveFocus', 'closeModal', 'insertShortcode', 'navigate', 'showTab', 'getDRSitems', 'selectItem', 'paginate', 'navigateShortcode', 'search', 'setDefaultSettings' );
			this.initialize_templates();
			this.render();
			this.shortcode = new drstk.Shortcode({});
		},


		/**
		 * Creates compiled implementations of the templates. These compiled versions are created using
		 * the wp.template class supplied by WordPress in 'wp-util'. Each template name maps to the ID of a
		 * script tag ( without the 'tmpl-' namespace ) created in template-data.php.
		 */
		initialize_templates: function () {
			this.templates.window = wp.template( "drstk-modal-window" );
			this.templates.backdrop = wp.template( "drstk-modal-backdrop" );
			this.templates.menuItem = wp.template( "drstk-modal-menu-item" );
			this.templates.menuItemSeperator = wp.template( "drstk-modal-menu-item-separator" );
			this.templates.tabMenu = wp.template( "drstk-modal-tab-menu" );
			this.templates.tabItem = wp.template( "drstk-modal-tab-item" );
			this.templates.tabContent = wp.template( "drstk-modal-tab-content" );
		},

		/**
		 * Assembles the UI from loaded templates.
		 * @internal Obviously, if the templates fail to load, our modal never launches.
		 */
		render: function () {
			"use strict";

			// Build the base window and backdrop, attaching them to the $el.
			// Setting the tab index allows us to capture focus and redirect it in Application.preserveFocus
			this.$el.attr( 'tabindex', '0' )
				.append( this.templates.window() )
				.append( this.templates.backdrop() );

			// Save a reference to the navigation bar's unordered list and populate it with items.
			// This is here mostly to demonstrate the use of the template class.
			this.ui.nav = this.$( '.navigation-bar nav ul' )
				.append( this.templates.menuItem( {url: "#one", name: "Single Item"} ) )
				.append( this.templates.menuItem( {url: "#two", name: "Tile Gallery"} ) )
				.append( this.templates.menuItem( {url: "#three", name: "Gallery Slider"} ) )
				.append( this.templates.menuItemSeperator() )
				.append( this.templates.menuItem( {url: "#four", name: "Media Playlist"} ) )
				.append( this.templates.menuItem( {url: "#five", name: "Map"} ) )
				.append( this.templates.menuItem( {url: "#six", name: "Timeline"} ) );


			// The l10n object generated by wp_localize_script() should be available, but check to be sure.
			// Again, this is a trivial example for demonstration.
			if ( typeof drstk_backbone_modal_l10n === "object" ) {
				this.ui.content = this.$( '.backbone_modal-main article' )
					.append( "<p>" + drstk_backbone_modal_l10n.replace_message + "</p>" );
			}

			// Handle any attempt to move focus out of the modal.
			jQuery( document ).on( "focusin", this.preserveFocus );

			// set overflow to "hidden" on the body so that it ignores any scroll events while the modal is active
			// and append the modal to the body.
			// TODO: this might better be represented as a class "modal-open" rather than a direct style declaration.
			jQuery( "body" ).css( {"overflow": "hidden"} ).append( this.$el );

			// Set focus on the modal to prevent accidental actions in the underlying page
			// Not strictly necessary, but nice to do.
			this.$el.focus();
		},

		/**
		 * Ensures that keyboard focus remains within the Modal dialog.
		 * @param e {object} A jQuery-normalized event object.
		 */
		preserveFocus: function ( e ) {
			"use strict";
			if ( this.$el[0] !== e.target && ! this.$el.has( e.target ).length ) {
				this.$el.focus();
			}
		},

		/* close the modal */
		closeModal: function ( e ) {
			"use strict";

			e.preventDefault();
			this.undelegateEvents();
			jQuery( document ).off( "focusin" );
			jQuery( "body" ).css( {"overflow": "auto"} );
			this.remove();
			drstk.backbone_modal.__instance = undefined;
		},

		/* inserts shortcode and closes modal */
		insertShortcode: function ( e ) {
			var items = this.shortcode.items;
			if (items != undefined){
				start_date = this.shortcode.get('settings').where({name:'start-date'})[0];
				if (start_date != undefined) {start_date = start_date.attributes.value[0];}
				end_date = this.shortcode.get('settings').where({name:'end-date'})[0];
				if (end_date != undefined) {end_date = end_date.attributes.value[0];}
				if ((this.current_tab == 6 && ((start_date != "" && start_date != undefined) || (end_date != "" && end_date != undefined)) && this.validTime() == true) || (this.current_tab == 6 && start_date == undefined && end_date == undefined) || (this.current_tab == 5 && this.validMap() == true) || (this.current_tab == 1 && this.shortcode.items.length == 1) || (this.current_tab != 6 && this.current_tab != 1 && this.current_tab != 5)){
					shortcode = '[drstk_'+this.tabs[this.current_tab];
					ids = []
					jQuery.each(items.models, function(i, item){
						if (item.attributes.repo == 'dpla'){
							pid = "dpla:"+item.attributes.pid;
						} else if (item.attributes.repo == 'drs'){
							pid = item.attributes.pid;
						} else if (item.attributes.repo == 'local'){
							pid = "wp:"+item.attributes.pid;
						}
						ids.push(pid);
					});
					ids.join(",");
					shortcode += ' id="'+ids+'"';
					if (this.current_tab == 5 || this.current_tab == 6){
						var self = this;
						_.each(this.colors, function(color){
							arr = [];
							items = self.shortcode.items.where({'color':color});
							_.each(items, function(i){
								if (i.attributes.repo == 'dpla'){
									pid = "dpla:"+i.attributes.pid;
								} else if (i.attributes.repo == 'drs'){
									pid = i.attributes.pid;
								} else if (i.attributes.repo == 'local'){
									pid = "wp:"+i.attributes.pid;
								}
								arr.push(pid);
							});
							if (arr.length > 0){
								shortcode += ' '+color+'_id="'+arr.join(",")+'"';
							}
						});
					}
					_.each(this.shortcode.get('settings').models, function(setting, i){
						vals = setting.get('value');
						if (vals.length > 0){
							vals = vals.join(",");
							shortcode += ' '+setting.get('name')+'="'+vals+'"';
						}
					});
					shortcode += ']';
					window.wp.media.editor.insert(shortcode);
					this.closeModal( e );
				} else if (this.current_tab == 1 && this.shortcode.items.length > 1){
					alert("There are more than 1 items selected for a single item shortcode.");
			  } else if (this.current_tab == 6){
					titles = this.validTime();
					titles = titles.join("\n");
					alert("The following item(s) are outside the specified date range or custom items may not have date values: \n"+titles);
				} else if (this.current_tab == 5){
					titles = this.validMap();
					titles = titles.join("\n");
					alert("The following item(s) may not have coordinate or location values: \n"+titles);
				}
			} else {
				alert("Please select items before inserting a shortcode");
			}
		},

		setDefaultSettings: function(){
			type = this.shortcode.get('type');
			settings = this.shortcode.get('settings');
			if (type == 'tile'){
				settings.add({
					'name': 'tile-type',//previously called type
					'value':['pinterest-hover'],
					'choices':{'pinterest-below':"Pinterest style with caption below", 'pinterest-hover':"Pinterest style with caption on hover", 'even-row':"Even rows with caption on hover", 'square':"Even Squares with caption on hover"},
					'label': 'Layout Type',
					'tag': 'select'
				});
				settings.add({
					'name': 'text-align',
					'value':['left'],
					'choices':{'center':"Center", 'left':"Left", 'right':"Right"},
					'label':'Caption Alignment',
					'tag':'select'
				});
				settings.add({
					'name': 'cell-height',
					'value':[200],
					'label':'Cell Height (auto for Pinterest style)',
					'tag':'number'
				});
				settings.add({
					'name':'cell-width',
					'value':[200],
					'label':'Cell Width',
					'tag':'number',
					'helper':'Make the height and width the same for squares'
				});
				settings.add({
					'name':'image-size',
					'value':[4],
					'label':'Image Size',
					'tag':'select',
					'choices':{1:'Largest side is 85px', 2:'Largest side is 170px', 3:'Largest side is 340px', 4:'Largest side is 500px', 5:'Largest side is 1000px'}
				});
				settings.add({
					'name':'metadata',
					'label':'Metadata for Captions',
					'tag':'checkbox',
					'value':['full_title_ssi','creator_tesim'],
					'choices':{'full_title_ssi':'Title','creator_tesim':'Creator,Contributor','date_ssi':'Date Created','abstract_tesim':'Abstract/Description'},
				});
				this.shortcode.set('settings', settings);
			} else if (type == 'single'){
				settings.add({
					'name':'image-size',
					'value':[4],
					'label':'Image Size',
					'tag':'select',
					'choices':{1:'Largest side is 85px', 2:'Largest side is 170px', 3:'Largest side is 340px', 4:'Largest side is 500px', 5:'Largest side is 1000px'}
				});
				settings.add({
					'name':'display-video',
					'value':['true'],
					'label':'Display Audio/Video',
					'tag':'checkbox',
					'choices':{0:'true'},
				});
				settings.add({
					'name':'align',
					'value':['center'],
					'label':'Image Alignment',
					'tag':'select',
					'choices':{'center':'Center','left':'Left','right':'Right'}
				});
				settings.add({
					'name': 'caption-align',
					'value':['left'],
					'choices':{'center':"Center", 'left':"Left", 'right':"Right"},
					'label':'Caption Alignment',
					'tag':'select'
				});
				settings.add({
					'name':'caption-position',
					'value':['below'],
					'label':'Caption Position',
					'choices':{'below':'Below','hover':'Over Image on Hover'},
					'tag':'select'
				});
				settings.add({
					'name':'zoom',
					'value':['on'],
					'label':'Enable Zoom',
					'choices':{0:'on'},
					'tag':'checkbox'
				});
				settings.add({
					'name':'zoom-position',
					'value':[1],
					'label':'Zoom Position',
					'helper':'Recommended and Default position:Top Right',
					'choices':{1:'Top Right',2:'Middle Right',3:'Bottom Right',4:'Bottom Corner Right',5:'Under Right',6:'Under Middle',7:'Under Left',8:'Bottom Corner Left',9:'Bottom Left',10:'Middle Left',11:'Top Left',12:'Top Corner Left',13:'Above Left',14:'Above Middle',15:'Above Right',16:'Top Right Corner','inner':"Over image itself"},
					'tag':'select'
				});
				this.shortcode.set('settings', settings);
			} else if (type == 'slider'){
				settings.add({
					'name':'image-size',
					'value':[4],
					'label':'Image Size',
					'tag':'select',
					'choices':{1:'Largest side is 85px', 2:'Largest side is 170px', 3:'Largest side is 340px', 4:'Largest side is 500px', 5:'Largest side is 1000px'}
				});
				settings.add({
					'name':'auto',
					'value':['on'],
					'label':'Auto rotate',
					'choices':{0:'on'},
					'tag':'checkbox'
				});
				settings.add({
					'name':'nav',
					'value':['on'],
					'label':'Next/Prev Buttons',
					'choices':{0:'on'},
					'tag':'checkbox'
				});
				settings.add({
					'name':'pager',
					'value':['on'],
					'label':'Dot pager',
					'choices':{0:'on'},
					'tag':'checkbox'
				});
				settings.add({
					'name':'speed',
					'value':[],
					'label':'Rotation Speed',
					'tag':'number',
					'helper':'Speed is in milliseconds. 5000 milliseconds = 5 seconds'
				});
				settings.add({
					'name': 'max-height',
					'value':[],
					'label':'Max Height',
					'tag':'number'
				});
				settings.add({
					'name':'max-width',
					'value':[],
					'label':'Max Width',
					'tag':'number',
				});
				settings.add({
					'name':'caption',
					'value':['on'],
					'label':'Enable captions',
					'choices':{0:'on'},
					'tag':'checkbox'
				});
				settings.add({
					'name': 'caption-align',
					'value':['center'],
					'choices':{'center':"Center", 'left':"Left", 'right':"Right"},
					'label':'Caption Alignment',
					'tag':'select'
				});
				settings.add({
					'name':'caption-position',
					'value':['relative'],
					'label':'Caption Position',
					'choices':{'absolute':'Over Image','relative':'Below Image'},
					'tag':'select'
				});
				settings.add({
					'name':'caption-width',
					'value':['below'],
					'label':'Caption Width',
					'choices':{'100%':'Width of gallery','image':'Width of image'},
					'tag':'select'
				});
				settings.add({
					'name':'metadata',
					'label':'Metadata for Captions',
					'tag':'checkbox',
					'value':['full_title_ssi','creator_tesim'],
					'choices':{'full_title_ssi':'Title','creator_tesim':'Creator,Contributor','date_ssi':'Date Created','abstract_tesim':'Abstract/Description'},
				});

				this.shortcode.set('settings', settings);
			} else if (type == 'timeline') {
				settings.add({
					'name':'start-date',
					'value':[],
					'label':'Start Date Boundary',
					'tag':'number',
					'helper':'year eg:1960'
				});
				settings.add({
					'name':'end-date',
					'value':[],
					'label':'End Date Boundary',
					'tag':'number',
					'helper':'year eg:1990'
				});
				settings.add({
					'name':'metadata',
					'label':'Metadata',
					'tag':'checkbox',
					'value':['Creator,Contributor'],
					'choices':{'Creator,Contributor':'Creator,Contributor','Abstract/Description':'Abstract/Description'},
				});
				settings.add({
					'name':'increments',
					'label':'Scale Increments',
					'tag':'select',
					'value':[5],
					'choices':{.5:'Very Low',2:'Low',5:'Medium',8:'High',13:'Very High'},
					'helper':'Specifies the granularity to represent items on the timeline'
				});
				_.each(this.colors, function(color){
					settings.add({
						'name':color+'_desc',
						'label':color.charAt(0).toUpperCase()+color.slice(1)+" Description",
						'tag':'text',
						'value':''
					});
				});
				this.shortcode.set('settings', settings);
			} else if (type == 'media') {
				settings.add({
					'name': 'height',
					'value':["270"],
					'label':'Height',
					'helper':'(Enter in pixels or %, Default is 270)',
					'tag':'text'
				});
				settings.add({
					'name':'width',
					'value':["100%"],
					'label':'Width',
					'tag':'text',
					'helper':'(Enter in pixels or %, Default is 100%)'
				});
				//we historically have not provided interface for aspectratio, skin, and listbarwidth, TODO - add these
				this.shortcode.set('settings', settings);
			} else if (type == 'map'){
				settings.add({
					'name':'story',
					'value':['yes'],
					'label':'Story',
					'tag':'checkbox',
					'choices':{0:'yes'},
				});
				settings.add({
					'name':'metadata',
					'label':'Metadata',
					'tag':'checkbox',
					'value':['Creator,Contributor'],
					'choices':{'Creator,Contributor':'Creator,Contributor','Date Created':'Date Created','Abstract/Description':'Abstract/Description'},
				});
				_.each(this.colors, function(color){
					settings.add({
						'name':color+'_desc',
						'label':color.charAt(0).toUpperCase()+color.slice(1)+" Description",
						'tag':'text',
						'value':''
					});
				});
				this.shortcode.set('settings', settings);
			} else {
				//handle old types? tile -> plural, slider -> gallery, single -> item, media -> collection_playlist
			}
		},

		/* navigation between shortcode types */
		navigate: function ( e ) {
			"use strict";
			e.preventDefault();
			this.search_params.page = 1;
			this.geo_count = 0;
			this.time_count = 0;
			this.shortcode.set('settings',  new drstk.Settings()); //TODO - may need to change how this works when we are pulling values from an existing shortcode
			jQuery(".navigation-bar a").removeClass("active");
			this.showTab(jQuery(e.currentTarget).attr("href"));
		},

		/* navigate tabs within a chosen shortcode type */
		navigateShortcode: function( e ){
			var path = jQuery(e.currentTarget).attr("href");
			jQuery(".nav-tab").removeClass("nav-tab-active");
			jQuery(e.currentTarget).addClass("nav-tab-active");
			this.search_params.page = 1;
			jQuery(".pane").hide();
			if (path == '#drs'){
				jQuery("#drs").show();
				jQuery("#drs input[name='search']").val(this.search_params.q);
				this.getDRSitems();
			} else if ( path == '#dpla' ){
				jQuery("#dpla input[name='search']").val(this.search_params.q);
				jQuery("#dpla").show();
				this.getDPLAitems();
			} else if (path == '#local'){
				jQuery("#local").show();
				this.getMediaitems();
			} else if (path == '#selected'){
				jQuery("#selected").show();
				this.getSelecteditems();
				tab_name = this.tabs[this.current_tab]
				var self = this;
				jQuery("#selected #sortable-"+tab_name+"-list").sortable({
					update: function(event, ui){
						_.each(_.clone(self.shortcode.items.models), function(model) {
							model.destroy();
						});
						jQuery.each(event.target.children, function(i, item){
							pid = jQuery(item).find("input").val();
							title = jQuery(item).find(".title").text();
							thumbnail = jQuery(item).find("img").attr("src");
							repo = jQuery(item).find("input").attr("class").split(" ")[1];
							if (self.shortcode.items.length == 0){
								self.shortcode.items = new drstk.Items({
									'title':title,
									'pid':pid,
									'thumbnail':thumbnail,
									'repo':repo
								})
							} else {
								self.shortcode.items.add({
									'title':title,
									'pid':pid,
									'thumbnail':thumbnail,
									'repo':repo
								})
							}
						});
					}
				});
			} else if (path == '#settings'){
				jQuery("#settings").show();
				this.getSettings();
			}
		},

		showTab: function ( id ){
			jQuery(".backbone_modal-main article").html("");
			var title = ""
			switch(id) {
				case "#one":
					this.current_tab = 1
					title = "Single Item"
					//clear items if there are more than one at this point
					if (this.shortcode.items != undefined && this.shortcode.items.length > 1){
						var self = this;
						_.each(_.clone(this.shortcode.items.models), function(item){
							item.destroy();
						});
					}
					break;
				case "#two":
					this.current_tab = 2
					title = "Tile Gallery"
					break;
				case "#three":
					this.current_tab = 3
					title = "Gallery Slider"
					break;
				case "#four":
					this.current_tab = 4
					title = "Media Playlist"
					break;
				case "#five":
					this.current_tab = 5
					title = "Map"
					break;
				case "#six":
					this.current_tab = 6
					title = "Timeline"
					break;
			}
			jQuery(".backbone_modal-main article").append( this.templates.tabContent( {title: title, type: this.tabs[this.current_tab]} ) );
			jQuery(".navigation-bar a[href="+id+"]").addClass("active");
			jQuery("#drs").show();
			this.getDRSitems();
			this.shortcode.set({"type": this.tabs[this.current_tab]});
			this.setDefaultSettings();
		},

		getDRSitems: function( ){
			if (this.current_tab == 4){ this.search_params.avfilter = true; } else { delete this.search_params.avfilter; }
			var self = this;
			if (self.search_params.page == 1){//reset time/geo counts when we're on the first page
				self.geo_count = 0;
				self.time_count = 0;
			}
			tab_name = this.tabs[this.current_tab]
      jQuery.post(drs_ajax_obj.ajax_url, {
         _ajax_nonce: drs_ajax_obj.drs_ajax_nonce,
          action: "get_drs_code",
          params: this.search_params,
      }, function(data) {
         var data = jQuery.parseJSON(data);
				 jQuery("#drs #sortable-"+tab_name+"-list").children("li").remove();
				 jQuery(".drs-pagination").html("");
				 if (jQuery.type(data) === "string"){
					 jQuery(".drs-items").html("<div class='notice notice-warning'><p>No results were retrieved for your query. Please try a different query.</p></div>");
				 } else if (data.response.response.numFound > 0){
           jQuery.each(data.response.response.docs, function(id, item){
						 if (id === 19) {// this is the last one
							 last = true;
						 } else {last = false;}
             if (item.active_fedora_model_ssi == 'CoreFile'){
               if (self.current_tab == 5){ //Maps
                 self.get_item_geographic_or_date_handler(item, true, false, data, last);
               } else if (self.current_tab == 6){ //Timeline
                 self.get_item_geographic_or_date_handler(item, false, true, data, last);
               } else { //Everything else
								this_item = new drstk.Item;
								thumb = "https://repository.library.northeastern.edu"+item.thumbnail_list_tesim[0];
								this_item.set("pid", item.id).set("thumbnail", thumb).set("repo", "drs").set("title", item.full_title_ssi);
								view = new drstk.ItemView({model:this_item});
								jQuery("#drs #sortable-"+tab_name+"-list").append(view.el);
								if(self.shortcode.items != undefined && self.shortcode.items.where({ pid: item.id }).length > 0){
									jQuery("#drs #sortable-"+tab_name+"-list").find("li:last-of-type input").prop("checked", true);
								}
              }
							jQuery(".drs-items").html("");
             }
           });
           self.updateDRSPagination(data);
         } else {
           jQuery(".drs-items").html("<div class='notice notice-warning'><p>No results were retrieved for your query. Please try a different query.</p></div>");
         }
       });
		},

		get_item_geographic_or_date_handler: function(item, mapsBool, timelineBool, collection_data, last) {
			var tab_name = this.tabs[this.current_tab]
			var key_date = {};
			var self = this;
			//AJAX call will be passed to internal WP AJAX
			jQuery.ajax({
				type: "POST",
				url: item_admin_obj.ajax_url,
				data: {
					'action':'get_item_admin',
					'pid' : item.id,
					'_ajax_nonce': item_admin_obj.item_admin_nonce,
				},
				success:function(data) {
					data = jQuery.parseJSON(data);
					key_date[key_date] = Object.keys(data.key_date)[0];
					if ((data && data.geographic && data.geographic.length && mapsBool) || data && data.coordinates && data.coordinates.length && mapsBool)  {
						this_item = new drstk.Item;
						thumb = "https://repository.library.northeastern.edu"+item.thumbnail_list_tesim[0];
						this_item.set("pid", item.id).set("thumbnail", thumb).set("repo", "drs").set("title", item.full_title_ssi);
						view = new drstk.ItemView({model:this_item});
						jQuery("#drs #sortable-"+tab_name+"-list").append(view.el);
						if(self.shortcode.items != undefined && self.shortcode.items.where({ pid: item.id }).length > 0){
							jQuery("#drs #sortable-"+tab_name+"-list").find("li:last-of-type input").prop("checked", true);
						}
						self.geo_count = self.geo_count + 1;
					} else if (data && data.key_date && timelineBool){
						this_item = new drstk.Item;
						thumb = "https://repository.library.northeastern.edu"+item.thumbnail_list_tesim[0];
						this_item.set("pid", item.id).set("thumbnail", thumb).set("repo", "drs").set("title", item.full_title_ssi);
						view = new drstk.ItemView({model:this_item});
						jQuery("#drs #sortable-"+tab_name+"-list").append(view.el);
						if(self.shortcode.items != undefined && self.shortcode.items.where({ pid: item.id }).length > 0){
							jQuery("#drs #sortable-"+tab_name+"-list").find("li:last-of-type input").prop("checked", true);
						}
						jQuery("#drs #sortable-"+tab_name+"-list").find("li:last-of-type").append("<p>Date: "+key_date[key_date]+"</p>");
						self.time_count = self.time_count + 1;
					}  else {
						console.log("no timeline or geo data found");
					}
				},
				error: function(errorThrown){
					console.log(errorThrown);
				},
				complete: function(jqXHR, textStatus){
					if (mapsBool){media_count = self.geo_count}
					if (timelineBool){media_count = self.time_count}
					if ((media_count >= (collection_data.pagination.table.current_page * 20)) && (last === true)){
						if (mapsBool){ self.geo_count = self.geo_count +1}
						if (timelineBool){self.time_count = self.time_count+1}
					}
					if (last === true){
						self.updateDRSPagination(collection_data);
					}
				}
			});
		},

		selectItem: function( e ){
			item = jQuery(e.currentTarget);
			pid = item.val();
			title = item.siblings(".title").text();
			thumbnail = item.siblings("img").attr("src");
			parent = item.parents(".pane").attr("id");
			if (parent == 'drs'){
				repo = 'drs'
			} else if (parent == 'dpla'){
				repo = 'dpla'
			} else {
				repo = 'local'
			}
			if (item.is(":checked")){
				if (this.shortcode.items === undefined){
					this.shortcode.items = new drstk.Items({
						'title':title,
						'pid':pid,
						'thumbnail':thumbnail,
						'repo':repo
					})
				} else if (this.shortcode.items.where({ pid: pid }).length == 0) {
					this.shortcode.items.add({
						'title':title,
						'pid':pid,
						'thumbnail':thumbnail,
						'repo':repo
					})
				}
				if (this.shortcode.get('type') == 'single' && parent == 'drs'){ //if type is single then get the metadata options for the settings
					var self = this;
					//single items can only have one items so we'll clear the rest out
					item.parents("ol").find("input:checked").not(item).each(function(){
						jQuery(this).prop( "checked", false );
						pid = jQuery(this).val();
						var remove = self.shortcode.items.where({ pid: pid });
						self.shortcode.items.remove(remove);
					});
					jQuery.ajax({
						url: item_admin_obj.ajax_url,
            type: "POST",
            data: {
              action: "get_item_admin",
              _ajax_nonce: item_admin_obj.item_admin_nonce,
              pid: pid,
		        }, complete: function(data){
							var data = jQuery.parseJSON(data.responseJSON);
							settings = self.shortcode.get('settings');
							choices_array = Object.keys(data.mods);
							choices = {}
							jQuery.each(choices_array, function(i, choice){
								choices[choice] = choice;
							});
							oldmeta = settings.where({name:'metadata'});
							settings.remove(oldmeta);
							settings.add({
								'name':'metadata',
								'label':'Metadata to Display',
								'tag':'checkbox',
								'value':[],
								'choices':choices,
							});
							self.shortcode.set('settings', settings);
						}
					});
				} else if (this.shortcode.get('type') == 'single' && parent == 'dpla'){
					local_params = this.search_params;
					var self = this;
					local_params.q = pid;
					jQuery.post(dpla_ajax_obj.ajax_url, {
		         _ajax_nonce: dpla_ajax_obj.dpla_ajax_nonce,
		          action: "get_dpla_code",
		          params: local_params,
		      }, function(data) {
						var data = jQuery.parseJSON(data);
						data = data.docs[0]
						choices = {}
						settings = self.shortcode.get('settings');
						if (data.sourceResource.title){
							choices["Title"] = "Title"
						}
						if (data.sourceResource.description){
							choices["Abstract/Description"] = "Abstract/Description"
						}
						if (data.sourceResource.creator){
							choices["Creator"] = "Creator"
						}
						if (data.sourceResource.date.displayDate){
							choices["Date Created"] = "Date Created"
						}
						oldmeta = settings.where({name:'metadata'});
						settings.remove(oldmeta);
						if (Object.keys(choices).length > 0){
							settings.add({
								'name':'metadata',
								'label':'Metadata to Display',
								'tag':'checkbox',
								'value':[],
								'choices':choices,
							});
							self.shortcode.set('settings', settings);
						}
					});
				}
			} else {
				var remove = this.shortcode.items.where({ pid: pid });
				this.shortcode.items.remove(remove);
			}
		},

		updateDRSPagination: function (data){
			media_count = 0;
			if (this.current_tab == 5){media_count = this.geo_count}
			if (this.current_tab == 6){media_count = this.time_count}
			if ( media_count > 0){
	      data.pagination.table.num_pages = Math.ceil(media_count / 20);
	    }
			if (data.pagination.table.num_pages > 1){
	       var pagination = "";
	       if (data.pagination.table.current_page > 1){
	         pagination += "<a href='#' class='prev-page'>&lt;&lt;</a>";
	       } else {
	         pagination += "<a href='#' class='prev-page disabled'>&lt;&lt;</a>";
	       }
	       for (var i = 1; i <= data.pagination.table.num_pages; i++) {
	         if (data.pagination.table.current_page == i){
	           var pagination_class = 'current-page disabled';
	         } else {
	           var pagination_class = '';
	         }
	           pagination += "<a href='#' class='"+pagination_class+"'>" + i + "</a>";
	       }
	       if (data.pagination.table.current_page == data.pagination.table.num_pages){
	         pagination += "<a href='#' class='next-page' data-val='"+data.pagination.table.num_pages+"'>&gt;&gt;</a>";
	       } else {
	         pagination += "<a href='#' class='next-page disabled' data-val='"+data.pagination.table.num_pages+"'>&gt;&gt;</a>";
	       }
				 jQuery(".drs-pagination").html("<span class='tablenav'><span class='tablenav-pages'>" + pagination + "</span></span>");
	    } else {
				jQuery(".drs-pagination").html("");
			}
		},

		paginate: function( e ){
      val = jQuery(e.currentTarget).html();
			val = jQuery.trim(val);
			type = jQuery(e.currentTarget).parents(".pane").attr("id");
			current_page = jQuery("#"+type+" .tablenav-pages .current-page").html();
      if (val == '&lt;&lt;'){
				val = parseInt(current_page) - 1;
      }
      if (val == '&gt;&gt;'){
				val = parseInt(current_page) + 1;
				if (jQuery("#"+type+" .tablenav-pages .current-page").next('a').html() == '&gt;&gt;'){//last page
					val = 0;
				}
      }
      if (jQuery.isNumeric(val) && val != 0){
        this.search_params.page = val;
				if (type == 'drs'){
					this.getDRSitems();
				} else if (type == 'dpla'){
					this.getDPLAitems();
				}
      }
		},

		getDPLAitems: function( ){
			if (this.current_tab == 4){ this.search_params.avfilter = true; } else { delete this.search_params.avfilter; }
			if (this.current_tab == 5){ this.search_params.spatialfilter = true; } else { delete this.search_params.spatialfilter; }
			if (this.current_tab == 6){ this.search_params.timefilter = true; } else { delete this.search_params.timefilter; }
			var self = this;
			tab_name = this.tabs[this.current_tab];
			console.log(this.search_params);
      jQuery.post(dpla_ajax_obj.ajax_url, {
         _ajax_nonce: dpla_ajax_obj.dpla_ajax_nonce,
          action: "get_dpla_code",
          params: this.search_params,
      }, function(data) {
				  var data = jQuery.parseJSON(data);
					jQuery("#dpla #sortable-"+tab_name+"-list").children("li").remove();
         if (data.count > 0){
					 jQuery(".dpla-items").html("");
           jQuery.each(data.docs, function(id, item){
						 this_item = new drstk.Item;
						 this_item.set("pid", item.id).set("thumbnail", item.object).set("repo", "dpla").set("title", item.sourceResource.title);
						 view = new drstk.ItemView({model:this_item});
						 jQuery("#dpla #sortable-"+tab_name+"-list").append(view.el);
						 if(self.shortcode.items != undefined && self.shortcode.items.where({ pid: item.id }).length > 0){
							 jQuery("#dpla #sortable-"+tab_name+"-list").find("li:last-of-type input").prop("checked", true);
						 }
						 if (self.current_tab == 6){
							jQuery("#drs #sortable-"+tab_name+"-list").find("li:last-of-type").append("<p>Date: "+item.sourceResource.date.dislpayDate+"</p>");
						 }
           });
					 if (self.search_params.q != ""){//too much pagination if there isn't a query
						 self.updateDPLAPagination(data);
					 }
         } else {
           jQuery(".dpla-items").html("<div class='notice notice-warning'><p>No results were retrieved for your query. Please try a different query.</p></div>");
					 jQuery("#dpla-pagination").html("");
         }
       });
		},

		updateDPLAPagination: function( data ){
			num_pages = Math.round(data.count/data.limit);
			console.log(num_pages);
			current_page = this.search_params.page;
			console.log(current_page);
			if (num_pages > 1){
	       var pagination = "";
				 //TODO - set up DPLA pagination
	    } else {
				jQuery("#dpla-pagination").html("");
			}
		},

		search: function( e ){
			this.search_params.q = jQuery(e.currentTarget).siblings("input[type='text']").val();
			parent = jQuery(e.currentTarget).parents(".pane").attr("id");
			if (parent == 'drs'){
				this.getDRSitems();
			} else if (parent == 'dpla'){
				this.getDPLAitems();
			}
		},

		getSelecteditems: function( ){
			tab_name = this.tabs[this.current_tab];
			count = this.shortcode.items.length;
	     if (count > 0){
				 jQuery(".selected-items").html("");
	       jQuery("#selected #sortable-"+tab_name+"-list").children("li").remove();
				 var self = this;
	       jQuery.each(this.shortcode.items.models, function(i, item) {
						var itemView = new drstk.ItemView({
		            model:item
		        });
		        jQuery("#selected #sortable-"+tab_name+"-list").append(itemView.el);
						if (self.current_tab == 5 || self.current_tab == 6){
							colors = "";
							_.each(self.colors, function(color){
								colors += "<option value='"+color+"'";
								if (item.attributes.color == color){ colors += " selected='selected'"; }
								colors += ">"+color.charAt(0).toUpperCase()+color.slice(1)+"</option>";
							});
							jQuery("#selected #sortable-"+tab_name+"-list").find("li:last-of-type label").append('<br/>Color: <select name="color"><option value="">Choose one</option>'+colors+'</select>');
						}
						if(self.shortcode.items.where({ pid: item.attributes.pid }).length > 0){
							jQuery("#selected #sortable-"+tab_name+"-list").find("li:last-of-type input").prop("checked", true);
						}
	        });
	     } else {
	       jQuery(".selected-items").html("You haven't selected any items yet.");
				 jQuery("#selected #sortable-"+tab_name+"-list").children("li").remove();
	     }
		},

		getSettings: function( ) {
			jQuery("#settings").html("<table />");
			_.each(this.shortcode.get('settings').models, function(setting, i) {
				var settingView = new drstk.SettingView({
						model:setting
				});
				jQuery("#settings table").append(settingView.el);
				jQuery("#settings table tr:last-of-type").addClass(setting.get('name'));
			});
		},

		settingsChange: function(e){
			if (jQuery(e.currentTarget).attr("type") == "checkbox"){
				name = jQuery(e.currentTarget).parents("tr").attr("class");
				setting = this.shortcode.get('settings').where({name:name})[0];
				var vals = []
				jQuery(e.currentTarget).parents("td").find("input[type='checkbox']").each(function(){
					if (jQuery(this).is(":checked")){
						vals.push(jQuery(this).attr("name"));
					}
				});
				setting.set('value', vals);
			} else {
				name = jQuery(e.currentTarget).attr("name");
				setting = this.shortcode.get('settings').where({name:name})[0];
				val = jQuery(e.currentTarget).val();
				setting.set('value', [val]);
			}
		},

		validTime: function(){
			return_arr = [];
			no_year = [];
			key_date_list = [];
			_.each(_.clone(this.shortcode.items.where({repo:'drs'})), function(item){
				jQuery.ajax({
					url: item_admin_obj.ajax_url,
					type: "POST",
					async: false,
					data: {
						action: "get_item_admin",
						_ajax_nonce: item_admin_obj.item_admin_nonce,
						pid: item.get('pid'),
					}, success: function(data){
						data = jQuery.parseJSON(data);
						var key_date_year = Object.keys(data.key_date)[0].split("/")[0];
						key_date_list.push({year:key_date_year, name:data.mods.Title[0]});
					}
				});
			});
			_.each(_.clone(this.shortcode.items.where({repo:'local'})), function(item){
				jQuery.ajax({
					url: item_admin_obj.ajax_url,
					type: "POST",
					async: false,
					data: {
						action: "get_custom_meta",
						_ajax_nonce: item_admin_obj.item_admin_nonce,
						pid: item.get('pid'),
					}, success: function(data){
						if (data._timeline_date == undefined){
							no_year.push(item.get('title'));
						} else {
							var key_date_year = data._timeline_date[0].split("/")[0];
							key_date_list.push({year:key_date_year, name:item.get('title')});
						}
					}
				});
			});
			var self = this;
			key_date_list.forEach(function(each_key){
				start_date = self.shortcode.get('settings').where({name:'start-date'})[0];
				start_date = start_date.attributes.value[0];
				end_date = self.shortcode.get('settings').where({name:'end-date'})[0];
				end_date = end_date.attributes.value[0];
				if(each_key.year < start_date || each_key.year > end_date){
          return_arr.push(each_key.name);
				}
			});
			if (return_arr.length > 0 || no_year.length > 0){
				return return_arr.concat(no_year);
			} else {
				return true;
			}
		},

		validMap: function(){
			no_map = [];
			key_date_list = [];
			_.each(_.clone(this.shortcode.items.where({repo:'local'})), function(item){
				jQuery.ajax({
					url: item_admin_obj.ajax_url,
					type: "POST",
					async: false,
					data: {
						action: "get_custom_meta",
						_ajax_nonce: item_admin_obj.item_admin_nonce,
						pid: item.get('pid'),
					}, success: function(data){
						if (data._map_coords == undefined || data._map_coords == ""){
							no_map.push(item.get('title'));
						}
					}
				});
			});
			if (no_map.length > 0){
				return no_map;
			} else {
				return true;
			}
		},

		changeColor: function(e){
			color = jQuery(e.currentTarget).val();
			if (color != ""){
				pid = jQuery(e.currentTarget).siblings(".tile").val();
				item = this.shortcode.items.where({pid: pid});
				item[0].set({'color':color});
			}
		},

		getMediaitems: function(){
			jQuery("#local").html("<a class='button' id='wp_media'>Add or Browse Local Items</a><br/>");
			if (this.shortcode.items != undefined && this.shortcode.items.where({repo:'local'}).length > 0){
				var self = this;
				_.each(this.shortcode.items.where({repo:'local'}), function(item){
					pid = item.get('pid');
					thumbnail = item.get('thumbnail');
					repo = "local";
					title = item.get('title');
					this_item = new drstk.Item;
					this_item.set("pid", pid).set("thumbnail", thumbnail).set("repo", repo).set("title", title);
					view = new drstk.ItemView({model:this_item});
					jQuery("#local").append(view.el);
					if(self.shortcode.items != undefined && self.shortcode.items.where({ pid: pid }).length > 0){
						jQuery("#local").find("li:last-of-type input").prop("checked", true);
					}
				});

			}
		},

		addMediaItems: function(e){
			if (typeof(frame) !== 'undefined') frame.close();
			if (this.current_tab == 1){
				multiple = false;
			} else {
				multiple = true;
			}
			// if (this.current_tab == 4){
			// 	type = ['audio','video'];
			// } else {
			// 	type = 'image';
			// }//TODO shortcodes have to handle wp items which may not have thumbnails and DPLA items which may have thumbnails that fail
			var self = this;
			frame = wp.media.frames.drstk_frame = wp.media({
				title: "Select Images",
				// library: {
				// 	type: type
				// },
				button: {
					text: "Add Selected Images"
				},
				multiple: multiple
			});
			frame.on('select', function() {
				var files = frame.state().get('selection').toJSON();
				jQuery.each(files, function(i) {
					pid = this.id.toString();
					title = this.title;
					thumbnail = (this.sizes != undefined) ? this.sizes.thumbnail.url : this.image.src;
					repo = "local";
					if (self.shortcode.items === undefined || self.shortcode.items.where({ pid: pid }).length == 0){
						this_item = new drstk.Item;
						this_item.set("pid", pid).set("thumbnail", thumbnail).set("repo", repo).set("title", title);
						if (self.shortcode.items === undefined){
							self.shortcode.items = new drstk.Items(this_item);
						} else if (self.shortcode.items.where({ pid: pid }).length == 0){
							self.shortcode.items.add(this_item);
						}
						view = new drstk.ItemView({model:this_item});
						jQuery("#local").append(view.el);
						jQuery("#local").find("li:last-of-type input").prop("checked", true);
					}
					if (self.current_tab == 1){
						jQuery.ajax({
							url: item_admin_obj.ajax_url,
	            type: "POST",
	            data: {
	              action: "get_post_meta",
	              _ajax_nonce: item_admin_obj.item_admin_nonce,
	              pid: pid,
			        }, success: function(data){
								choices = {}
								settings = self.shortcode.get('settings');
								if (data.post_title){
									choices["title"] = "Title"
								}
								if (data.post_excerpt){
									choices["caption"] = "Caption"
								}
								oldmeta = settings.where({name:'metadata'});
								settings.remove(oldmeta);
								if (Object.keys(choices).length > 0){
									settings.add({
										'name':'metadata',
										'label':'Metadata to Display',
										'tag':'checkbox',
										'value':[],
										'choices':choices,
									});
									self.shortcode.set('settings', settings);
								}
							}
						});
					}
				});
			}).open();
		}
	} );

jQuery( function ( $ ) {
	"use strict";
	/**
	 * Attach a click event to the meta-box button that instantiates the Application object, if it's not already open.
	 */
	$( "#drs-backbone_modal" ).click( function ( e ) {
		e.preventDefault();
		if ( drstk.backbone_modal.__instance === undefined ) {
			drstk.backbone_modal.__instance = new drstk.backbone_modal.Application();
		}
	} );
} );
