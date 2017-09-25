<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace M;

/**
 * Description of images
 *
 * @author admin
 */
class Images {
    use \Core\Traits\Singleton;
	
	public function upload_file($file, $filename, $upload_dir = '..\images\blog', $allowed_types = ['image/png','image/x-png','image/jpeg','image/jpg','image/webp','image/gif'])
        {
            $blacklist = [".php", ".phtml", ".php3", ".php4"];

            $ext = substr($filename, strrpos($filename,'.'), strlen($filename)-1); // В переменную $ext заносим расширение загруженного файла.

            if(in_array($ext, $blacklist)){
                return ['error' => 'Запрещено загружать исполняемые файлы'];
            }

            $upload_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . $upload_dir . DIRECTORY_SEPARATOR; // Место, куда будут загружаться файлы.
            $max_filesize = 8388608; // Максимальный размер загружаемого файла в байтах (в данном случае он равен 8 Мб).
            $new_filename = uniqid() . $ext; // В переменную $filename заносим точное имя файла.

            if(!is_writable($upload_dir)){ // Проверяем, доступна ли на запись папка, определенная нами под загрузку файлов.
                return ['error' => 'Невозможно загрузить файл в папку "'.$upload_dir.'". Установите права доступа - 777.'];
            }
            elseif(!in_array($file['type'], $allowed_types)){
                return ['error' => 'Данный тип файла не поддерживается.'];
            }
            elseif(filesize($file['tmp_name']) > $max_filesize){
                return ['error' => 'файл слишком большой. максимальный размер ' . intval($max_filesize/(1024*1024)).'мб'];
            }
            elseif(!move_uploaded_file($file['tmp_name'], $upload_dir . $new_filename)){// Загружаем файл в указанную папку.
                return ['error' => 'При загрузке возникли ошибки. Попробуйте ещё раз.'];
            }
                
            return 'img src="/images/blog/' . $new_filename . '" style="display: block; max-width: 100%; margin: 0 auto;"';
	}
}