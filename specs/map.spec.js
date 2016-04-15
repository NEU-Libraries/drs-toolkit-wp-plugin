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
                        pid: 'neu:180455',
                        title: '"Mr. Ralph Hilton, V.P. of the Kiwanis Club of Roxbury, presents Kiwanis trophies to the captains of the winning teams in the Roxbury Clubhouse, Boys\' Clubs of Boston International Basketball Tournament."',
                        coordinates: [ '42.4072107', '-71.3824374' ],
                        metadata: '1961<br></div>',
                        url: 'http://hdl.handle.net/2047/d20162914'
                    },
                    {
                        pid: 'neu:183764',
                        title: '"Overseer, Walter Robb, III, Presents Trophies And Individual Awards" at a Boys\' Club basketball tournament.',
                        coordinates: [ '42.4072107', '-71.3824374' ],
                        metadata: '1965<br></div>',
                        url: 'http://hdl.handle.net/2047/d20163576'
                    },
                    {
                        pid: 'neu:132175',
                        title: '152 Harold Street.',
                        coordinates: [ '42.3159452', '-71.0904327' ],
                        metadata: 'Unknown (Photographer)<br></div>May 1958<br/>',
                        url: 'http://hdl.handle.net/2047/d20157802'
                    }
                    ,
                    {
                        pid: 'neu:131795',
                        title: '18 Fountain Street, Roxbury, Mass.',
                        coordinates: [ '42.3242716', '-71.0848434' ],
                        metadata: 'Shwachman, Irene (Photographer)<br></div>April 11, 1962<br/>',
                        url: 'http://hdl.handle.net/2047/d20157726'
                    },
                    {
                        pid: 'neu:212654',
                        title: '1949-1950 Roxbury Clubhouse basketball team posing with their trophy.',
                        coordinates: [ '42.4072107', '-71.3824374' ],
                        metadata: '1950<br></div>', 
                        url: 'http://hdl.handle.net/2047/d20169346'
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

    describe('Function fitToBounds', function () {
        it('returns empty array if no items are passed', function() {
            expect(fitToBounds()).toEqual([]);
        });

        it('returns bounds if for all items that are passed', function() {
            expect(fitToBounds(
                [
                    {
                        coordinates: ['10.10', '20.20'],
                        title: 'Test Item 1'
                    },
                    {
                        coordinates: ['30.10', '40.20'],
                        title: 'Test Item 2'
                    }
                ], [], null
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

    describe('Function pidExists', function () {
        beforeEach(function() {
            loadFixtures('map.html');
        });
        it('returns false if pid does not exist for the given input', function() {
            expect(pidExists($($('.coordinate')[0]))).toEqual(false);
        });
        it('returns true if the pid do exist for the given input', function() {
            expect(pidExists($($('.coordinates')[0]))).toEqual(true);
        });
    });
    describe('Function titleExists', function () {
        beforeEach(function() {
            loadFixtures('map.html');
        });
        it('returns false if title does not exist for the given input', function() {
            expect(titleExists($($('.coordinate')[0]))).toEqual(false);
        });
        it('returns true if the title do exist for the given input', function() {
            expect(titleExists($($('.coordinates')[0]))).toEqual(true);
        });
    });
    describe('Function coordinatesExists', function () {
        beforeEach(function() {
            loadFixtures('map.html');
        });
        it('returns false if coordinates does not exist for the given input', function() {
            expect(coordinatesExists($($('.coordinate')[0]))).toEqual(false);
        });
        it('returns true if the coordinates do exist for the given input', function() {
            expect(coordinatesExists($($('.coordinates')[0]))).toEqual(true);
        });
    });
    describe('Function metaDataExists', function () {
        beforeEach(function() {
            loadFixtures('map.html');
        });
        it('returns false if metadata does not exist for the given input', function() {
            expect(metaDataExists($($('.coordinate')[0]))).toEqual(false);
        });
        it('returns true if the metadata does if valid jqSelector passed', function() {
            expect(metaDataExists($($('.coordinates')[0]))).toEqual(true);
        });
    });
    describe('Function urlExists', function () {
        beforeEach(function() {
            loadFixtures('map.html');
        });
        it('returns false if url does not exist for the given input', function() {
            expect(urlExists($($('.coordinate')[0]))).toEqual(false);
        });
        it('returns true if the url does if valid jqSelector passed', function() {
            expect(urlExists($($('.coordinates')[0]))).toEqual(true);
        });
    });
    describe('Function getColorDescriptions', function() {
        beforeEach(function() {
            loadFixtures('map.html');
        });
        it('returns empty object when color descriptions don\'t exist', function() {
            expect(getColorDescriptions($('#map1'))).toEqual({});
        });
        it('returns object with the color descriptions as properties', function() {
            expect(getColorDescriptions($('#map'))).toEqual({
                red: 'Red Description',
                blue: 'Blue Description',
                green: 'Green Description',
                yellow: 'Yellow Description',
                orange: 'Orange Description'
            });
        });
    });
    describe('Function getColorGroups', function() {
        beforeEach(function() {
            loadFixtures('map.html');
        });
        it('returns empty object when color groups don\'t exist', function() {
            expect(getColorGroups($('#map1'))).toEqual({});
        });
        it('returns object with the color groups as properties', function() {
            expect(getColorGroups($('#map'))).toEqual({
                red: 'neu:180455, neu:125675',
                blue: 'neu:183764, neu:132100',
                green: 'neu:132175',
                yellow: 'neu:131795',
                orange: 'neu:212654'
            });
        });
    });

    describe('Function getCustomItems', function () {

        beforeEach(function() {
            loadFixtures('map.html');
        });
        it('returns empty item array when there is no input', function() {
            expect(getCustomItems($('.custom-coodinate')))
                .toEqual([]);
        });

        it('returns item array when there is correct input', function() {
            expect(getCustomItems($('.custom-coordinates')))
                .toEqual([
                    {
                        title: 'Custom Item 1 Title',
                        coordinates: [ '42.3600825','-71.0588801' ],
                        description: 'Custom Item 1 Description',
                        url: 'www.google.com',
                        colorgroup: 'red'
                    },
                    {
                        title: 'Custom Item 2 Title',
                        coordinates: [ '42.40843','-71.0536625' ],
                        description: 'Custom Item 2 Description',
                        url: 'www.google.com',
                        colorgroup: 'blue'
                    }
                ]);
        });
    });

    describe('Function isStoryModeEnabled', function () {

        beforeEach(function() {
            loadFixtures('map.html');
        });
        it('returns false when there is no story mode or it is disabled', function() {
            expect(isStoryModeEnabled($('#map1'))).toEqual(false);
        });

        it('returns true when the story mode is enabled', function() {
            expect(isStoryModeEnabled($('#map'))).toEqual(true);
        });
    });
});
