<?php

namespace app\models\Functions;

use app\models\NormalLog;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use Yii;

class UploadFile2 extends Model
{
    use \app\models\Traits\Methods\ExecutionLimit;

    /**
     * @var UploadedFile
     */
    public $UpFile;
    public $UpPic;
    public $MultiTypeUpFile;
    public $AllTypeUpFile;

    public $id;
    public $path;
    public $filename;
    public $filepath;

    /**
     * 是否增加 亂數編碼
     * (避免檔案重複覆蓋)
     */
    const IS_ENCODE_FILE_NAME = true;

    public function rules()
    {
        return [
            [['UpFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf'],  #額外附件..等
            [['UpPic'], 'file', 'skipOnEmpty' => true, 'extensions' => ['jpg', 'png', 'jpeg']],  #設備圖片..等
            [['MultiTypeUpFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf, xls, xlsx, doc, docx'],  #清冊附件..等
            [['AllTypeUpFile'], 'file', 'skipOnEmpty' => true],  #ZIP檔案解壓上傳..等
        ];
        # 'maxFiles' => 5
    }

    /**
     * 檔案上傳
     * @param string $file_type
     * @return bool
     */
    public function upload($file_type = 'UpFile')
    {
        if ($this->validate()) {
            if ($this->filepath) {
                if (self::IS_ENCODE_FILE_NAME) {
                    $tempBaseName = pathinfo($this->filepath, PATHINFO_BASENAME);
                    $this->filepath = pathinfo($this->filepath, PATHINFO_DIRNAME) . '/' .
                        $this->getEncodeString('half1', $tempBaseName);
                }

                $file_path = $this->filepath;
            } else {
                if (self::IS_ENCODE_FILE_NAME) {
                    $this->filename = $this->getEncodeString('half1', $this->filename);
                }

                $file_path = $this->path . '/' . $this->filename;
            }

            # 以 FileSystem 方式上傳
            return $this->uploadToFileSystem($file_type, $file_path);
        } else {
            return false;
        }
    }

    /**
     * @param $file_type
     * @param $file_path
     * @return bool
     */
    protected function saveFile($file_type, $file_path)
    {
        if (!$this->hasProperty($file_type)) {
            return false;
        }

        try {
            if (mb_detect_encoding($this->filename) == 'ASCII') {
                $this->$file_type->saveAs($file_path);
            } else {
                //可能因中文檔名等 會有編碼問題 自動轉成亂數名稱
                $this->filename = md5(uniqid(rand())) . '.' . pathinfo($this->filename, PATHINFO_EXTENSION);
                $file_path = $this->path . '/' . $this->filename;
                $this->$file_type->saveAs($file_path);
            }
        } catch (\Exception $e) {
            NormalLog::recordErrorLog('檔案上傳異常', $e);
            return false;
        } catch (\Error $e) {
            NormalLog::recordErrorLog('檔案上傳異常', $e);
            return false;
        }

        return true;
    }

    /**
     * 上傳圖片旋轉處理 (因為IMAGE EXIF 數位相機、手機...等拍攝會有)
     * @param $path
     * @return bool
     */
    protected function imageFixOrientation($path)
    {
        $this->setExecutionLimit('30', '1024M');

        $image = imagecreatefromjpeg($path);
        if (!$image) {
            return false;
        }
        $exif = exif_read_data($path);
        if (!$exif) {
            return false;
        }

        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $image = imagerotate($image, 180, 0);
                    imagejpeg($image, $path);
                    break;
                case 6:
                    $image = imagerotate($image, -90, 0);
                    imagejpeg($image, $path);
                    break;
                case 8:
                    $image = imagerotate($image, 90, 0);
                    imagejpeg($image, $path);
                    break;
            }
        }

        //釋放記憶體
        if ($image) {
            imagedestroy($image);
        }

        return true;
    }

    /**
     * 檔案上傳 (上傳至FileSystem， e.g. S3...)
     * @param $file_type
     * @param $file_path
     * @return bool
     */
    protected function uploadToFileSystem($file_type, $file_path)
    {
        if (!is_resource($this->$file_type)) {
            $stream = fopen($this->$file_type->tempName, 'r+');
        } else {
            $stream = $this->$file_type;
        }

        if (Yii::$app->fileSystem->putStream($file_path, $stream)) {
            return pathinfo($file_path, PATHINFO_BASENAME);
        } else {
            return false;
        }
    }

    /**
     * 取得檔案編碼 baseName
     * @param $mode
     * @param $fileBaseName
     * @return string
     */
    protected function getEncodeString($mode, $fileBaseName)
    {
        switch ($mode) {
            # 一半編碼 第一種
            case 'half1':
                return date('Yds') . substr(uniqid(), 0, 7) . '_' . $fileBaseName;
                break;
        }

        return $fileBaseName;
    }
}
