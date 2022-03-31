<?php

include './uploads/UploadException.php';

class Upload {

    // the file directory
    const IMAGE_DIR = './images/';
    const IMAGE_URL = 'http://localhost/api_rest/images/';
    const FILE_DIR = 'http://localhost/api_rest/files/';

    // extract the upload file from $_FILE
    public static function _extract_file($fileType) {
        // let's check user upload file or not
        if (!isset($_FILES[$fileType])):
            throw new Exception("No file was uploaded", 204);
        elseif ($_FILES[$fileType]['error'] !== UPLOAD_ERR_OK):
            throw new UploadException($_FILES['file']['error']);
        endif;
        // let's get user update file name
        $fileName = $_FILES[$fileType]['name'];
        // let's get the tmp_name to save/move file in our folder
        $tmpName = $_FILES[$fileType]['tmp_name'];
        // let's get file extention
        $array = explode('.', $fileName);
        $fileExt = end($array);
        self::_set_file($fileName, $fileExt, $tmpName);
    }

    // move the file object to our `FILE_DIR` folder
    public static function _upload_file() {
        // let's check if file was upload
        if (!isset(self::$_file)):
            throw new Exception("No file was uploaded", 204);
        endif;
        if(!move_uploaded_file(self::$_file->tmp_name, self::IMAGE_DIR.self::$_file->name)):
            throw new Exception('Failed to write file to disk', 417);
        endif;
    }

    // delete file
    public static function _delete_file() {
        // let's check file exists or not
        if (isset(self::$_file) && self::$_file->name != 'cover' && file_exists(self::IMAGE_DIR.self::$_file->name)):
            // let's delete the file
            unlink(self::IMAGE_DIR.self::$_file->name);
        endif;
    }

    public static function _get_file() {
        return self::$_file;
    }

    // set the file name
    public static function _set_file($name, $ext=null, $tmp=null) {
        if (!isset(self::$_file)) self::$_file = new stdClass();
        self::$_file->name = $name;
        self::$_file->tmp_name = isset(self::$_file->tmp_name) && !isset($tmp) ? self::$_file->tmp_name : $tmp;
        self::$_file->ext = isset(self::$_file->ext) && !isset($ext) ? self::$_file->ext : $ext;;
    }

    // the file object
    private static $_file = null;

}



