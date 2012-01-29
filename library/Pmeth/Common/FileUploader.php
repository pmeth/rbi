<?php
namespace Pmeth\Common;
/**
 * Description of FileUploader
 * 
 * Based on tutorial here: http://www.devshed.com/c/a/PHP/Developing-a-Modular-Class-For-a-PHP-File-Uploader
 *
 * @author Peter Meth
 */

class FileUploader {

// define 'FileUploader' class

	protected $uploadFile;
	protected $name;
	protected $tmp_name;
	protected $type;
	protected $extension;
	protected $size;
	protected $error;
	protected $allowedTypes = array(
		 'application/octet-stream',
		 'text/plain',
	);
	protected $allowedExtensions = array(
		 'rom',
		 'nes',
		 'csv',
	);

	public function __construct($uploadDir='../uploads/') {
		if (!is_dir($uploadDir)) {
			throw new Exception('Invalid upload directory.');
		}
		if (!count($_FILES)) {
			throw new Exception('Invalid number of file upload parameters.');
		}
		foreach ($_FILES['userfile'] as $key => $value) {
			$this->{$key} = $value;
		}
		if (!in_array($this->type, $this->allowedTypes)) {
			throw new Exception('Invalid MIME type of target file. Tried to upload ' . $this->type);
		}

		$path_parts = pathinfo($this->name);
		$this->extension = isset($path_parts['extension']) ? $path_parts['extension'] : '';
		if (!in_array($this->extension, $this->allowedExtensions)) {
			throw new Exception('Invalid File Extension. Tried to upload ' . $this->extension);
		}
		
		$this->uploadFile = $uploadDir . basename($this->name);
	}

// upload target file to specified location in the web server
	public function upload() {
		if (move_uploaded_file($this->tmp_name, $this->uploadFile)) {
			return true;
		}
// throw exception according to error number
		switch ($this->error) {
			case 1:
				throw new Exception('Target file exceeds maximum allowed size.');
				break;
			case 2:
				throw new Exception('Target file exceeds the MAX_FILE_SIZE value specified on the upload form.');
				break;
			case 3:
				throw new Exception('Target file was not uploaded completely.');
				break;
			case 4:
				throw new Exception('No target file was uploaded.');
				break;
			case 6:
				throw new Exception('Missing a temporary folder.');
				break;
			case 7:
				throw new Exception('Failed to write target file to disk.');
				break;
			case 8:
				throw new Exception('File upload stopped by extension.');
				break;
		}
	}

	public function getUploadFile() {
		return $this->uploadFile;
	}

	public function getName() {
		return $this->name;
	}

	public function getTmp_name() {
		return $this->tmp_name;
	}

	public function getType() {
		return $this->type;
	}

	public function getExtension() {
		return $this->extension;
	}

	public function getSize() {
		return $this->size;
	}

	public function getError() {
		return $this->error;
	}

	public function getAllowedTypes() {
		return $this->allowedTypes;
	}

	public function getAllowedExtensions() {
		return $this->allowedExtensions;
	}


}

