<?php
namespace KpakpandoEventsBooking\File;

use Exception;

class File
{
    static $acceptedOrigins = [DEF_LOCAL_SERVER, DEF_LIVE_SERVER];
    static $arExtensions = ['jpg', 'jpeg', 'png'];
    static $maxSize = 2048576;
    static $directory = '';
    static $fieldId = '';
    static $fileExtension = '';

    public static function uploadFile($customFileName='')
    {
        $fileSize = $_FILES[self::$fieldId]['size'];
        $fileName = $_FILES[self::$fieldId]['name'];
        $tmpFileName = $_FILES[self::$fieldId]['tmp_name'];
        
        if ($fileSize > 0)
        {
            $arFileExt = explode('.', $fileName);
            $fileExt = strtolower(end($arFileExt));
    
            if (!in_array($fileExt, self::$arExtensions))
            {
                $extensions = implode(', ', self::$arExtensions);
                throw new Exception("Invalid extension! Only {$extensions} are allowed");
            }
            elseif ($fileSize > self::$maxSize)
            {
                throw new Exception('The file is too large');
            }
            else
            {
                self::$fileExtension = $fileExt;
                if ($customFileName != '')
                {
                    $newFileName = $customFileName.'.'.$fileExt;
                }
                else
                {
                    $newFileName = uniqid().'.'.$fileExt;
                }
                
                if (move_uploaded_file($tmpFileName, DEF_DOC_ROOT.self::$directory.$newFileName))
                {
                    return $newFileName;
                }
                else
                {
                    throw new Exception('An error occurred while uploading your file');
                }
            }
        }
    }
}