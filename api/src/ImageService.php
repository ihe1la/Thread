<?php
class ImageService {
    private $uploadsDir;
    private $security;
    
    public function __construct(Security $security) {
        $this->uploadsDir = __DIR__ . '/../uploads/';
        $this->security = $security;
    }
    
    public function createThumbnail($file) {
        $this->security->validateImageUpload($file);
        
        $source = $file['tmp_name'];
        $filename = uniqid() . '_thumb_' . basename($file['name']);
        $destination = $this->uploadsDir . $filename;
        
        list($width, $height) = getimagesize($source);
        $thumbWidth = 200;
        $thumbHeight = floor($height * ($thumbWidth / $width));
        
        $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
        
        switch($file['type']) {
            case 'image/jpeg':
                $source = imagecreatefromjpeg($source);
                break;
            case 'image/png':
                $source = imagecreatefrompng($source);
                break;
            case 'image/gif':
                $source = imagecreatefromgif($source);
                break;
            case 'image/svg+xml':
                // SVG doesn't need resizing
                move_uploaded_file($file['tmp_name'], $destination);
                return $filename;
            default:
                throw new Exception('Unsupported image type');
        }
        
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);
        
        switch($file['type']) {
            case 'image/jpeg':
                imagejpeg($thumb, $destination, 85);
                break;
            case 'image/png':
                imagepng($thumb, $destination, 8);
                break;
            case 'image/gif':
                imagegif($thumb, $destination);
                break;
        }
        
        return $filename;
    }
}