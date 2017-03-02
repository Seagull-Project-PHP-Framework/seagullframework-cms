<?php

require_once dirname(__FILE__) . '/Media2DAO.php';
require_once SGL_MOD_DIR . '/media2/lib/Media/Util.php';
require_once 'Pager.php';

/**
 * Media manager.
 *
 * @package media2
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class Media2Mgr extends SGL_Manager
{
    public function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Manager();

        $this->pageTitle      = 'Media Manager';
        $this->masterTemplate = 'master.html';
        $this->template       = 'media2List.html';

        $this->_aActionsMapping = array(
            'list'     => array('list'),
            'upload'   => array('upload'),
            'edit'     => array('edit'),
            'download' => array('download'),
            'preview'  => array('preview'),

            'listSimple'   => array('list', 'listSimple'),
            'uploadSimple' => array('upload', 'uploadSimple')
        );

        $this->da = Media2DAO::singleton();
    }

    public function validate(SGL_Request $req, SGL_Registry $input)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $this->validated       = true;
        $input->pageTitle      = $this->pageTitle;
        $input->template       = $this->template;
        $input->masterTemplate = $this->masterTemplate;
        $input->action         = $req->get('action')
            ? $req->get('action') : 'list';

        $input->mediaId = $req->get('mediaId');
        $input->thumb   = $req->get('thumb');

        $input->redir = $req->get('redir');
        $input->page  = $req->get('page');

        $input->mimeTypeId  = $req->get('mimeTypeId');
        $input->mediaTypeId = $req->get('mediaTypeId');
    }

    public function display(SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
    }

    public function _cmd_list(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        if ($input->mediaTypeId == 'all') {
            $input->mediaTypeId = null;
        }
        if ($input->mimeTypeId == 'all') {
            $input->mimeTypeId = null;
        }

        $count   = $this->da->getMediaCount($input->mediaTypeId, $input->mimeTypeId);
        $url     = $input->getCurrentUrl()->makeCurrentLink(
            array('page' => 'page-id'));
        $perPage = $input->action == 'listSimple' ? 12 : 16;

        // page options
        $options = array(
            'totalItems'            => $count,
            'currentPage'           => $input->page,
            'mode'                  => 'Sliding',
            'perPage'               => $perPage, // make it configurable
            'delta'                 => 3,
            'fileName'              => str_replace('page-id', '%d', $url),
            'path'                  => '',

            // taken from finder
            'curPageSpanPre'        => '<span>',
            'curPageSpanPost'       => '</span>',
            'spacesBeforeSeparator' => 1,
            'spacesAfterSeparator'  => 1,
            'append'                => false
        );
        $oPager = Pager::factory($options);

        // get offsets and limits needed for DAO
        list($from, $to) = $oPager->getOffsetByPageId();
        $limit  = $to - $from + 1;
        $offset = $from - 1;

        $aMedias = $this->da->getMedias($input->mediaTypeId, $input->mimeTypeId,
            null, $limit, $offset);

        $aMimeTypes  = $this->da->getMimeTypeInfoList();
        $aMediaTypes = $this->da->getMediaTypeInfoList();

        // fix 'path=""' bug
        $output->pagerLinks  = str_replace('="/', '="', $oPager->links);
        $output->aMedias     = $aMedias;
        $output->aMimeTypes  = $aMimeTypes;
        $output->aMediaTypes = $aMediaTypes;
        $output->addJavascriptFile(array(
            'media2/js/jquery/plugins/jquery.dimensions.js',
            'media2/js/jquery/plugins/jquery.shadow.js',
            'media2/js/jquery/plugins/jquery.ifixpng.js',
            'media2/js/jquery/plugins/jquery.fancyzoom.js',
            'media2/js/Media2.js',
            'media2/js/Media2/List.js'
        ));
        $output->addOnLoadEvent('Media2.List.init()', true);
    }

    public function _cmd_upload(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $aTypes = $this->da->getMediaTypeInfoList();

        $output->aTypes   = $aTypes;
        $output->template = 'media2Upload.html';
        $output->addJavascriptFile(array(
            'js/jquery/plugins/jquery.form.js',
            'media2/js/jquery/ajaxfileupload.js',
            'media2/js/Media2.js',
            'media2/js/Media2/Upload.js',
            'media2/js/Media2/Edit.js'
        ));
        $output->addOnLoadEvent('Media2.Upload.init()', true);
    }

    public function _cmd_edit(SGL_Registry $input, SGL_Output $output)
    {
        $oMedia = $this->da->getMediaById($input->mediaId);

        $output->aMimeTypes  = $this->da->getMimeTypeInfoList();
        $output->aMediaTypes = $this->da->getMediaTypeInfoList();
        $output->oMedia      = $oMedia;
        $output->template    = 'media2Edit.html';
        $output->addJavascriptFile(array(
            'js/jquery/plugins/jquery.form.js',
            'media2/js/Media2.js',
            'media2/js/Media2/Edit.js'
        ));
        $output->addOnLoadEvent('Media2.Edit.init()', true);
    }

    public function _cmd_download(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        require_once SGL_CORE_DIR . '/Download.php';

        $oMedia   = $this->da->getMediaById($input->mediaId);
        $fileName = SGL_UPLOAD_DIR . '/' . $oMedia->file_name;

        $oDownload = new SGL_Download();
        $oDownload->setFile($fileName);
        $oDownload->setContentType($oMedia->mime_type);
        $oDownload->setContentDisposition(HTTP_DOWNLOAD_ATTACHMENT, $oMedia->name);
        $oDownload->setAcceptRanges('none');

        $ok = $oDownload->send();
        exit;
    }

    public function _cmd_preview(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        require_once SGL_CORE_DIR . '/Download.php';

        $oMedia = $this->da->getMediaById($input->mediaId);
        if (SGL_Media_Util::isImageMimeType($oMedia->mime_type)) {
            $path = SGL_Media_Util::getImagePathByMimeType(
                $oMedia->file_name,
                $oMedia->mime_type,
                $input->thumb
            );
            $path = SGL_APP_ROOT . '/' . $path;
        } else {
            $path = SGL_Media_Util::getIconPathByMimeType($oMedia->mime_type);
        }

        $oDownload = new SGL_Download();
        $oDownload->setFile($path);
        $oDownload->setContentType($oMedia->mime_type);
        $oDownload->setContentDisposition(HTTP_DOWNLOAD_INLINE, $oMedia->name);
        $oDownload->setAcceptRanges('none');

        $ok = $oDownload->send();
        exit;
    }

    public function _cmd_listSimple(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $output->redir = $input->getCurrentUrl()->toString();

        $output->template       = 'media2ListSimple.html';
        $output->masterTemplate = 'masterSimple.html';
        $output->addOnLoadEvent('Media2.List.initSimple()', true);
    }

    public function _cmd_uploadSimple(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        $output->template       = 'media2UploadSimple.html';
        $output->masterTemplate = 'masterSimple.html';
        $output->addOnLoadEvent('Media2.Upload.initSimple()', true);
    }
}
?>