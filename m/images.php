<?php

namespace M;

/**
 * Class Images - a model to work with images.
 */
class Images
{
    use \Core\Traits\Singleton;
	
	public function upload_file($file, $filename, $upload_dir = '..\images\blog', $allowed_types = ['image/png','image/x-png','image/jpeg','image/jpg','image/webp','image/gif'])
    {
        // A list of forbidden extensions.
        $blacklist = [".php", ".phtml", ".php3", ".php4"];
        // Get image extension.
        $ext = substr($filename, strrpos($filename,'.'), strlen($filename)-1);
        // Check forbidden extensions.
        if (in_array($ext, $blacklist)) {
            return ['error' => 'Запрещено загружать исполняемые файлы'];
        }
        // Determine directory for files uploading.
        $upload_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . $upload_dir . DIRECTORY_SEPARATOR;
        // Determine maximal file size.
        $max_filesize = 8388608;
        // Generate unique file name.
        $new_filename = uniqid() . $ext;
        // Check upload directory is writable.
        if (!is_writable($upload_dir)) {
            return ['error' => 'Невозможно загрузить файл в папку "'.$upload_dir.'". Установите права доступа - 777.'];
        }
        // Check file type.
        elseif (!in_array($file['type'], $allowed_types)) {
            return ['error' => 'Данный тип файла не поддерживается.'];
        }
        // Check file size.
        elseif (filesize($file['tmp_name']) > $max_filesize) {
            return ['error' => 'файл слишком большой. максимальный размер ' . intval($max_filesize/(1024*1024)).'мб'];
        }
        // Upload file and check uploading errors.
        elseif (!move_uploaded_file($file['tmp_name'], $upload_dir . $new_filename)) {
            return ['error' => 'При загрузке возникли ошибки. Попробуйте ещё раз.'];
        }

        return 'img src="/images/blog/' . $new_filename;
	}
}

