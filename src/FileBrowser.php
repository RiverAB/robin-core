<?php namespace RobinCms\Core;

use DirectoryIterator;

class FileBrowser
{
    protected $path;
    protected $publicPath;
    protected $files;

    /**
     * New instance
     * 
     * @param string $path       Absolute path to the upload folder
     * @param string $publicPath Relative path to the upload folder from public
     */
    public function __construct($path, $publicPath)
    {
        $this->path       = realpath($path);
        $this->publicPath = rtrim($publicPath, "\//");
    }

    
    /**
     * Get a list of all uploaded files
     * 
     * @return array
     */
    public function getFiles()
    {
        if (is_null($this->files)) {
            
            $this->files = [];

            foreach(new DirectoryIterator($this->path) as $file) {

                if ($file->isDot() || $file->isDir() || strpos($file->getFilename(), '.') === 0) {
                    continue;
                }

                $type = get_file_type($file->getFilename());
                if ($type == 'image') {
                    list($width, $height) = getimagesize($file->getPathname());
                }

                $this->files[] = [
                    'filename'   => $file->getFilename(),
                    'type'       => $type,
                    'path'       => $file->getPathname(),
                    'size'       => $file->getSize(),
                    'human_size' => human_filesize($file->getSize()),
                    'uri'        => str_replace($this->publicPath, '', $file->getPathname()),
                    'created'    => $file->getCTime(),
                    'date'       => date('Y-m-d', $file->getCTime()),
                    'time'       => date('H:i', $file->getCTime()),
                    'extension'  => strtolower($file->getExtension()),
                    'width'      => empty($width)? 0 : $width,
                    'height'     => empty($height)? 0 : $height,
                ];
            }

            usort($this->files, function($a, $b) {
                return $a['created'] < $b['created'];
            });

        }

        return $this->files;
    }


    /**
     * Save an uploaded file
     * 
     * @param  array  $file  The $_FILES[name] super global
     * @return string|null  Error message or null on success
     */
    public function uploadFile(array $file)
    {
        if (arr_val($file, 'error') !== UPLOAD_ERR_OK) {
            return "An error occurred. Upload error: " . $file['error'];
        }

        $tmp_name = arr_val($file, 'tmp_name');
        $name     = $this->cleanFilename(arr_val($file, 'name'));
        $path     = rtrim($this->path, '/') . '/' . $name;
        
        if (strpos($name, '.') === 0) {
            return "Invalid filename. A file must have a filename, not just an extension.";
        }

        if (!@move_uploaded_file($tmp_name, $path)) {
            if (!is_writable($this->path)) {
                $error = "Upload folder is not writeable";
            } else if (is_file($path) && !is_writable($path)) {
                $error = "A file with the name " . $name . " already exists and isn't writeable.";
            } else {
                $error = "An unspecified error occurred for the file " . $name;
            }
            return $error;
        }

        return null;
    }


    /**
     * Delete an uploaded file
     * 
     * @param  string $file Filename
     * @return string|null  Error message or null on success
     */
    public function deleteFile($file)
    {
        $path     = realpath(rtrim($this->path, '/') . '/' . $file);

        if (strpos($file, '.') === 0 || strpos($file, '/') !== false) {
            return "Invalid filename";
        }

        if (strpos($path, $this->path) !== 0) {
            return "Invalid path";
        }

        if (!is_file($path)) {
            return "The file is not found";
        }

        if (!@unlink($path)) {
            return "Could not delete the file. Check file permissions.";
        }

        return null;
    }


    /**
     * Parse and sanitize the filename
     * 
     * @param  string $filename
     * @return string
     */
    public function cleanFilename($filename)
    {
        $filename = str_replace(' ', '_', $filename);
        $filename = mb_ereg_replace("([^\w\s\d\-_~,;:\[\]\(\).])", '', $filename);
        $filename = mb_ereg_replace("([\.]{2,})", '', $filename);
        return $filename;
    }
}