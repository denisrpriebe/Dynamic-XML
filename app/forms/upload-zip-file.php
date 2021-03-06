<?php

use DynamicXML\Sessions\Session;
use DynamicXML\Utilities\UploadUtil;
use DynamicXML\Utilities\FileUtil;
use DynamicXML\Utilities\DirectoryUtil;

require_once "../../start.php";

$session = new Session();

$uploadUtil = new UploadUtil();
$uploadUtil->setAllowedTypes("zip");
$uploadUtil->setDirectory("../uploads");
$uploadUtil->setMaxFileSize("5000000"); // 3 MB
$uploadUtil->upload($_FILES["zip-file"]);

if ($uploadUtil->successful) {

    $directory = "../extracted/" . FileUtil::getBaseName($uploadUtil->uploadedFile);
    
    DirectoryUtil::createDirectory($directory);
    FileUtil::extractZipFile($uploadUtil->uploadedFile, $directory);
    DirectoryUtil::removeAllFilesInDirectoryExceptFor(array("xml", "XML"), $directory);

    $message = array(
        "type" => "success",
        "text" => "<strong>File Uploaded</strong><br/>Your zip file has been successfully uploaded."
    );
} else {
    $message = array(
        "type" => "danger",
        "text" => "<strong>Upload Failed</strong><br/>" . $uploadUtil->errors[0]
    );
}

$session->register("message", $message);
header("Location: ../../public/home.php");
