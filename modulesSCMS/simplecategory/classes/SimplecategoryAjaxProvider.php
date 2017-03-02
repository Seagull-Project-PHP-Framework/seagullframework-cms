<?php
require_once SGL_CORE_DIR . '/Delegator.php';
require_once SGL_CORE_DIR . '/AjaxProvider2.php';
require_once SGL_MOD_DIR . '/simplecategory/classes/SimpleCategoryDAO.php';

/**
 * Ajax provider.
 *
 * @package simplecategory
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class SimplecategoryAjaxProvider extends SGL_AjaxProvider2
{
    public function __construct()
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::__construct();

        $this->da = new SGL_Delegator();
        $this->da->add(SimpleCategoryDAO::singleton());
    }

    public function process(SGL_Registry $input, SGL_Output $output)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        // turn off autocommit
        $this->dbh->autoCommit(false);

        $ok = parent::process($input, $output);
        DB::isError($ok)
            ? $this->dbh->rollback()
            : $this->dbh->commit();

        // turn autocommit on
        $this->dbh->autoCommit(true);

        return $ok;
    }

    /**
     * Ensure the current user can perform requested action.
     *
     * @param integer $requestedUserId
     *
     * @return boolean
     */
    protected function _isOwner($requestedUserId)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);

        return in_array(SGL_Session::getRoleId(), array(SGL_ADMIN, SGL_ROLE_MODERATOR));
    }

    public function refreshCategoryTree(SGL_Registry $input, SGL_Output $output)
    {
        $langId = $this->req->get('langId');

        $aCategories = $this->da->getTreeByCategoryId(
            SGL_CATEGORY_ROOT, $langId,
            $onlyActive = false, $showUntranlsated = true);

        $output->html = $this->_renderTemplate($output, array(
            'aCats'          => $aCategories,
            'masterTemplate' => 'simplecategory_tree.html'
        ));
    }

    public function moveCategory(SGL_Registry $input, SGL_Output $output)
    {
        $categoryId = $this->req->get('categoryId');
        $orderId    = $this->req->get('orderId');
        $parentId   = $this->req->get('parentId');

        $this->da->moveCategoryById($categoryId, $parentId, $orderId);
    }

    public function deleteCategory(SGL_Registry $input, SGL_Output $output)
    {
        $categoryId = $this->req->get('categoryId');

        // all subtree with relevant translations will be removed
        $this->da->deleteCategoryById($categoryId);
    }

    public function updateCategory(SGL_Registry $input, SGL_Output $output)
    {
        $categoryId = $this->req->get('categoryId');
        $oCategory  = (object)$this->req->get('category');

        // by default we update
        $newCategory = false;

        // if category ID is missing - we're adding new category
        if (empty($categoryId)) {
            $categoryId  = $this->da->addCategory($oCategory->parent_id,
                $oCategory->is_active);
            $newCategory = true;
        }

        $this->da->updateCategoryById($categoryId,
            array('is_active' => $oCategory->is_active));

        // add or update translation
        $ok = $this->da->updateCategoryTranslationById($categoryId,
            $oCategory->language_id, array(
                'name'        => $oCategory->name,
                'description' => $oCategory->description
            )
        );

        // category was already removed, show missing template
        if (PEAR::isError($ok)) {
            SGL_Error::pop();
            $output->html = $this->_renderTemplate($output, 'simplecategory_missing.html');

        // get updated edit screen
        } else {
            if ($newCategory) {
                $this->req->set('categoryId', $categoryId);
            }
            $this->req->set('langId', $oCategory->language_id);
            $this->getCategoryEditScreen($input, $output);
        }
        $output->isNew      = $newCategory;
        $output->name       = $oCategory->name;
        $output->categoryId = $categoryId;
    }

    public function getCategoryAddScreen(SGL_Registry $input, SGL_Output $output)
    {
        $parentId = $this->req->get('parentId');
        $langId   = $this->req->get('langId');

        $oCategory              = new stdClass();
        $oCategory->parent_id   = $parentId;
        $oCategory->is_active   = true;
        $oCategory->language_id = $langId;

        $aPath = $this->da->getPathByCategoryId($parentId, $langId);

        $output->html = $this->_renderTemplate($output, array(
            'oCategory'      => $oCategory,
            'masterTemplate' => 'simplecategory_form.html',
//            'aLangs'         => $aLangs,
            'aPath'          => $aPath
        ));
    }

    public function getCategoryEditScreen(SGL_Registry $input, SGL_Output $output)
    {
        $categoryId = $this->req->get('categoryId');
        $langId     = $this->req->get('langId');

        $aLangs     = SimpleCms_Util::formatLanguageList(SGL_Util::getLangsDescriptionMap());
        $aPath      = $this->da->getPathByCategoryId($categoryId, $langId);
        $oCategory  = $this->da->getCategoryById($categoryId, $langId,
            $includeMissingTrans = true);

        // if translation is missing let's at least specify required language
        if (empty($oCategory->language_id)) {
            $oCategory->language_id = $langId;
        }

        $output->html = $this->_renderTemplate($output, array(
            'oCategory'      => $oCategory,
            'masterTemplate' => 'simplecategory_form.html',
            'aLangs'         => $aLangs,
            'aPath'          => $aPath,
            'isEdit'         => true
        ));
    }
}
?>