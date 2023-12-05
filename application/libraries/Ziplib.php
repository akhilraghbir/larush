<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ziplib {
    public function zipFilesAndDownload($file_names,$zipfilename){
        $file_path = FCPATH;
        if(!empty($file_names) && count($file_names) > 0){
            $zip = new ZipArchive();
            $archive_file_name = $zipfilename.".zip";
            if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE  )!==TRUE) {
                $_SESSION['message'] = "Cannot open <$archive_file_name>\n";
                header('Location:'.$_SERVER['HTTP_REFERER']);
                exit(0);
            }
            if(!empty($file_names)){
                for($i=0;$i<count($file_names);$i++){
                    $filename = $file_names[$i];
                    if(file_exists($file_path.$filename)){
                        $fselected[] = $filename;
                        $zip->addFile($file_path.$filename,$filename);
                    }
                }
            }
        } else {
            $archive_file_name = $file_path.$file_names[0];
        }
        $zip->close();
        $file_name = basename($archive_file_name);
        header("Content-Type: application/zip");
        header("Content-Disposition: attachment; filename=$file_name");
        header("Content-Length: " . filesize($archive_file_name));
        ob_end_clean();
        flush();
        readfile($archive_file_name);
        unlink($archive_file_name);
        exit;
    }
}
?>