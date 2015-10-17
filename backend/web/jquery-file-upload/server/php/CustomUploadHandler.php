<?php
use backend\modules\pigeon_image\models\Image;
use backend\helpers\ExtraFunctions;

error_reporting(E_ALL | E_STRICT);
require(Yii::getAlias('@webroot').'/jquery-file-upload/server/php/UploadHandler.php');

class CustomUploadHandler extends UploadHandler 
{
    function __construct($options = null, $initialize = true, $error_messages = null) 
	{
		parent::__construct($options, $initialize, $error_messages);
	}
   /* protected function initialize() {
        $this->db = new mysqli(
            $this->options['db_host'],
            $this->options['db_user'],
            $this->options['db_pass'],
            $this->options['db_name']
        );
        parent::initialize();
       // $this->db->close();
    }*/

   /* protected function handle_form_data($file, $index) {
        $file->title = @$_REQUEST['title'][$index];
        $file->description = @$_REQUEST['description'][$index];
    }*/

    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error,  $index = null, $content_range = null) 
	{
        $file = parent::handle_file_upload($uploaded_file, $name, $size, $type, $error, $index, $content_range);
        if (empty($file->error)) 
		{
			$Image = new Image;
			$Image->IDuser = Yii::$app->user->getId();
            $Image->image_file = $file->name;
            $Image->IDalbum = $this->options["IDalbum"];
            $Image->date_created = ExtraFunctions::currentTime("ymd-his");
			$Image->save();
           
        }
        return $file;
    }

   /** protected function set_additional_file_properties($file) {
        parent::set_additional_file_properties($file);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $sql = 'SELECT `id`, `type`, `title`, `description` FROM `'
                .$this->options['db_table'].'` WHERE `name`=?';
            $query = $this->db->prepare($sql);
            $query->bind_param('s', $file->name);
            $query->execute();
            $query->bind_result(
                $id,
                $type,
                $title,
                $description
            );
            while ($query->fetch()) {
                $file->id = $id;
                $file->type = $type;
                $file->title = $title;
                $file->description = $description;
            }
        }
    }*/

   /* public function delete($print_response = true) {
        $response = parent::delete(false);
        foreach ($response as $name => $deleted) {
            if ($deleted) {
                $sql = 'DELETE FROM `'
                    .$this->options['db_table'].'` WHERE `name`=?';
                $query = $this->db->prepare($sql);
                $query->bind_param('s', $name);
                $query->execute();
            }
        } 
        return $this->generate_response($response, $print_response);
    }*/

}
