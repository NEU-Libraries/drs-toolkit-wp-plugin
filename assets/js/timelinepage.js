jQuery(document).ready(function($) {

    var eventsList = [];

    $('.timelineclass').each(function(index) {

        eventsList.push({
            media: {url : $($('.timelineclass')[index]).data('url'), caption:$($('.timelineclass')[index]).data('caption'), credit:$($('.timelineclass')[index]).data('credit')},
			start_date: {year:$($('.timelineclass')[index]).data('year'), month:$($('.timelineclass')[index]).data('month'), day:$($('.timelineclass')[index]).data('day')},
			text: {headline:$($('.timelineclass')[index]).data('headline'), text:$($('.timelineclass')[index]).data('text')}
        });
    });
    
    var finalTimelineJson = {events:eventsList};
    console.log(finalTimelineJson);
    window.timeline = new TL.Timeline('timeline-embed', finalTimelineJson);
});