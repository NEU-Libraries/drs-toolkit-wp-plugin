jasmine.getFixtures().fixturesPath = 'specs/';

describe('Maps Unit Tests', function() {

    describe('Function getCordinatesFromString', function () {
        it('returns null when there is no input', function () {
            expect(getCordinatesFromString()).toEqual(null);
        });

        it('returns array of coordinates when there is input', function () {
            expect(getCordinatesFromString('10.10, 15.20')).toEqual(['10.10', '15.20']);
        });
    });

    describe('Function getItemsFromJqueryArray', function () {

        beforeEach(function() {
            loadFixtures('map.html');
        });
        it('returns empty item array when there is no input', function() {
            expect(getItemsFromJqueryArray($('.coodinate')))
                .toEqual([]);
        });

        it('returns item array when there is correct input', function() {
            expect(getItemsFromJqueryArray($('.coordinates')))
                .toEqual([
                    {
                        title: '"Mr. Ralph Hilton, V.P. of the Kiwanis Club of Roxbury, presents Kiwanis trophies to the captains of the winning teams in the Roxbury Clubhouse, Boys\' Clubs of Boston International Basketball Tournament."',
                        coordinates: [ '42.4072107', '-71.3824374' ]
                    },
                    {
                        title: '"Overseer, Walter Robb, III, Presents Trophies And Individual Awards" at a Boys\' Club basketball tournament.',
                        coordinates: [ '42.4072107', '-71.3824374' ]
                    },
                    {
                        title: '152 Harold Street.',
                        coordinates: [ '42.3159452', '-71.0904327' ] }
                    ,
                    {
                        title: '18 Fountain Street, Roxbury, Mass.',
                        coordinates: [ '42.3242716', '-71.0848434' ]
                    },
                    {
                        title: '1949-1950 Roxbury Clubhouse basketball team posing with their trophy.',
                        coordinates: [ '42.4072107', '-71.3824374' ]
                    }
                ]);
        });
    });

    describe('Function createMap', function () {
        beforeEach(function() {
            loadFixtures('map.html');
        });
        it('returns null if no mapID is passed', function() {
            expect(createMap()).toEqual(null);
        });
        it('returns not return a null if mapID is passed', function() {
            expect(createMap('map')).not.toEqual(null);
        });
    });

    describe('Function getBoundsForMap', function () {
        it('returns empty array if no items are passed', function() {
            expect(getBoundsForMap()).toEqual([]);
        });

        it('returns bounds if for all items that are passed', function() {
            expect(getBoundsForMap(
                [
                    {
                        coordinates: ['10.10', '20.20'],
                        title: 'Test Item 1'
                    },
                    {
                        coordinates: ['30.10', '40.20'],
                        title: 'Test Item 2'
                    }
                ]
            ))
                .toEqual([
                    ['10.10', '20.20'],
                    ['30.10', '40.20']
                ]);
        });
    });

    describe('Function getApiKey', function () {
        beforeEach(function() {
            loadFixtures('map.html');
        });
        it('returns undefined if invalid jqSelector passed', function() {
            expect(getApiKey($('#maps'))).toEqual(undefined);
        });
        it('returns api key if valid jqSelector passed', function() {
            expect(getApiKey($('#map'))).toEqual('pk.eyJ1IjoiZGhhcmFtbWFuaWFyIiwiYSI6ImNpbTN0cjJmMTAwYmtpY2tyNjlvZDUzdXMifQ.8sUclClJc2zSBNW0ckJLOg');
        });
    });

    describe('Function getProjectKey', function () {
        beforeEach(function() {
            loadFixtures('map.html');
        });
        it('returns undefined if invalid jqSelector passed', function() {
            expect(getProjectKey($('#maps'))).toEqual(undefined);
        });
        it('returns project key if valid jqSelector passed', function() {
            expect(getProjectKey($('#map'))).toEqual('dharammaniar.pfnog3b9');
        });
    });
});
