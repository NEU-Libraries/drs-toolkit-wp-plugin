jQuery(document).ready(function($) {
    
    var eventsList = getItemsFromJqueryArrayTimelineArray($('.timelineclass'));
    
    var increments = $('#timeline-increments').data('increments');
    
    var colorDescriptions = $('#timeline-color-desc').data();
    
    var colorIds = $('#timeline-color-ids').data();
    
    var sortedColorIds = Object.keys(colorIds).sort(function(a,b){return colorIds[a]-colorIds[b]})
    
    var options = {scale_factor:increments};
    
    var finalTimelineJson = {events:eventsList};
    
    window.timeline = new TL.Timeline('timeline-embed', finalTimelineJson, options);
    
    itemBackgroundModifier($('.tl-timemarker-content-container'), sortedColorIds, colorIds);
});

	 function getItemsFromJqueryArrayTimelineArray(jqArray) {
		 
		var items = [];
		
		var timelineClass = '.timelineclass';
		
		jqArray.each(function(index) {
			items.push({
				media: {url : genericRetrieval(index, timelineClass, 'url'), caption:genericRetrieval(index, timelineClass, 'caption'), credit:genericRetrieval(index, timelineClass, 'capiton')},
				start_date: {year:genericRetrieval(index, timelineClass, 'year'), month:genericRetrieval(index, timelineClass, 'month'), day:genericRetrieval(index, timelineClass, 'day')},
				text: {headline:genericRetrieval(index, timelineClass, 'headline'), text:genericRetrieval(index, timelineClass, 'text')}
		 });
	 });
	 
	 return items;
	}
	
	
    function genericRetrieval(index, className, element){
		
		if(index == null || className == null || element == null){
			return null;
		}
     
     return $($(className)[index]).data(element);
     
	}
	
	function itemBackgroundModifier(jqArray, sortedColorIds, colorIds){
		
		var itemMarkerClass = '.tl-timemarker-content-container';
		var counter = 0;
		jqArray.each(function(index) {
			$($(itemMarkerClass)[index]).css('background-color', colorIds[$(sortedColorIds).get(counter)]);
			counter++;
		 });
		
	}
