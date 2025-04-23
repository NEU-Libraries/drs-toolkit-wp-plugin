const { appendSingleItem } = require('./getSelectedItems');
// const jquery = require('jquery');

global.jQuery = global.$ = jest.fn(() => ({
    append: jest.fn(),
    prop: jest.fn(),
}));

const mockedJQuery = jest.fn();
mockedJQuery.mockImplementation((selector) => {
    switch (selector) {
        case '#selected #sortable-tabName-list':
            return {
                append: jest.fn(),
            };
        default:
            return {};
    }
});

jest.mock('jQuery', () => mockedJQuery);

describe('appendSingleItem function', () => {
    let mockItem, mockDrstk, mockShortcode, mockOptions;

    beforeEach(() => {
        // Setup your mock data for each test case
        jest.clearAllMocks();
        mockItem = { attributes: { pid: 'testPID' } };
        mockDrstk = { ItemView: jest.fn() };
        mockShortcode = {
            get: jest.fn(),
            items: {
                where: jest.fn(),
            },
        };
        mockOptions = { settings: {} };
    });

    it('creates and appends itemView', () => {
        const mockTabs = ['tab1', 'tab2'];
        const mockCurrentTab = 0;
        appendSingleItem(mockItem, { currentTab: mockCurrentTab, tabs: mockTabs, shortcode: mockShortcode, drstk: mockDrstk, options: mockOptions });

        expect(mockedJQuery).toHaveBeenCalledWith('#selected #sortable-tab1-list');
        // Add more assertions here
    });
});

// describe('appendSingleItem', () => {
//     it('should append the item view to the list', () => {
//         const mockItem = {}; // Provide your mock item data here
//         const mockOptions = {
//             currentTab: 0,
//             tabs: { 0: 'tabName' },
//             drstk: {
//                 ItemView: function () {
//                     this.el = 'mockEl';
//                 },
//             },
//             // ... other necessary mock options
//         };

//         appendSingleItem(mockItem, mockOptions);

//         // Check that jQuery was called with the right selector
//         expect(mockedJQuery).toHaveBeenCalledWith('#selected #sortable-tabName-list');

//         // Check that the `append` function was called with the expected argument
//         expect(mockedJQuery().append).toHaveBeenCalledWith('mockEl');
//     });
// });
