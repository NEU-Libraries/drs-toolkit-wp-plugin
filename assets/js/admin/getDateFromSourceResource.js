/**
 * Extracts date information from a source object
 * @param {Object} source - The source object containing date-related keys
 * @returns {Array} - Returns an array containing the begin and end dates
 */
function getDateFromSourceResource(source) {
    let date = source.date || '';
    let begin_date = '',
        end_date = '';

    // Check if 'date' is an array and use its first element if it is.
    if (Array.isArray(date)) {
        date = date[0]?.displayDate || '';
    } else {
        date = date.displayDate || '';
    }

    if (date) {
        const [start, end] = date.split('-');
        begin_date = extractDate(start, source.date.begin);
        end_date = extractDate(end, source.date.end);
    }

    return [begin_date, end_date];
}

/**
 * Extracts a date value using a preferred and fallback value.
 * @param {string} preferred - The preferred date value
 * @param {string} fallback - The fallback date value
 * @returns {string} - Returns the year part of the extracted date
 */
function extractDate(preferred, fallback) {
    let date = preferred || fallback || '';
    date = date.split('-')[0];

    return jQuery.isNumeric(date) ? date : '';
}
