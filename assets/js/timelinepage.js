var page_id= "";

jQuery(document).ready(function($) {
	console.log(timeline_obj.post_id);
    var eventsList = getItemsFromJqueryArrayTimelineArray($('.timelineclass'));

    var increments = $('#timeline-increments').data('increments');

    var options = {scale_factor:increments};

    var finalEventsListAfterCustomData = getTimelineCustomItems($('.custom-timeline'), eventsList);

    var colorIds = getcolorIdsData($('#timeline-color-ids'));

    for (var attrname in finalEventsListAfterCustomData['colorDict']) { colorIds[attrname] = finalEventsListAfterCustomData['colorDict'][attrname]; }

    var finalTimelineJson = {events:finalEventsListAfterCustomData['eventsList']};

    window.timeline = new TL.Timeline('timeline-embed', finalTimelineJson, options);

    itemBackgroundModifier($('.tl-timemarker-content-container'), colorIds);

});

	function getItemsFromJqueryArrayTimelineArray(jqArray) {

		var items = [];

		var timelineClass = '.timelineclass';

		jqArray.each(function(index) {
      var headline = "<a href='"+genericRetrieval(index, timelineClass, 'full')+"'>"+genericRetrieval(index, timelineClass, 'headline')+"</a>";
			items.push({
				media: {url : genericRetrieval(index, timelineClass, 'url'), caption:genericRetrieval(index, timelineClass, 'caption'), credit:genericRetrieval(index, timelineClass, 'caption')},
				start_date: {year:genericRetrieval(index, timelineClass, 'year'), month:genericRetrieval(index, timelineClass, 'month'), day:genericRetrieval(index, timelineClass, 'day')},
				text: {headline:headline, text:genericRetrieval(index, timelineClass, 'text')},
        unique_id: genericRetrieval(index, timelineClass, 'pid').replace(":","")
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

	function getTimelineCustomItems(jqArray, eventsList){

		var finalDictionary = {};

		if(eventsList[0].media.url == ''){
			eventsList = [];
		}

		var customTimelineClass = '.custom-timeline';

		var customDate = {};
		var colorDescObj = {};
		jqArray.each(function(index){
			var currentDate = genericRetrieval(index, customTimelineClass, 'year').toString().concat(genericRetrieval(index, customTimelineClass, 'month').toString()).concat(genericRetrieval(index, customTimelineClass, 'day').toString());
			var colorgroup = genericRetrieval(index, customTimelineClass, 'colorgroup');
			customDate[currentDate] = colorgroup;
			eventsList.push({
				media: {url : genericRetrieval(index, customTimelineClass, 'url'), caption:genericRetrieval(index, customTimelineClass, 'caption'), credit:genericRetrieval(index, customTimelineClass, 'caption')},
				start_date: {year:genericRetrieval(index, customTimelineClass, 'year'), month:genericRetrieval(index, customTimelineClass, 'month'), day:genericRetrieval(index, customTimelineClass, 'day')},
				text: {headline:genericRetrieval(index, customTimelineClass, 'title'), text:genericRetrieval(index, customTimelineClass, 'description')}
			});

		});

		finalDictionary['eventsList'] = eventsList;
		finalDictionary['colorDict'] = customDate;

		return finalDictionary;
	}

	function itemBackgroundModifier(jqArray, colorIds){
		var itemMarkerClass = '.tl-timemarker-content-container';
		jqArray.each(function(index) {
      pid = $(this).parents(".tl-timemarker").attr("id");
      pid = pid.split("-")[0];
			$("#"+pid+"-marker "+itemMarkerClass).css('background-color', colorIds[pid]);
		 });

	}

	function getcolorIdsData(colorIdElement){
		if(colorIdElement == null){return null;}
		return colorIdElement.data();
	}

	function getcolorDescData(colorDescElement){
		if(colorDescElement == null){return null;}
		return (colorDescElement).data();
	}
