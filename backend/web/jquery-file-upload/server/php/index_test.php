<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

error_reporting(E_ALL | E_STRICT);
require('UploadHandler.php');
$options = array(
            'upload_dir' => 'test',        
            'accept_file_types' => '/\.(gif|jpe?g|png)$/i' ,
			'max_file_size'=>5000000, //5mb                  
           );
$upload_handler = new UploadHandler($options);
