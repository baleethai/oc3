<?php
require_once(DIR_SYSTEM . 'library/cloudfront/aws/aws-autoloader.php');
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class ModelExtensionModuleCloudFront extends Model {
	public function exportImage($filename) {
        $access_key = $this->config->get('cloudfront_access_key');
        $secret_key = $this->config->get('cloudfront_secret_key');
        $bucket = $this->config->get('cloudfront_image_bucket');
        $location = $this->config->get('cloudfront_image_location');

		if ($this->config->get('cloudfront_status') && ($this->config->get('cloudfront_usage') == 1 || $this->config->get('cloudfront_usage') == 3) && $access_key && $secret_key && $bucket) {
            
            try {
                $s3 = S3Client::factory(array(
                    'credentials'   => array(
                        'key'    => $access_key,
                        'secret' => $secret_key
                    ),
                    'region'        => $location,
                    'version'       => 'latest',
                    'http'          => array(
                        'verify' => false
                    )
                ));
                
                $s3->putObject(array(
                    'Bucket'     => $bucket,
                    'Key'        => $filename,
                    'SourceFile' => DIR_IMAGE . $filename,
                    'ACL'        => 'public-read',
                    'http'          => array(
                        'verify' => false
                    )
                ));

            } catch(Exception $error) {
                $this->log->write($error->getMessage());
            }
        }
        
        return false;
	}
    
    public function getImageURL($filename) {
        $access_key = $this->config->get('cloudfront_access_key');
        $secret_key = $this->config->get('cloudfront_secret_key');
        $bucket = $this->config->get('cloudfront_image_bucket');

		if ($this->config->get('cloudfront_status') && ($this->config->get('cloudfront_usage') == 1 || $this->config->get('cloudfront_usage') == 3) && $access_key && $secret_key && $bucket && $this->config->get('cloudfront' . base64_decode('X2xpY2Vuc2VfbGljZW5zZV9rZXk='))) {
            return $this->config->get('cloudfront_url') . $filename;
        }
        
        return false;
	}
    
    public function exportDownload($filename) {
        $access_key = $this->config->get('cloudfront_access_key');
        $secret_key = $this->config->get('cloudfront_secret_key');
        $bucket = $this->config->get('cloudfront_download_bucket');
        $location = $this->config->get('cloudfront_download_location');

		if ($this->config->get('cloudfront_status') && $access_key && $secret_key && $bucket) {
            try {
                $s3 = S3Client::factory(array(
                    'credentials'   => array(
                        'key'    => $access_key,
                        'secret' => $secret_key
                    ),
                    'region'        => $location,
                    'version'       => 'latest',
                    'http'          => array(
                        'verify' => false
                    )
                ));
                
                $s3->putObject(array(
                    'Bucket'     => $bucket,
                    'Key'        => 'download/' . $filename,
                    'SourceFile' => DIR_DOWNLOAD . $filename,
                    'http'          => array(
                        'verify' => false
                    )
                ));
            } catch(Exception $error) {
                $this->log->write($error->getMessage());
            }
        }
        
        return false;
    }
}