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
class Plugincompany_Couponimport_Model_Importer {


    /**
     * Processes coupons based on array input
     *
     * @param array $coupons
     * @param $ruleId
     * @return $this
     */
    public function importCoupons(array $coupons,$ruleId)
    {
        $rule = Mage::getModel('salesrule/rule')->load($ruleId);
        $now = Mage::getModel('core/date')->date('Y-m-d H:i:s');
        $success = 0;$error = 0;

        $table = $rule->getResource()->getTable('salesrule/coupon');
        $resource = Mage::getSingleton('core/resource');
        $writeConnection = $resource->getConnection('core_write');

        $sql = "INSERT IGNORE INTO $table (rule_id,usage_limit,usage_per_customer,expiration_date,created_at,type,code) ";
        $couponSql = array();
        foreach ($coupons as $couponText) {
            //trim spaces
            $couponText =  trim($couponText);

            //if empty string don't add
            if ($couponText == '' || !$couponText) {
                continue;
            }

            $couponSql[]= "({$rule->getRuleId()},{$rule->getUsesPerCoupon()},{$rule->getUsesPerCustomer()},'{$rule->getToDate()}','{$now}',1,'{$couponText}')";
        }
        $totalCoupons = count($couponSql);
        $values = implode(', ',$couponSql);
        try
        {
            //set coupon data and save
            $sql .= " VALUES " . $values . ";";
            $result = $writeConnection->query($sql);
            $success = $result->rowCount();
        }
        catch(Exception $e)
        {
            $globalError = true;
        }
        if($globalError){
            Mage::getSingleton('core/session')->addError("An error occured while importing");
        }else{
            if($success > 0)
                Mage::getSingleton('core/session')->addSuccess("Successfully imported $success coupon codes");

            $error = $totalCoupons - $success;
            if ($error > 0) {
                Mage::getSingleton('core/session')->addError("Unable to import $error coupon codes");
            }
        }

        return $this;
    }

}
