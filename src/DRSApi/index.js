import axios from "axios";
/**
 * Parameters passed to fetch from File
 * @typedef  {Object} FetchFileParams
 * @property {Array<string>} allowedTypes   - Allowed types for the file returned
 * @property {string} fileId                - Id of the file to be retrieved
 */
/**
 * Return type for fetch from File
 * @typedef  {Object} FetchFileResponse
 * @property {String | undefined} fileUrl   - Url of the File
 * @property {String} fileId                - DRS Id of the File
 * @property {String} dataFormat            - Format of the File
 * @property {String | undefined} err		- If error is present
 * @property {Object} mods                  - Details of the file
 */

/**
 * Fetches the file urls from the fileId passed
 * @param {FetchFileParams} params          - Data to be passed
 * @returns {Promise<FetchFileResponse>}    - Promise object
 */
export async function fetchFromFile({ fileId, allowedTypes }) {
	try {
		let fileUrl;
		let err;
		const baseUrl = "https://repository.library.northeastern.edu/api/v1/files/";
		const response = await axios.get(baseUrl + fileId);
		const { data } = response;

		const dataFormat = data.mods.Format[0].toLowerCase();

		if (
			allowedTypes.includes(dataFormat) ||
			(allowedTypes.includes("audio") && dataFormat === "sound recording")
		) {
			if (dataFormat === "image") {
				// master image cannot be used, since the size is way too big!!!
				Object.entries(data.content_objects).forEach(([key, value]) => {
					// using large image
					if (value.includes("Large")) {
						fileUrl = key;
					}
				});
			} else {
				// For everything else use cannonical object or master file
				fileUrl = Object.keys(data.canonical_object)[0];
			}
		} else {
			console.log(dataFormat);
			console.log(allowedTypes);
			console.log("Data format not included in the allowedTypes");
			err = "not in allowedTypes";
		}

		return { fileUrl, fileId, dataFormat, mods: data.mods, err };
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

		/**
		 *
		 * searchParams {
		 * 	key1:val1
		 * key2:val2
		 *
		 * Object.entries
		 * [
		 * {key1,val1},{key2,val2}]
		 * }
		 */

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
