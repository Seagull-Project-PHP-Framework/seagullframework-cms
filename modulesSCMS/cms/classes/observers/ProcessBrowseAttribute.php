<?php

/**
 * @package    seagull
 * @subpackage cms
 * @author     Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class ProcessBrowseAttribute extends SGL_Observer
{
    var $error = false;

    function update($observable)
    {
        $lastId = $observable->oContent->id;

        // only allow product content types for now
        $oContent = SGL_Content::getById($lastId);

        // no file attributes found
        if (empty($_FILES)) {
            return true;
        }
        // it's edit
        //$editMode = !empty($observable->input->contentId);

        $aAttribIds = $observable->input->content->attributes['attr_id'];
        foreach ($oContent->aAttribs as $oAttribute) {
            if ($oAttribute->typeId != SGL_CONTENT_ATTR_TYPE_FILE) {
                continue;
            }
            $attribId = $oAttribute->id;
            $attribOrder = array_search($attribId, $aAttribIds);

            // if not found smth went wrong
            if ($attribOrder === false) {
                continue;
            }

            $fileSize    = $_FILES['content']['size']['attributes']['data'][$attribOrder];
            $fileTmpName = $_FILES['content']['tmp_name']['attributes']['data'][$attribOrder];
            $fileName    = $_FILES['content']['name']['attributes']['data'][$attribOrder];
            $fileType    = $_FILES['content']['type']['attributes']['data'][$attribOrder];
            $mediaId     = $this->_uploadImage($fileSize, $fileTmpName,
                $fileName, $fileType);

            if ($mediaId === false) {
                $this->error = true;
                continue;
            }

            // if new media was uploaded and in edit mode
            /*
            if ($editMode && $oAttribute->value) {
                require_once SGL_MOD_DIR . '/media/classes/MediaDAO.php';
                $daMedia = &MediaDAO::singleton();
                $ok = $daMedia->deleteMediaById($oAttribute->value);
                if (PEAR::isError($ok)) {
                    return $ok;
                }
            }
            */

            $attrName = $oAttribute->name;
            $oContent->$attrName = $mediaId;
        }
        $ok = $oContent->save();
        if (PEAR::isError($ok)) {
            return $ok;
        }
        if ($this->error) {
            return;
        }
    }

    function _uploadImage($fileSize, $fileTmpName, $fileName, $fileType)
    {
        if (empty($fileSize)) {
            return false;
        }
        require_once SGL_MOD_DIR . '/media/classes/MediaMgr.php';

        $mediaMgr = new MediaMgr();
        // wrong mime type
        if (false === ($mimeType = $mediaMgr->getMimeType($fileTmpName))) {
            return false;
        }
        $tmpInput                   = new stdClass();
        $tmpInput->submitted        = true;
        $tmpInput->mediaFileType    = $mimeType;
        $tmpInput->mediaFileName    = $mediaMgr->toValidFileName($fileName, $mimeType);
        $tmpInput->mediaFileTmpName = $fileTmpName;
        $ok = $mediaMgr->_cmd_add($tmpInput, $tmpOutput = new stdClass());

        if (isset($ok) && $ok === false) {
            return $ok;
        }

        $tmpInput->media = new stdClass();
        $tmpInput->media->file_type_id = $tmpOutput->fileTypeID;
        $tmpInput->media->orig_name    = $tmpInput->mediaFileName;
        $tmpInput->media->mime_type    = $mimeType;
        $tmpInput->media->file_size    = $fileSize;
        $tmpInput->media->file_name    = $tmpOutput->mediaUniqueName;
        $tmpInput->media->name         = $tmpInput->mediaFileName;
        $tmpInput->media->description  = 'Auto-uploaded by ProcessBrowseAttribute observer';
        $mediaId = $mediaMgr->_cmd_insert($tmpInput, $tmpOutput);

        return $mediaId;
    }
}
?>