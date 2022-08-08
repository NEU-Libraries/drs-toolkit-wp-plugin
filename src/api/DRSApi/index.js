/**
 * @module DRSApi
 *
 * This file contains methods to do file and search requests to the DRS Server
 * There are 2 APIs right now
 * 1. File - Gets a single file
 * 2. Search - Gets a collection of files or sub-collection with the given collectionId
 */

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
		let fileUrl; // stores the url of the file from the search
		let err; // error

		const baseUrl = "https://repository.library.northeastern.edu/api/v1/files/";
		const response = await axios.get(baseUrl + fileId);
		const { data } = response;

		const dataFormat = data.mods.Format[0].toLowerCase();

		if (
			allowedTypes.includes(dataFormat) ||
			(allowedTypes.includes("audio") && dataFormat === "sound recording")
		) {
			if (dataFormat === "image") {
				// Object.entries() returns an array of enumerable string-keyed property [key, value] pairs
				const thumbnails = Object.keys(data.thumbnails);
				fileUrl = thumbnails[thumbnails.length - 1];
			} else {
				// For everything else use cannonical object or master file
				// Object.keys(obj) – returns an array of keys
				fileUrl = Object.keys(data.canonical_object)[0];
			}
		} else {
			console.log("Data format not included in the allowedTypes", dataFormat);
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

		// short circuiting (&&)
		// object.entries returns array using key,value pairs from the object passed in
		// check below for related links
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

/**
 * For Short circuiting
 * @see https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Logical_AND
 *
 * Object.entries returns key,value pairs from an object in array format, thus we can iterate using the following format
 * either map or foreach can be used
 * map returns a new array where as for each does not
 *
 * @see https://www.freecodecamp.org/news/4-main-differences-between-foreach-and-map/
 */
