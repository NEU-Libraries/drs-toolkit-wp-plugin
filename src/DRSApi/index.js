import axios from "axios";
/**
 * Parameters passed to fetch from File
 * @typedef  {Object} FetchFileParams
 * @property {string} format                - Type expected by block
 * @property {String|null} fileFormat       - Type in the data (Passed from Search)
 * @property {string} fileId                - Id of the file to be retrieved
 */
/**
 * Return type for fetch from File
 * @typedef  {Object} FetchFileResponse
 * @property {String} fileUrl               - Url of the File
 * @property {String} fileId                - DRS Id of the File
 * @property {String} Format                - Format of the File
 * @property {Object} mods                  - Details of the file
 */

/**
 * Fetches the file urls from the fileId passed
 * @param {FetchFileParams} params          - Data to be passed
 * @returns {Promise<FetchFileResponse>}    - Promise object
 */
export async function fetchFromFile({ format, fileId, fileFormat = null }) {
	try {
		let fileUrl = "";
		const baseUrl = "https://repository.library.northeastern.edu/api/v1/files/";
		const response = await axios.get(baseUrl + fileId);
		const { data } = response;

		if (format == "Image") {
			if (fileFormat == null || fileFormat == "Image")
				Object.entries(data.content_objects).forEach(([key, value]) => {
					if (value.includes("Large")) {
						fileUrl = key;
					}
				});
			else {
				const lenThumbnails = data.thumbnails.length;
				fileUrl = data.thumbnails[lenThumbnails - 1];
			}
		} else {
			fileUrl = Object.keys(data.canonical_object)[0];
		}

		return { fileUrl, fileId, format, mods: data.mods };
	} catch (error) {
		console.log(error);
	}
}

/**
 * Parameter passed to fetch from search
 * @typedef  {Object} FetchSearchParams
 * @property {string} collectionId                  - Collection/Set Id
 * @property {Object} searchParams					- Parameters to modify search
 */

/**
 * Response recived from Fetch from Search
 * @typedef {Object} FetchSearchResponse
 */

/**
 * Fetch from search from the collection Id
 * @param   {FetchSearchParam} params               - Data to be passed
 * @returns {Promise<FetchSearchResponse>}          - Promise object
 */
export async function fetchFromSearch({ collectionId, searchParams }) {
	try {
		let baseUrl =
			"https://repository.library.northeastern.edu/api/v1/search/" +
			collectionId +
			"?";

		searchParams &&
			Object.entries(searchParams).map(([key, value]) => {
				baseUrl += key + "=" + value + "?";
			});

		const response = await axios.get(baseUrl);
		const { data } = response;
		return data;
	} catch (error) {
		console.log(error);
	}
}
