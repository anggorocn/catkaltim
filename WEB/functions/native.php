<?
function ibase_blob_add($blob_handle, $data) {}
/**
 *  This function will discard a BLOB if it has not yet been closed by
 * ibase_blob_close().
 * Parameters
 * blob_handle
 * A BLOB handle opened with ibase_blob_create().
 * 
 * Return Values  Returns TRUE on success or FALSE on failure.
 * See Also   ibase_blob_close() - Close blob ibase_blob_create() - Create a
 * new blob for adding data ibase_blob_import() - Create blob, copy file in
 * it, and close it  
 */
function ibase_blob_cancel($blob_handle) {}
/**
 *  This function closes a BLOB that has either been opened for reading by
 * ibase_blob_open() or has been opened for writing by ibase_blob_create().
 * Parameters
 * blob_handle
 * A BLOB handle opened with ibase_blob_create() or ibase_blob_open().
 * 
 * Return Values  If the BLOB was being read, this function returns TRUE on
 * success, if the BLOB was being written to, this function returns a string
 * containing the BLOB id that has been assigned to it by the database. On
 * failure, this function returns FALSE.
 * See Also   ibase_blob_cancel() - Cancel creating blob ibase_blob_open() -
 * Open blob for retrieving data parts  
 */
function ibase_blob_close($blob_handle) {}
/**
 *  ibase_blob_create() creates a new BLOB for filling with data.
 * Parameters
 * link_identifier
 * An InterBase link identifier. If omitted, the last opened link is assumed.
 * 
 * Return Values  Returns a BLOB handle for later use with ibase_blob_add()
 * or FALSE on failure.
 * See Also   ibase_blob_add() - Add data into a newly created blob
 * ibase_blob_cancel() - Cancel creating blob ibase_blob_close() - Close blob
 * ibase_blob_import() - Create blob, copy file in it, and close it  
 */
function ibase_blob_create($link_identifier) {}
/**
 *  This function opens a BLOB for reading and sends its contents directly to
 * standard output (the browser, in most cases).
 * Parameters
 * link_identifier
 * An InterBase link identifier. If omitted, the last opened link is assumed.
 *  blob_id
 * 
 *      Return Values  Returns TRUE on success or FALSE on failure.
 * See Also   ibase_blob_open() - Open blob for retrieving data parts
 * ibase_blob_close() - Close blob ibase_blob_get() - Get len bytes data from
 * open blob  
 */
function ibase_blob_echo($blob_id) {}
/**
 *  This function returns at most len bytes from a BLOB that has been opened
 * for reading by ibase_blob_open().  Note:  It is not possible to read from
 * a
 * BLOB that has been opened for writing by ibase_blob_create().
 *  Parameters
 * blob_handle
 * A BLOB handle opened with ibase_blob_open().
 *  len
 * Size of returned data.
 * 
 * Return Values  Returns at most len bytes from the BLOB, or FALSE on
 * failure.
 * Examples   Example #1 ibase_blob_get() example
 *    Whilst this example doesn&#039;t do much more than a
 * &#039;ibase_blob_echo($data->BLOB_VALUE)&#039; would do, it does show you
 * how to get information into a $variable to manipulate as you please.
 * See Also   ibase_blob_open() - Open blob for retrieving data parts
 * ibase_blob_close() - Close blob ibase_blob_echo() - Output blob contents
 * to
 * browser  
 */
function ibase_blob_get($blob_handle, $len) {}
/**
 *  This function creates a BLOB, reads an entire file into it, closes it and
 * returns the assigned BLOB id.
 * Parameters
 * link_identifier
 * An InterBase link identifier. If omitted, the last opened link is assumed.
 *  file_handle
 * The file handle is a handle returned by fopen().
 * 
 * Return Values  Returns the BLOB id on success, or FALSE on error.
 * Examples   Example #1 ibase_blob_import() example   
 */
function ibase_blob_import($link_identifier, $file_handle) {}
/**
 *  Returns the BLOB length and other useful information.
 * Parameters
 * link_identifier
 * An InterBase link identifier. If omitted, the last opened link is assumed.
 *  blob_id
 * A BLOB id.
 * 
 * Return Values  Returns an array containing information about a BLOB. The
 * information returned consists of the length of the BLOB, the number of
 * segments it contains, the size of the largest segment, and whether it is a
 * stream BLOB or a segmented BLOB. 
 */
function ibase_blob_info($link_identifier, $blob_id) {}
/**
 *  Opens an existing BLOB for reading.
 * Parameters
 * link_identifier
 * An InterBase link identifier. If omitted, the last opened link is assumed.
 *  blob_id
 * A BLOB id.
 * 
 * Return Values  Returns a BLOB handle for later use with ibase_blob_get()
 * or FALSE on failure.
 * See Also   ibase_blob_close() - Close blob ibase_blob_echo() - Output blob
 * contents to browser ibase_blob_get() - Get len bytes data from open blob  
 */
function ibase_blob_open($link_identifier, $blob_id) {}

function ibase_fetch_assoc($result, $fetch_flag) {}
?>