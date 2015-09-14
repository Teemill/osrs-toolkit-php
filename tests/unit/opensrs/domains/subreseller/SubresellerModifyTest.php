<?php

use OpenSRS\domains\subreseller\SubresellerModify;
/**
 * @group subreseller
 * @group SubresellerModify
 */
class SubresellerModifyTest extends PHPUnit_Framework_TestCase
{
    protected $func = 'subresModify';

    protected $validSubmission = array(
        "data" => array(
            /**
             * Required
             *
             * cpp_enabled: indicates if credit card
             *   payments are enabled for sub-reseller
             *   values:
             *     - N = no
             *     - Y = yes
             * low_balance_email: email address to
             *   send notice when balance falls to a
             *   predefined level
             * username: username for the sub-reseller
             * password: password for the new sub-
             *   reseller account
             * pricing_plan: pricing plan assigned to
             *   the sub-reseller
             * status: status of the account, accepted
             *   values: active, onhold, locked,
             *           cancelled, paid_only
             * system_status_email: email address that
             *   will receive system status messages
             */
            "ccp_enabled" => "",
            "low_balance_email" => "",
            "username" => "",
            "password" => "",
            "pricing_plan" => "",
            "status" => "",
            "system_status_email" => "",

            /**
             * Optional
             *
             * url: web address of the account
             * nameservers: list of default nameservers
             *   for the sub-reseller
             * storefront_rwi: determines whether the
             *   sub-reseller sees the storefront ink
             *   in RWI (and hense whether the sub-
             *   reseller can configure a storefront).
             *   accepted values:
             *     - N = do not show link (default)
             *     - Y = show storefront link
             * payment_email: email payment notices
             *   are sent to
             * allowed_tld_list: list of TLDs sub-
             *   reseller is allowed to sell
             */
            "url" => "",
            "nameservers" => "",
            "storefront_rwi" => "",
            "payment_email" => "",
            "allowed_tld_list" => "",
            ),
        /**
         * Required
         *
         * NOTE THE FOLLOWING ARE SIBLING ENTRIES
         * TO ->data, THEY ARE NOT CHILDREN
         *
         * personal: associative array
         *   containing contact information for
         *   domain owner
         *   Note: admin, billing, tech contacts
         *         are also supported. 'admin'
         *         contact is the sub-reseller's
         *         emergency contact, not domain
         *         admin contact
         */
        "personal" => array(
            "first_name" => "",
            "last_name" => "",
            "org_name" => "",
            "address1" => "",
            // address2 optional
            "address2" => "",
            // address3 optional
            "address3" => "",
            "city" => "",
            "state" => "",
            "country" => "",
            "postal_code" => "",
            "phone" => "",
            "fax" => "",
            "email" => "",
            "url" => "",
            "lang_pref" => ""
            ),
        );

    /**
     * Valid submission should complete with no
     * exception thrown
     *
     * @return void
     *
     * @group validsubmission
     */
    public function testValidSubmission() {
        $data = json_decode( json_encode($this->validSubmission) );

        $data->data->username = "subreseller" . time();
        $data->data->password = "password1234";
        $data->data->ccp_enabled = "Y";
        $data->data->pricing_plan = "1";
        $data->data->status = "onhold";
        $data->data->low_balance_email = "phptoolkit@tucows.com";
        $data->data->system_status_email = "phptoolkit@tucows.com";

        $data->personal->first_name = "John";
        $data->personal->last_name = "Smith";
        $data->personal->org_name = "Tucows";
        $data->personal->address1 = "96 Mowat Avenue";
        $data->personal->address2 = "";
        $data->personal->address3 = "";
        $data->personal->city = "Toronto";
        $data->personal->state = "ON";
        $data->personal->country = "CA";
        $data->personal->postal_code = "M6K 3M1";
        $data->personal->phone = "+1.4165350123";
        $data->personal->email = "phptoolkit@tucows.com";
        $data->personal->lang_pref = "EN";

        $ns = new SubresellerModify( 'array', $data );

        $this->assertTrue( $ns instanceof SubresellerModify );
    }

    /**
     * Data Provider for Invalid Submission test
     */
    function submissionFields() {
        return array(
            'missing username' => array('username'),
            'missing password' => array('password'),
            'missing ccp_enabled' => array('ccp_enabled'),
            'missing pricing_plan' => array('pricing_plan'),
            'missing status' => array('status'),
            'missing low_balance_email' => array('low_balance_email'),
            'missing system_status_email' => array('system_status_email'),
            'missing amount' => array('password'),

            // data->personal fields
            'missing first_name' => array('first_name', 'personal'),
            'missing last_name' => array('last_name', 'personal'),
            'missing org_name' => array('org_name', 'personal'),
            'missing address1' => array('address1', 'personal'),
            'missing city' => array('city', 'personal'),
            'missing state' => array('state', 'personal'),
            'missing country' => array('country', 'personal'),
            'missing postal_code' => array('postal_code', 'personal'),
            'missing phone' => array('phone', 'personal'),
            'missing email' => array('email', 'personal'),
            'missing lang_pref' => array('lang_pref', 'personal'),
            );
    }

    /**
     * Invalid submission should throw an exception
     *
     * @return void
     *
     * @dataProvider submissionFields
     * @group invalidsubmission
     */
    public function testInvalidSubmissionFieldsMissing( $field, $parent = 'data' ) {
        $data = json_decode( json_encode($this->validSubmission) );

        $data->data->username = "subreseller" . time();
        $data->data->password = "password1234";
        $data->data->ccp_enabled = "Y";
        $data->data->pricing_plan = "1";
        $data->data->status = "onhold";
        $data->data->low_balance_email = "phptoolkit@tucows.com";
        $data->data->system_status_email = "phptoolkit@tucows.com";

        $data->personal->first_name = "John";
        $data->personal->last_name = "Smith";
        $data->personal->org_name = "Tucows";
        $data->personal->address1 = "96 Mowat Avenue";
        $data->personal->address2 = "";
        $data->personal->address3 = "";
        $data->personal->city = "Toronto";
        $data->personal->state = "ON";
        $data->personal->country = "CA";
        $data->personal->postal_code = "M6K 3M1";
        $data->personal->phone = "+1.4165350123";
        $data->personal->email = "phptoolkit@tucows.com";
        $data->personal->lang_pref = "EN";

        $this->setExpectedExceptionRegExp(
            'OpenSRS\Exception',
            "/$field.*not defined/"
            );



        // clear field being tested
        if(is_null($parent)){
            unset( $data->$field );
        }
        else{
            unset( $data->$parent->$field );
        }

        $ns = new SubresellerModify( 'array', $data );
     }
}