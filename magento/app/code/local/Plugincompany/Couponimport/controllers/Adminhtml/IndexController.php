<?php
/**
 *
 * Created by:  Milan Simek
 * Company:     Plugin Company
 *
 * LICENSE: http://plugin.company/docs/magento-extensions/magento-extension-license-agreement
 *
 * YOU WILL ALSO FIND A PDF COPY OF THE LICENSE IN THE DOWNLOADED ZIP FILE
 *
 * FOR QUESTIONS AND SUPPORT
 * PLEASE DON'T HESITATE TO CONTACT US AT:
 *
 * SUPPORT@PLUGIN.COMPANY
 *
 */
?>
<?php
class Plugincompany_Couponimport_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{

    protected function _initRule()
    {
        $this->_title($this->__('Promotions'))->_title($this->__('Shopping Cart Price Rules'));

        Mage::register('current_promo_quote_rule', Mage::getModel('salesrule/rule'));
        $id = (int)$this->getRequest()->getParam('id');

        if (!$id && $this->getRequest()->getParam('rule_id')) {
            $id = (int)$this->getRequest()->getParam('rule_id');
        }

        if ($id) {
            Mage::registry('current_promo_quote_rule')->load($id);
        }
    }

    /**
     * Process pasted coupons
     */
    public function pasteAction()
    {
        $post = Mage::app()->getRequest()->getPost();
        $ruleId = $this->getRequest()->getParam('id');
        $coupons = explode("\n", $post['paste_rule_import']);

        $couponCount = count($coupons);

        //remove duplicates
        $coupons = array_unique($coupons);

        if ($couponCount != count($coupons)) {
            $duplicates = $couponCount - count($coupons);
            Mage::getSingleton('core/session')->addNotice("$duplicates duplicate codes not added");
        }

        if (!empty($coupons)) {
            Mage::getModel('plugincompany_couponimport/importer')->importCoupons($coupons,$ruleId);
        }

    }

    /**
     * Process uploaded file
     */
    public function importfileAction()
    {
        $ruleId = $this->getRequest()->getParam('id');
        $tmpName = $_FILES['csv_rule_import']['tmp_name'];

        //if no file detected display error
        if (!$tmpName) {
            Mage::getSingleton('core/session')->addNotice("Please select a file first");
            echo '<script type="text/javascript">parent.update()</script>';
            exit;
        }

        //if not .txt file display error
        if (!$_FILES['csv_rule_import']['type'] == "text/plain" || !stristr($_FILES['csv_rule_import']['name'],'.txt')) {
            Mage::getSingleton('core/session')->addError("Uploaded file is not a .txt file");
            echo '<script type="text/javascript">parent.update()</script>';
            exit;
        }


        //process coupons
        $coupons = array();
        $fh = fopen($tmpName,'r');
        while ($line = fgets($fh)) {
            $coupons[] = str_replace("\n", '', $line);
        }

        $couponCount = count($coupons);

        //remove duplicates
        $coupons = array_unique($coupons);

        if ($couponCount != count($coupons)) {
            $duplicates = $couponCount - count($coupons);
            Mage::getSingleton('core/session')->addNotice("$duplicates duplicate codes not added");
        }

        if (!empty($coupons)) {
            Mage::getModel('plugincompany_couponimport/importer')->importCoupons($coupons,$ruleId);
        }

        echo '<script type="text/javascript">parent.update()</script>';
    }

    public function couponsGridAction(){
        $this->_initRule();
        $this->loadLayout()->renderLayout();
    }

    /**
     * Export coupon codes as excel xml file
     *
     * @return void
     */
    public function exportCouponsXmlAction()
    {
        $this->_initRule();
        $rule = Mage::registry('current_promo_quote_rule');
        if ($rule->getId()) {
            $fileName = 'coupon_codes.xml';
            $content = $this->getLayout()
                ->createBlock('adminhtml/promo_quote_edit_tab_coupons_grid')
                ->getExcelFile($fileName);
            $this->_prepareDownloadResponse($fileName, $content);
        } else {
            $this->_redirect('*/*/detail', array('_current' => true));
            return;
        }
    }

    /**
     * Export coupon codes as CSV file
     *
     * @return void
     */
    public function exportCouponsCsvAction()
    {
        $this->_initRule();
        $rule = Mage::registry('current_promo_quote_rule');
        if ($rule->getId()) {
            $fileName = 'coupon_codes.csv';
            $content = $this->getLayout()
                ->createBlock('adminhtml/promo_quote_edit_tab_coupons_grid')
                ->getCsvFile();
            $this->_prepareDownloadResponse($fileName, $content);
        } else {
            $this->_redirect('*/*/detail', array('_current' => true));
            return;
        }
    }

    /**
     * Coupons mass delete action
     */
    public function couponsMassDeleteAction()
    {
        $this->_initRule();
        $rule = Mage::registry('current_promo_quote_rule');

        if (!$rule->getId()) {
            $this->_forward('noRoute');
        }

        $codesIds = $this->getRequest()->getParam('ids');

        if (is_array($codesIds)) {

            $couponsCollection = Mage::getResourceModel('salesrule/coupon_collection')
                ->addFieldToFilter('coupon_id', array('in' => $codesIds));

            foreach ($couponsCollection as $coupon) {
                $coupon->delete();
            }
        }
    }

}