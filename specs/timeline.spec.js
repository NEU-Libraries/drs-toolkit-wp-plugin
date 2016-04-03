jasmine.getFixtures().fixturesPath = 'specs/';

describe('Timeline Unit Tests', function() {

    describe('Function to check the null statement of generic retrieval', function () {
        it('returns null when there is no input', function () {
            expect(genericRetrieval()).toEqual(null);
        });
    });
    
    describe('Function generic Retrieval', function () {
        beforeEach(function() {
            loadFixtures('timeline.html');
        });
        
        it('returns timeline specific data with appropriate data parameter is passed', function() {
            expect(genericRetrieval($('.timelineclass')[0],'.timelineclass','year')).not.toEqual(1961);
        });
    });

    describe('Function getItemsFromJqueryArrayTimelineArray', function () {

        beforeEach(function() {
            loadFixtures('timeline.html');
        });
        it('returns empty item array when there is no input', function() {
            expect(getItemsFromJqueryArrayTimelineArray($('.time')))
                .toEqual([]);
        });

        it('returns item array when there is correct input', function() {
            expect(getItemsFromJqueryArrayTimelineArray($('.timelineclass')))
                .toEqual([
                    {
                        media: {url:'https://repository.library.northeastern.edu/downloads/neu:180456?datastream_id=thumbnail_3',caption:'Boston Boys and Girls Club Photographs',credit:undefined},
                        start_date: {year:1961,month:'01',day:'01'},
                        text: {headline:'Boston Boys and Girls Club Photographs',text:'"Mr. Ralph Hilton, V.P. of the Kiwanis Club of Roxbury, presents Kiwanis trophies to the captains of the winning teams in the Roxbury Clubhouse, Boys Clubs of Boston International Basketball Tournament."'}
                    },
                    {
                        media: {url:'https://repository.library.northeastern.edu/downloads/neu:183765?datastream_id=thumbnail_3',caption:'Boston Boys and Girls Club Photographs',credit:undefined},
                        start_date: {year:1965,month:'01',day:'01'},
                        text: {headline:'Boston Boys and Girls Club Photographs',text:'"Overseer, Walter Robb, III, Presents Trophies And Individual Awards" at a Boys Club basketball tournament.'}
                    }
                ]);
        });
    });
});