<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}


class Migrator extends CI_Controller
{
    public function index()
    {
        $account_data = $this->session->userdata('user_id');
        if (empty($account_data)) {
            die('Authorization error.');
        }

        $this->db->db_debug = false;
        $this->create_product_id_field_order_table();
        $this->create_offer_id_field_order_table();
        echo 'Migration Complete <br>';
    }
    private function create_product_id_field_order_table() {
        if (!$this->column_exists('orders', 'product_id')) {
            $this->db->query("ALTER TABLE `orders` ADD `product_id` INT NULL;");
        }
    }
    private function create_offer_id_field_order_table() {
        if (!$this->column_exists('orders', 'offer_id')) {
            $this->db->query("ALTER TABLE `orders` ADD `offer_id` INT NULL;");
        }
    }
    private function create_isMobile_field_session_table() {
        if (!$this->column_exists('sessions', 'is_mobile')) {
            $this->db->query("ALTER TABLE `sessions` ADD `is_mobile` TINYINT NOT NULL DEFAULT '0' AFTER `last_activity`;");
        }
    }
    private function create_env_variables_table()
    {
        if (!$this->table_exists('environment_variables')) {
            $r = $this->db->query(
                "CREATE TABLE `environment_variables` ( `variable` VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL , `value` VARCHAR(256) NOT NULL , PRIMARY KEY (`variable`)) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;"
                );
            $this->check_error('error creating environment_variables table: ', $r);
        }
    }

    private function rename_group_dcc_hash_to_hash(){
        $this->db->query("ALTER TABLE `group_dcc` CHANGE `group_dcc_hash` `hash` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL;");
    }

    private function add_contact_active_to_contact_index(){
        $this->db->query("ALTER TABLE `contacts` DROP INDEX `aid`, ADD INDEX `aid` (`account_id`, `contact_active`);");
    }

    private function make_usernames_unique(){
        $this->db->query("ALTER TABLE `users` ADD UNIQUE `user_username` (`user_username`);");
    }

    private function add_campaign_step_deleted_column(){
        if (!$this->column_exists('campaign_steps', 'campaign_step_deleted')) {
            $this->db->query("ALTER TABLE `campaign_steps` ADD `campaign_step_deleted` TINYINT NOT NULL DEFAULT '0' AFTER `campaign_step_media_url`;");
            $this->db->query("ALTER TABLE `textinchurch`.`campaign_steps` DROP INDEX `cid`, ADD INDEX `cid` (`campaign_id`, `campaign_step_deleted`);");
        }
    }

    private function newDCCFields()
    {
        if (!$this->table_exists('group_dcc')) {
            $r = $this->db->query(
                "CREATE TABLE `group_dcc` (".
                "`group_dcc_id` int(11) NOT NULL AUTO_INCREMENT,".
                "`group_dcc_hash` varchar(25) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,".
                "`account_id` int(11) NOT NULL,".
                "`group_id` int(11) NOT NULL,".
                "`group_dcc_title` varchar(160) NOT NULL DEFAULT '',".
                "`group_dcc_desc` varchar(255) NOT NULL DEFAULT '',".
                "`group_dcc_child_info` int(1) NOT NULL DEFAULT '0',".
                "`group_dcc_options` text NOT NULL,".
                "`group_dcc_widgets` text NOT NULL,".
                "`group_dcc_video_url` varchar(256) NOT NULL DEFAULT '',".
                "`group_dcc_fb_pixel_id` varchar(160) NOT NULL DEFAULT '',".
                "`group_dcc_redirect_url` varchar(256) NOT NULL DEFAULT '',".
                "`group_dcc_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,".
                "PRIMARY KEY (`group_dcc_id`),".
                "UNIQUE KEY `hash` (`group_dcc_hash`),".
                "INDEX `account_id` (`account_id`),".
                "INDEX `group_id` (`group_id`)".
                ") ENGINE=InnoDB DEFAULT CHARSET=latin1;"
                );
            $this->check_error('install_account_integrations: error creating group_dcc table: ', $r);

            $r = $this->db->query(
                "ALTER TABLE `group_dcc` ".
                "ADD FOREIGN KEY fk_account(group_id) REFERENCES groups(group_id) ON DELETE CASCADE ON UPDATE CASCADE,".
                "ADD FOREIGN KEY fk_account(account_id) REFERENCES accounts(account_id) ON DELETE CASCADE ON UPDATE CASCADE"
                );
            $this->check_error('group_dcc: error adding keys: ', $r);

            /*$r = $this->db->query(
                "ALTER TABLE `group_dcc` MODIFY `group_dcc_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1"
                );
            $this->check_error('group_dcc: error adding autoincrement for group_dcc_id column: ', $r);*/
        }
    }
    private function group_dcc_video_intro_column()
    {
        if (!$this->column_exists('group_dcc', 'group_dcc_intro_video_url')) {
            $r = $this->db->query("ALTER TABLE `group_dcc` ADD `group_dcc_intro_video_url` VARCHAR(255) NOT NULL DEFAULT '' AFTER `group_dcc_widgets`;");
            $this->check_error('group_dcc: add group_dcc_intro_video_url column: ', $r);
        }
    }

    private function campaignStepsRepeat()
    {
        if (!$this->column_exists('campaign_steps', 'campaign_step_num_repeat')) {
            $r = $this->db->query("ALTER TABLE campaign_steps ADD `campaign_step_num_repeat` int(11) NOT NULL DEFAULT 0 AFTER `campaign_step_repeat`");
            $this->check_error('campaign_steps: add campaign_step_num_repeat column: ', $r);
        }
    }

    private function accountTaxExemptNumber()
    {
        if (!$this->column_exists('accounts', 'account_tax_id')) {
            $r = $this->db->query("ALTER TABLE accounts ADD `account_tax_id` varchar(160) NOT NULL AFTER `account_type`");
            $this->check_error('account: add account_tax_id column: ', $r);
        }
    }

    private function accountPauseField()
    {
        if (!$this->column_exists('accounts', 'account_is_paused')) {
            $r = $this->db->query("ALTER TABLE `accounts` ADD `account_is_paused` TINYINT(1) NOT NULL DEFAULT '0' AFTER `account_cancel_date`");
            $this->check_error('account: add account_is_paused column: ', $r);
        }
    }

    private function install_post()
    {
        error_log('first');
        if (!$this->table_exists('blog_posts')) {
            error_log('second');
            $r = $this->db->query(
                            "CREATE TABLE `blog_posts` (".
                            "`post_id` int(11) NOT NULL,".
                            "`post_title` text  NOT NULL,".
                            "`post_content` text NOT NULL DEFAULT '',".
                            "`post_permalink` varchar(100) NOT NULL DEFAULT '',".
                            "`post_description` varchar(256) NOT NULL DEFAULT '',".
                            "`post_feature_image` varchar(256) NOT NULL DEFAULT '',".
                            "`post_status` tinyint(4) NOT NULL ,".
                            "`post_create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,".
                            "`post_updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ".
                            ") ENGINE=MyISAM DEFAULT CHARSET=latin1;"
                    );

            $this->check_error('install_blog_posts: error creating table: ', $r);

            $r = $this->db->query(
                            "ALTER TABLE `blog_posts` ADD PRIMARY KEY (`post_id`)"
                    );
            $this->check_error('install_blog_posts: error adding keys: ', $r);

            $r = $this->db->query(
                            "ALTER TABLE `blog_posts` MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1"
                    );
            $this->check_error('install_blog_posts: error adding autoincrement for post_id column: ', $r);
        }
    }

    private function groupsFormComplete()
    {
        if (!$this->column_exists('groups', 'group_form_completion_sms')) {
            $r = $this->db->query("ALTER TABLE groups ADD `group_form_completion_sms` text NOT NULL AFTER `group_reply_text`");
            $this->check_error('groups: add group_form_completion_sms column: ', $r);
        }

        if (!$this->column_exists('groups', 'group_form_completion_reminder_sms')) {
            $r = $this->db->query("ALTER TABLE groups ADD `group_form_completion_reminder_sms` varchar(100) NOT NULL AFTER `group_form_completion_sms`");
            $this->check_error('groups: add group_form_completion_reminder_sms column: ', $r);
        }

        if (!$this->column_exists('groups', 'group_form_completion_reminder_email')) {
            $r = $this->db->query("ALTER TABLE groups ADD `group_form_completion_reminder_email` varchar(100) NOT NULL AFTER `group_form_completion_sms`");
            $this->check_error('groups: add group_form_completion_reminder_email column: ', $r);
        }

        if (!$this->column_exists('groups', 'group_form_completion_reminder_content')) {
            $r = $this->db->query("ALTER TABLE groups ADD `group_form_completion_reminder_content` text NOT NULL AFTER `group_form_completion_reminder_email`");
            $this->check_error('groups: add group_form_completion_reminder_content column: ', $r);
        }
        if (!$this->column_exists('groups', 'group_comment_title')) {
            $r = $this->db->query("ALTER TABLE groups ADD `group_comment_title` varchar(100) NOT NULL AFTER `group_comments`");
            $this->check_error('groups: add group_comment_title column: ', $r);
        }
    }

    private function importDeveloperApplication()
    {
        if (!$this->db->table_exists('developer_applications')) {
            $this->db->query("CREATE TABLE `developer_applications` (
                `app_id` int(11) NOT NULL,
                `account_id` int(11) NOT NULL,
                `application_name` varchar(255) NOT NULL,
                `application_description` text NOT NULL,
                `information_url` varchar(255) NOT NULL,
                `authorization_callback_urls` text NOT NULL,
                `application_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Active,0=In Active,2=Block',
                `application_create_date` datetime NOT NULL,
                `application_stamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `app_secret_key` varchar(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
            $this->db->query("ALTER TABLE `developer_applications`
                ADD PRIMARY KEY (`app_id`);");
            $this->db->query(" ALTER TABLE `developer_applications`
                    MODIFY `app_id` int(11) NOT NULL AUTO_INCREMENT;");
        }
    }


    private function add_april16_update()
    {
        // Add column for Account Color
        if (!$this->column_exists('accounts', 'account_color')) {
            $r = $this->db->query("ALTER TABLE accounts ADD `account_color` varchar(8) NOT NULL AFTER `account_test_mode`");
            $this->check_error('accounts: add account_color column: ', $r);
        }

        // Add column for Account Logo Type
        if (!$this->column_exists('accounts', 'account_logo_type')) {
            $r = $this->db->query("ALTER TABLE accounts ADD `account_logo_type` varchar(64) NOT NULL AFTER `account_color`");
            $this->check_error('accounts: add account_logo_type column: ', $r);
        }

        // Add column for Account Logo URL
        if (!$this->column_exists('accounts', 'account_logo_url')) {
            $r = $this->db->query("ALTER TABLE accounts ADD `account_logo_url` varchar(255) NOT NULL AFTER `account_logo_type`");
            $this->check_error('accounts: add account_logo_url column: ', $r);
        }

        // Add column for Account Color
        if (!$this->column_exists('accounts', 'account_referral_auth_key')) {
            $r = $this->db->query("ALTER TABLE accounts ADD `account_referral_auth_key` varchar(255) NOT NULL AFTER `account_logo_url`");
            $this->check_error('accounts: add account_referral_auth_key column: ', $r);
        }

        // Add column for User Onboarding
        if (!$this->column_exists('users', 'user_onboarded')) {
            $r = $this->db->query("ALTER TABLE users ADD `user_onboarded` varchar(1) NOT NULL AFTER `user_last_name`");
            $r = $this->db->query("Update users SET user_onboarded = 1");
            $this->check_error('users: add user_onboarded column: ', $r);
        }

        // Add column for User Onboarding
        if (!$this->column_exists('statistics', 'stat_group_count')) {
            $r = $this->db->query("ALTER TABLE statistics ADD `stat_contacts_count` int(11) NOT NULL DEFAULT '0' AFTER `stat_email_count`");
            $r = $this->db->query("ALTER TABLE statistics ADD `stat_group_count` int(11) NOT NULL DEFAULT '0' AFTER `stat_contacts_count`");
            $this->check_error('statistics: add stat_group_count column: ', $r);
        }
    }

    private function add_mar5_update()
    {
        // Add column for ip address tracking on article acceptance
        if (!$this->column_exists('user_articles', 'user_ip')) {
            $r = $this->db->query("ALTER TABLE user_articles ADD `user_ip` varchar(160) NOT NULL AFTER `user_id`");
            $this->check_error('user_articles: add user_ip column: ', $r);
        }
        // Add column for tracking errors on integrations
        if (!$this->column_exists('account_integrations', 'integration_has_error')) {
            $r = $this->db->query("ALTER TABLE account_integrations ADD `integration_has_error` int(1) NOT NULL DEFAULT 0 AFTER `integration_is_active`");
            $this->check_error('account_integrations: add integration_has_error column: ', $r);
        }
    }

    private function add_rcp_notes()
    {
        if (!$this->column_exists('receipts', 'rcp_notes')) {
            $r = $this->db->query("ALTER TABLE receipts ADD `rcp_notes` varchar(160) NOT NULL AFTER `rcp_status`");
            $this->check_error('receipts: add rcp_notes column: ', $r);
        } else {
            $r = $this->db->query(
                "ALTER TABLE `receipts` MODIFY `rcp_notes` varchar(160) NOT NULL"
                );
            $this->check_error('receipts: add rcp_notes column: ', $r);
        }
    }

    private function install_product_stripe_details()
    {
        if (!$this->column_exists('products', 'product_key')) {
            $r = $this->db->query("ALTER TABLE products ADD `product_key` varchar(100) NOT NULL AFTER `product_parent_id`");
            $this->check_error('products: add product_key column: ', $r);
        }

        if (!$this->column_exists('accounts', 'stripe_id')) {
            $r = $this->db->query("ALTER TABLE accounts ADD `stripe_id` varchar(100) NOT NULL AFTER `account_sid`");
            $this->check_error('accounts: add stripe_id column: ', $r);
        }

        if (!$this->table_exists('sales')) {
            $r = $this->db->query(
                "CREATE TABLE `sales` (".
                "`sale_id` int(11) NOT NULL,".
                "`sale_key` varchar(11) NOT NULL DEFAULT '',".
                "`sale_details` TEXT NOT NULL DEFAULT '',".
                "`sale_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP".
                ") ENGINE=MyISAM DEFAULT CHARSET=latin1;"
                );
            $this->check_error('install_sales: error creating sales table: ', $r);

            $r = $this->db->query(
                "ALTER TABLE `sales` ADD PRIMARY KEY (`sale_id`)"
                );
            $this->check_error('install_sales: error adding keys: ', $r);

            $r = $this->db->query(
                "ALTER TABLE `sales` MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1"
                );
            $this->check_error('install_sales: error adding autoincrement for sales_id column: ', $r);
        }
    }

    private function install_locked_groups()
    {
        if (!$this->column_exists('groups', 'group_linked_id')) {
            $r = $this->db->query("ALTER TABLE groups ADD `group_linked_id` varchar(255) NOT NULL AFTER `group_is_catchall`");
            $this->check_error('groups: add group_linked_id column: ', $r);
        }

        if (!$this->column_exists('account_integrations', 'integration_is_active')) {
            $r = $this->db->query("ALTER TABLE account_integrations ADD `integration_is_active` int(1) NOT NULL DEFAULT 0 AFTER `integration_type`");
            $this->check_error('account_integrations: add integration_is_active column: ', $r);
        }

        if (!$this->column_exists('users', 'users_notifications_status')) {
            $r = $this->db->query("ALTER TABLE users ADD `users_notifications_status` int(1) NOT NULL DEFAULT 0 AFTER `user_enabled`");
            $this->check_error('users: add users_notifications_status column: ', $r);
        }
    }

    private function install_msg_recepients()
    {
        if (!$this->column_exists('conversations', 'msg_recepients')) {
            $r = $this->db->query("ALTER TABLE conversations ADD `msg_recepients` varchar(255) NOT NULL AFTER `group_id`");
            $this->check_error('conversations: add msg_recepients column: ', $r);
        }
    }

    private function install_account_integrations()
    {
        if (!$this->table_exists('account_integrations')) {
            $r = $this->db->query(
                "CREATE TABLE `account_integrations` (".
                "`account_integrations_id` int(11) NOT NULL,".
                "`account_id` int(11) NOT NULL,".
                "`integration_type` varchar(11) NOT NULL DEFAULT '',".
                "`integration_details` TEXT NOT NULL DEFAULT '',".
                "`integration_callback` varchar(255) NOT NULL DEFAULT '',".
                "`integration_settings` TEXT NOT NULL DEFAULT '',".
                "`account_integration_last_run_date` datetime DEFAULT '0000-00-00 00:00:00',".
                "`account_integration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP".
                ") ENGINE=MyISAM DEFAULT CHARSET=latin1;"
                );
            $this->check_error('install_account_integrations: error creating account_integrations table: ', $r);

            $r = $this->db->query(
                "ALTER TABLE `account_integrations` ADD PRIMARY KEY (`account_integrations_id`),".
                "ADD FOREIGN KEY fk_account(account_id) REFERENCES accounts(account_id) ON DELETE CASCADE ON UPDATE CASCADE"
                );
            $this->check_error('install_account_integrations: error adding keys: ', $r);

            $r = $this->db->query(
                "ALTER TABLE `account_integrations` MODIFY `account_integrations_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1"
                );
            $this->check_error('account_integrations: error adding autoincrement for account_integrations_id column: ', $r);
        }
        if (!$this->column_exists('contacts', 'contact_pco_id')) {
            $r = $this->db->query("ALTER TABLE contacts ADD `contact_pco_id` int(11) NOT NULL AFTER `contact_data_full`");
            $this->check_error('install_financials: error adding contact_pco_id column: ', $r);
        }
    }

    private function update_financials()
    {
        $this->load->model('accounts_model');
        if (!$this->column_exists('accounts', 'account_bill_day')) {
            $r = $this->db->query("ALTER TABLE accounts ADD `account_bill_day` int(11) AFTER `account_create_date`");
            $this->check_error('install_financials: error adding account_bill_day column: ', $r);
        }

        if (!$this->column_exists('accounts', 'account_bill_month')) {
            $r = $this->db->query("ALTER TABLE accounts ADD `account_bill_month` int(11) AFTER `account_create_date`");
            $this->check_error('install_financials: error adding account_bill_month column: ', $r);
        }

        if (!$this->column_exists('accounts', 'account_referer_id')) {
            $r = $this->db->query("ALTER TABLE accounts ADD `account_referer_id` int(11) AFTER `account_id`");
            $this->check_error('install_financials: error adding account_referer_id column: ', $r);
        }

        if (!$this->column_exists('accounts', 'account_cancel_date')) {
            $r = $this->db->query("ALTER TABLE accounts ADD `account_cancel_date` datetime DEFAULT '0000-00-00 00:00:00' AFTER `account_expiration_date`");
            $this->check_error('install_financials: error adding account_cancel_date column: ', $r);
        }



        $sparams = array('account_bill_day'=>null);
        $fields = array('account_id', 'account_expiration_date', 'account_renewal_date', 'account_create_date');
        $accounts = $this->accounts_model->search_accounts($sparams, $fields);

        $updates = array();
        if (!empty($accounts)) {
            foreach ($accounts->result() as $account) {
                if ($account->account_expiration_date!='0000-00-00 00:00:00') {
                    $updates['account_bill_day'] = date('d', strtotime($account->account_expiration_date));
                } elseif ($account->account_renewal_date!='0000-00-00 00:00:00') {
                    $updates['account_bill_day'] = date('d', strtotime($account->account_renewal_date));
                } elseif ($account->account_create_date!='0000-00-00 00:00:00') {
                    $updates['account_bill_day'] = date('d', strtotime($account->account_create_date));
                } else {
                    $updates['account_bill_day'] = 1;
                    error_log('Could not set day for account number: '.$account->account_id);
                }

            // Check if there is a yearly subscription
            $products = $this->accounts_model->get_products($account->account_id, array('product_type'=>'parent', 'product_frequency'=>'yearly'));
                if (count($products)>0) {
                    if ($account->account_expiration_date!='0000-00-00 00:00:00') {
                        $updates['account_bill_month'] = date('m', strtotime($account->account_expiration_date));
                    } elseif ($account->account_renewal_date!='0000-00-00 00:00:00') {
                        $updates['account_bill_month'] = date('m', strtotime($account->account_renewal_date));
                    } elseif ($account->account_create_date!='0000-00-00 00:00:00') {
                        $updates['account_bill_month'] = date('m', strtotime($account->account_create_date));
                    } else {
                        $updates['account_bill_month'] = 1;
                    }
                }

                $this->accounts_model->update_account($account->account_id, $updates);
            }
        }
    }


    /* Installation Methods
     */

    private function add_beta_columns()
    {
        if (!$this->column_exists('messages', 'msg_email_template')) {
            $r = $this->db->query("ALTER TABLE messages ADD `msg_email_template` varchar(64) NOT NULL AFTER `msg_subject`");
            $this->check_error('messages: add msg_email_template column: ', $r);
        }

        if (!$this->column_exists('campaign_steps', 'campaign_step_email_template')) {
            $r = $this->db->query("ALTER TABLE campaign_steps ADD `campaign_step_email_template` varchar(64) NOT NULL AFTER `campaign_step_from_email`");
            $this->check_error('campaign_steps: add campaign_step_email_template column: ', $r);
        }

        if (!$this->column_exists('contacts', 'contact_data_synced')) {
            $r = $this->db->query("ALTER TABLE contacts ADD `contact_data_synced` int(1) NOT NULL DEFAULT 0 AFTER `contact_comments`");
            $this->check_error('contacts: add contact_data_synced column: ', $r);
        }

        if (!$this->column_exists('contacts', 'contact_data_full')) {
            $r = $this->db->query("ALTER TABLE contacts ADD `contact_data_full` varchar(64000) NOT NULL AFTER `contact_active`");
            $this->check_error('contacts: add contact_data_full column: ', $r);
        }

        if (!$this->column_exists('contacts', 'contact_image_url')) {
            $r = $this->db->query("ALTER TABLE contacts ADD `contact_image_url` varchar(255) NOT NULL AFTER `contact_comments`");
            $this->check_error('contacts: add contact_image_url column: ', $r);
        }

        if (!$this->column_exists('contacts', 'contact_image_type')) {
            $r = $this->db->query("ALTER TABLE contacts ADD `contact_image_type` varchar(64) NOT NULL AFTER `contact_comments`");
            $this->check_error('contacts: add contact_image_type column: ', $r);
        }

        if (!$this->column_exists('contacts', 'contact_facebook_url')) {
            $r = $this->db->query("ALTER TABLE contacts ADD `contact_facebook_url` varchar(255) NOT NULL AFTER `contact_comments`");
            $this->check_error('contacts: add contact_facebook_url column: ', $r);
        }

        if (!$this->column_exists('contacts', 'contact_instagram_url')) {
            $r = $this->db->query("ALTER TABLE contacts ADD `contact_instagram_url` varchar(255) NOT NULL AFTER `contact_comments`");
            $this->check_error('contacts: add contact_instagram_url column: ', $r);
        }

        if (!$this->column_exists('contacts', 'contact_twitter_url')) {
            $r = $this->db->query("ALTER TABLE contacts ADD `contact_twitter_url` varchar(255) NOT NULL AFTER `contact_comments`");
            $this->check_error('contacts: add contact_twitter_url column: ', $r);
        }

        if (!$this->column_exists('contacts', 'contact_linkedin_url')) {
            $r = $this->db->query("ALTER TABLE contacts ADD `contact_linkedin_url` varchar(255) NOT NULL AFTER `contact_comments`");
            $this->check_error('contacts: add contact_linkedin_url column: ', $r);
        }
    }

    private function add_install_data()
    {
        if (!$this->column_exists('installations', 'app_fcm_token')) {
            $r = $this->db->query("ALTER TABLE installations ADD `app_fcm_token` text NOT NULL AFTER `user_id`");
            $this->check_error('installations: add app_fcm_token column: ', $r);
        }
    }

    private function add_legal_articles()
    {
        if (!$this->column_exists('articles', 'article_version')) {
            $r = $this->db->query("ALTER TABLE articles ADD `article_status` int(1) NOT NULL DEFAULT '0' AFTER `article_id`");
            $r = $this->db->query("ALTER TABLE articles ADD `article_version` decimal(10,2) NOT NULL DEFAULT '0.00' AFTER `article_id`");
            $r = $this->db->query("ALTER TABLE articles ADD `article_type` varchar(64) NOT NULL DEFAULT '' AFTER `article_id`");
            $r = $this->db->query("ALTER TABLE articles ADD `article_intro` text NOT NULL AFTER `article_title`");
            $r = $this->db->query("ALTER TABLE articles ADD `article_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `article_body`");
            $this->check_error('articles: add article_intro, article_type, article_version, article_stamp column: ', $r);
        }

        if (!$this->column_is_type('articles', 'article_version', 'decimal(10,2)')) {
            $r = $this->db->query("ALTER TABLE `articles` MODIFY `article_version` decimal(10,2) NOT NULL DEFAULT '0.00'");
            $this->check_error('install_addons: error updating article_version column type to DECIMAL: ', $r);
        }
    }

    private function install_financials()
    {
        if (!$this->column_exists('accounts', 'account_test_mode')) {
            $r = $this->db->query("ALTER TABLE accounts ADD `account_test_mode` varchar(16) NOT NULL DEFAULT '' AFTER `account_type`");
            $this->check_error('install_financials: error adding account_test_mode column: ', $r);
        }

        if (!$this->column_exists('accounts', 'account_trial_end_date')) {
            $r = $this->db->query("ALTER TABLE accounts ADD `account_trial_end_date` datetime DEFAULT '0000-00-00 00:00:00' AFTER `account_create_date`");
            $this->check_error('install_financials: error adding account_trial_end_date column: ', $r);
        }

        if (!$this->table_exists('change_logs')) {
            $r = $this->db->query(
                "CREATE TABLE `change_logs` (".
                "`change_log_id` int(11) NOT NULL AUTO_INCREMENT,".
                "`account_id` int(11) NOT NULL,".
                "`invoice_id` int(11) NOT NULL,".
                "`user_id` int(11) NOT NULL,".
                "`change_log_data` text NOT NULL DEFAULT '',".
                "`change_log_date` datetime DEFAULT NULL,".
                "PRIMARY KEY (`change_log_id`),".
                "KEY `account_id` (`account_id`),".
                "KEY `invoice_id` (`invoice_id`),".
                "KEY `user_id` (`user_id`)".
                ") ENGINE=MyISAM DEFAULT CHARSET=latin1;"
                );
            $this->check_error('install_financials: error creating change_logs table: ', $r);
        }

        if (!$this->table_exists('discounts')) {
            $r = $this->db->query(
                "CREATE TABLE `discounts` (".
                "`discount_id` int(11) NOT NULL AUTO_INCREMENT,".
                "`discount_name` varchar(64) NOT NULL DEFAULT '',".
                "`discount_code` varchar(32) NOT NULL DEFAULT '',".
                "`discount_enabled` int(1) NOT NULL DEFAULT 1,".
                "`discount_hidden` int(1) NOT NULL DEFAULT 1,".
                "`discount_type` varchar(16) NOT NULL DEFAULT '',".
                "`discount_product_type` varchar(16) NOT NULL DEFAULT '',".
                "`discount_frequency` varchar(16) NOT NULL DEFAULT '',".
                "`discount_amount` decimal(8, 2) NOT NULL DEFAULT 0.00,".    /* Dollar amount or multiplier */
                "PRIMARY KEY (`discount_id`)".
                ") ENGINE=MyISAM DEFAULT CHARSET=latin1;"
                );
            $this->check_error('install_financials: error creating discounts table: ', $r);
        } elseif (!$this->column_exists('discounts', 'discount_product_type')) {
            $r = $this->db->query("ALTER TABLE discounts ADD `discount_product_type` varchar(16) NOT NULL DEFAULT '' AFTER `discount_type`");
            $this->check_error('install_financials: error adding discount_product_type column: ', $r);
        }

        if (!$this->table_exists('account_discounts')) {
            $r = $this->db->query(
                "CREATE TABLE `account_discounts` (".
                "`account_discount_id` int(11) NOT NULL AUTO_INCREMENT,".
                "`account_id` int(11) NOT NULL,".
                "`discount_id` int(11) NOT NULL,".
                "`account_discount_added` datetime DEFAULT '0000-00-00 00:00:00',".
                "`account_discount_expires` datetime DEFAULT '0000-00-00 00:00:00',".
                "`account_discount_removed` datetime DEFAULT '0000-00-00 00:00:00',".
                "PRIMARY KEY (`account_discount_id`),".
                "KEY `account_id` (`account_id`),".
                "KEY `discount_id` (`discount_id`)".
                ") ENGINE=MyISAM DEFAULT CHARSET=latin1;"
                );
            $this->check_error('install_financials: error creating account_discounts table: ', $r);
        } elseif (!$this->column_exists('account_discounts', 'account_discount_id')) {
            $r = $this->db->query("ALTER TABLE account_discounts ADD `account_discount_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");
            $this->check_error('install_financials: error adding account_discount_id column: ', $r);
        }
        foreach (array('account_discount_added', 'account_discount_expires', 'account_discount_removed') as $col) {
            $r = $this->db->query("ALTER TABLE account_discounts MODIFY COLUMN `$col` datetime DEFAULT '0000-00-00 00:00:00'");
            $this->check_error("install_financials: error changing $col column default value: ", $r);
        }

        if (!$this->table_exists('invoices')) {
            $r = $this->db->query(
                "CREATE TABLE `invoices` (".
                "`invoice_id` int(11) NOT NULL AUTO_INCREMENT,".
                "`account_id` int(11) NOT NULL,".
                "`invoice_status` varchar(16) NOT NULL DEFAULT '',".
                "`invoice_product_total` decimal(8, 2) NOT NULL DEFAULT 0.00,".
                "`invoice_discount_total` decimal(8, 2) NOT NULL DEFAULT 0.00,".
                "`invoice_adjust_total` decimal(8, 2) NOT NULL DEFAULT 0.00,".
                "`invoice_payment_total` decimal(8, 2) NOT NULL DEFAULT 0.00,".
                "`invoice_refund_total` decimal(8, 2) NOT NULL DEFAULT 0.00,".
                "`invoice_net_total` decimal(8, 2) NOT NULL DEFAULT 0.00,".
                "`invoice_net_due` decimal(8, 2) NOT NULL DEFAULT 0.00,".
                "`invoice_create_date` datetime DEFAULT '0000-00-00 00:00:00',".
                "`invoice_paid_date` datetime DEFAULT '0000-00-00 00:00:00',".
                "PRIMARY KEY (`invoice_id`),".
                "KEY `account_id` (`account_id`)".
                ") ENGINE=MyISAM DEFAULT CHARSET=latin1;"
                );
            $this->check_error('install_financials: error creating invoices table: ', $r);
        } else {
            if ($this->column_exists('invoices', 'invoice_amount')) {
                $r = $this->db->query("DROP TABLE IF EXISTS invoices");
                $this->check_error("install_financials: error dropping obsolete invoices table: ", $r);
            }
        }
        foreach (array('invoice_create_date', 'invoice_paid_date') as $col) {
            $r = $this->db->query("ALTER TABLE invoices MODIFY COLUMN `$col` datetime DEFAULT '0000-00-00 00:00:00'");
            $this->check_error("install_financials: error changing $col column default value: ", $r);
        }

        if (!$this->table_exists('products')) {
            $r = $this->db->query(
                "CREATE TABLE `products` (".
                "`product_id` int(11) NOT NULL AUTO_INCREMENT,".
                "`product_parent_id` int(11) NOT NULL DEFAULT 0,".
                "`product_upgrade_id` int(11) NOT NULL DEFAULT 0,".
                "`product_name` varchar(64) NOT NULL DEFAULT '',".
                "`product_enabled` int(1) NOT NULL DEFAULT 1,".
                "`product_type` varchar(16) NOT NULL DEFAULT '',".
                "`product_frequency` varchar(16) NOT NULL DEFAULT '',".
                "`product_count` int(11) NOT NULL DEFAULT 0,".
                "`product_amount` decimal(8, 2) NOT NULL DEFAULT 0.00,".
                "PRIMARY KEY (`product_id`)".
                ") ENGINE=MyISAM DEFAULT CHARSET=latin1;"
                );
            $this->check_error('install_financials: error creating products table: ', $r);
        } else {
            if (!$this->column_exists('products', 'product_upgrade_id')) {
                $r = $this->db->query("ALTER TABLE products ADD `product_upgrade_id` int(11) NOT NULL DEFAULT 0 AFTER `product_parent_id`");
                $this->check_error('install_financials: error adding product_upgrade_id column: ', $r);
            }
        }

        if (!$this->table_exists('account_products')) {
            $r = $this->db->query(
                "CREATE TABLE `account_products` (".
                "`account_product_id` int(11) NOT NULL AUTO_INCREMENT,".
                "`account_id` int(11) NOT NULL,".
                "`product_id` int(11) NOT NULL,".
                "`account_product_added` datetime DEFAULT '0000-00-00 00:00:00',".
                "`account_product_expires` datetime DEFAULT '0000-00-00 00:00:00',".
                "`account_product_removed` datetime DEFAULT '0000-00-00 00:00:00',".
                "PRIMARY KEY (`account_product_id`),".
                "KEY `account_id` (`account_id`),".
                "KEY `product_id` (`product_id`)".
                ") ENGINE=MyISAM DEFAULT CHARSET=latin1;"
                );
            $this->check_error('install_financials: error creating account_products table: ', $r);
        } elseif (!$this->column_exists('account_products', 'account_product_id')) {
            $r = $this->db->query("ALTER TABLE account_products ADD `account_product_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");
            $this->check_error('install_financials: error adding account_product_id column: ', $r);
        }
        foreach (array('account_product_added', 'account_product_expires', 'account_product_removed') as $col) {
            $r = $this->db->query("ALTER TABLE account_products MODIFY COLUMN `$col` datetime DEFAULT '0000-00-00 00:00:00'");
            $this->check_error("install_financials: error changing $col column default value: ", $r);
        }

        if (!$this->table_exists('invoice_products')) {
            $r = $this->db->query(
                "CREATE TABLE `invoice_products` (".
                "`invoice_product_id` int(11) NOT NULL AUTO_INCREMENT,".
                "`invoice_id` int(11) NOT NULL,".
                "`product_id` int(11) NOT NULL,".
                "`product_quantity` int(11) NOT NULL DEFAULT 1,".
                "`discount_id` int(11) NOT NULL,".
                "PRIMARY KEY `invoice_product_id` (`invoice_product_id`),".
                "KEY `invoice_id` (`invoice_id`),".
                "KEY `product_id` (`product_id`),".
                "KEY `discount_id` (`discount_id`)".
                ") ENGINE=MyISAM DEFAULT CHARSET=latin1;"
                );
            $this->check_error('install_financials: error creating invoice_products table: ', $r);
        } else {
            if (!$this->column_exists('invoice_products', 'product_quantity')) {
                $r = $this->db->query("ALTER TABLE invoice_products ADD `product_quantity` int(11) NOT NULL DEFAULT 1 AFTER `product_id`");
                $this->check_error('install_financials: error adding product_quantity column: ', $r);
            }
            if (!$this->column_exists('invoice_products', 'invoice_product_id')) {
                $r = $this->db->query("ALTER TABLE invoice_products ADD `invoice_product_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");
                $this->check_error('install_financials: error adding invoice_product_id column: ', $r);
            }
        }

        if (!$this->table_exists('payments')) {
            $r = $this->db->query(
                "CREATE TABLE `payments` (".
                "`payment_id` int(11) NOT NULL AUTO_INCREMENT,".
                "`invoice_id` int(11) NOT NULL,".
                "`order_id` int(11) NOT NULL,".
                "`payment_amount` decimal(8, 2) NOT NULL DEFAULT 0.00,".
                "`payment_create_date` datetime DEFAULT '0000-00-00 00:00:00',".
                "`payment_refund_date` datetime DEFAULT '0000-00-00 00:00:00',".
                "PRIMARY KEY (`payment_id`),".
                "KEY `invoice_id` (`invoice_id`)".
                ") ENGINE=MyISAM DEFAULT CHARSET=latin1;"
                );
            $this->check_error('install_financials: error creating payments table: ', $r);
        } elseif (!$this->column_exists('payments', 'payment_refund_date')) {
            $r = $this->db->query("ALTER TABLE payments ADD `payment_refund_date` datetime DEFAULT '0000-00-00 00:00:00' AFTER `payment_create_date`");
            $this->check_error('install_financials: error adding payment_refund_date column: ', $r);
        }
        foreach (array('payment_create_date', 'payment_refund_date') as $col) {
            $r = $this->db->query("ALTER TABLE payments MODIFY COLUMN `$col` datetime DEFAULT '0000-00-00 00:00:00'");
            $this->check_error("install_financials: error changing $col column default value: ", $r);
        }

        if (!$this->table_exists('refunds')) {
            $r = $this->db->query(
                "CREATE TABLE `refunds` (".
                "`refund_id` int(11) NOT NULL AUTO_INCREMENT,".
                "`invoice_id` int(11) NOT NULL,".
                "`refund_status` varchar(16) NOT NULL DEFAULT '',".
                "`refund_amount` decimal(8, 2) NOT NULL DEFAULT 0.00,".
                "`refund_create_date` datetime DEFAULT '0000-00-00 00:00:00',".
                "PRIMARY KEY (`refund_id`),".
                "KEY `invoice_id` (`invoice_id`)".
                ") ENGINE=MyISAM DEFAULT CHARSET=latin1;"
                );
            $this->check_error('install_financials: error creating refunds table: ', $r);
        }
        foreach (array('refund_create_date') as $col) {
            $r = $this->db->query("ALTER TABLE refunds MODIFY COLUMN `$col` datetime DEFAULT '0000-00-00 00:00:00'");
            $this->check_error("install_financials: error changing $col column default value: ", $r);
        }
    }

    private function add_msg_send_shortcode()
    {
        if (!$this->column_exists('messages', 'msg_send_shortcode')) {
            $r = $this->db->query("ALTER TABLE messages ADD `msg_send_shortcode` int(1) NOT NULL DEFAULT 0 ");
            $this->check_error('add_msg_send_shortcode: add integration_has_error column: ', $r);
        }
    }
    private function add_comp_msg_send_shortcode()
    {
        if (!$this->column_exists('campaign_steps', 'msg_send_shortcode')) {
            $r = $this->db->query("ALTER TABLE campaign_steps ADD `msg_send_shortcode` int(1) NOT NULL DEFAULT 0 ");
            $this->check_error('add_comp_msg_send_shortcode: add integration_has_error column: ', $r);
        }
    }
    private function install_user_articles()
    {
        if (!$this->table_exists('user_articles')) {
            $r = $this->db->query(
                "CREATE TABLE `user_articles` (".
                "`user_articles_id` int(11) NOT NULL,".

                "`user_id` int(11) NOT NULL,".
                "`article_id` int(11) NOT NULL,".
                "`user_article_value` int(1) NOT NULL DEFAULT '0',".
                "`user_article_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP".

                ") ENGINE=MyISAM DEFAULT CHARSET=latin1;"
                );
            $this->check_error('install_user_articles: error creating table: ', $r);

            $r = $this->db->query(
                "ALTER TABLE `user_articles` ADD PRIMARY KEY (`user_articles_id`), ".
                "ADD FOREIGN KEY fk_conv(user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,".
                "ADD FOREIGN KEY fk_conv(article_id) REFERENCES articles(article_id) ON DELETE CASCADE ON UPDATE CASCADE"
                );
            $this->check_error('install_user_articles: error adding keys: ', $r);

            $r = $this->db->query("ALTER TABLE `user_articles` MODIFY `user_articles_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1");
            $this->check_error('install_user_articles: error adding autoincrement for user_articles_id column: ', $r);

            $r = $this->db->query("ALTER TABLE `user_articles` ADD PRIMARY KEY (`user_articles_id`)");
            $this->check_error('install_user_articles: error adding user_articles key: ', $r);
        } elseif (!$this->index_exists('user_articles', 'PRIMARY')) {
            $r = $this->db->query("ALTER TABLE `user_articles` ADD PRIMARY KEY (`user_articles_id`)");
            $this->check_error('install_user_articles: error adding user_articles key: ', $r);
        }
    }

    private function add_country_columns()
    {
        if (!$this->column_exists('accounts', 'account_country')) {
            $r = $this->db->query("ALTER TABLE accounts ADD `account_country` varchar(2) NOT NULL DEFAULT 'US' AFTER `account_zip`");
            $this->check_error('country: add account_country column: ', $r);

            $r = $this->db->query("UPDATE accounts SET `account_country`='CA' WHERE `account_state` ".
                " IN ('AB', 'BC', 'MB', 'NU', 'NB', 'NF', 'NT', 'NS', 'ON', 'PE', 'QC', 'SK', 'YT')");
            $this->check_error('country: set account_country=CA: ', $r);
        }

        if (!$this->column_exists('connections', 'conn_country')) {
            $r = $this->db->query("ALTER TABLE connections ADD `conn_country` varchar(2) NOT NULL DEFAULT 'US' AFTER `conn_type`");
            $this->check_error('country: add conn_counry column: ', $r);

            $r = $this->db->query("UPDATE connections SET `conn_country`='CA' ".
                "WHERE account_id IN (SELECT account_id FROM accounts WHERE account_country='CA')");
            $this->check_error('country: set conn_country=CA: ', $r);
        }

        if (!$this->column_exists('contacts', 'contact_country')) {
            $r = $this->db->query("ALTER TABLE contacts ADD `contact_country` varchar(2) NOT NULL DEFAULT 'US' AFTER `contact_zip`");
            $this->check_error('country: add contact_country column: ', $r);

            $r = $this->db->query("UPDATE contacts SET `contact_country`='CA' WHERE `contact_state` ".
                " IN ('AB', 'BC', 'MB', 'NU', 'NB', 'NF', 'NT', 'NS', 'ON', 'PE', 'QC', 'SK', 'YT')");
            $this->check_error('country: set contact_country=CA: ', $r);
        }
    }

    private function issue_696_fix()
    {
        $num_todo = 5000;
        $num_errors = $num_ignored = $num_invalid = $num_updated = $num_withname = 0;

        if (($fp = fopen("contacts.csv", "r")) !== false) {
            while (($row = fgetcsv($fp, 1000, ",")) !== false) {
                if ($row[0] == 'contact_id') {
                    continue;
                }

                $data = array('contact_id'=>$row[0]);
                $r = $this->db->get_where('contacts', $data);

                $contact = $r && $r->num_rows() > 0 ? $r->result()[0] : false;
                if ($contact && empty($contact->contact_first_name) && empty($contact->contact_last_name)) {
                    error_log($contact->contact_id);

                    if (empty($contact->contact_first_name) && empty($contact->contact_last_name)) {
                        $this->db->where('contact_id', $row[0]);
                        $this->db->where('group_id', $row[1]);
                        if ($this->db->update('group_contacts', array('group_contact_create_date'=>$contact->contact_create_date))) {
                            if ($this->db->affected_rows() > 0) {
                                $num_updated++;
                            } else {
                                $num_ignored++;
                            }
                        } else {
                            $num_errors++;
                        }
                    } else {
                        $num_withname++;
                    }
                } else {
                    $num_invalid++;
                }

                if (!--$num_todo) {
                    break;
                }
            }
        }
        fclose($fp);

        error_log('num_invalid: '.$num_invalid);
        error_log('num_withname: '.$num_withname);
        error_log('num_ignored: '.$num_ignored);
        error_log('num_changed: '.$num_updated);
        error_log('num_errors: '.$num_errors);
    }

    private function issue_696_upgrade()
    {
        /* This can run for a while, including dropping the group_id_pending column. */
        set_time_limit(600);

        if (!$this->column_exists('contacts', 'contact_pending_email')) {
            $r = $this->db->query("ALTER TABLE contacts ADD `contact_pending_email` int(1) NOT NULL DEFAULT 0 AFTER `contact_active`");
            $this->check_error('contact: add contact_pending_email column: ', $r);
        }

        /* If the contacts group_id_pending column does not exist, nothing further need be checked/done. */
        if (!$this->column_exists('contacts', 'group_id_pending')) {
            return;
        }

        /* Query to get contact with a group_id_pending value whether there is not already a
         * matching group_contacts record.
         * (Yes, some contacts are also pending in groups they are already members of).
         */
        $sub_sql =
               "LEFT JOIN groups g ON c.group_id_pending=g.group_id ".
               "LEFT JOIN group_contacts gc ON gc.contact_id=c.contact_id AND gc.group_id=g.group_id ".
               "WHERE group_id_pending != 0 AND g.group_id IS NOT NULL AND gc.group_contact_id IS NULL ";
        $cnt_sql = "SELECT COUNT(*) AS count FROM contacts c ".$sub_sql;
        $srch_sql = "SELECT c.account_id, c.contact_id, c.contact_email, g.group_id FROM contacts c ".$sub_sql."ORDER BY c.contact_id ASC LIMIT 1000";
        do {
            $cids = array();
            $pids = array();

            $r = $this->db->query($cnt_sql);
            $cnt = $r ? $r->result()[0]->count : false;
            if ($cnt !== false) {
                error_log('issue_696_upgrade: '.$cnt.' contact(s) remaining to be migrated.');
            } else {
                die('issue_696_upgrade: error getting migration count.');
            }

            $r = $this->db->query($srch_sql);
            foreach ($r->result_array() as $row) {
                $this->db->set('group_contact_create_date', 'NOW()', false);
                $this->db->insert('group_contacts', array(
                    'group_id'=>$row['group_id'],
                    'contact_id'=>$row['contact_id'],
                    'group_contact_by_keyword'=>1,
                    ));

                if ($this->db->insert_id()) {
                    $cids[] = $row['contact_id'];
                }
                if (empty($row['contact_email'])) {
                    $pids[] = $row['contact_id'];
                }
            }

            /* Translate the old group_id_pending to contact_pending_email; this isn't critical but it's a useful hint */
            $this->db->query("UPDATE contacts SET contact_pending_email=1 WHERE contact_id IN (".implode(',', $pids).")");

            /* It doesn't matter if this fails as our main query will ignore contacts we have created
             * group_contacts for.  We only do this to make the main query faster (and for progress).
             */
            $this->db->query("UPDATE contacts SET group_id_pending=0 WHERE contact_id IN (".implode(',', $cids).")");
        } while (!empty($cids));

        $r = $this->db->query("ALTER TABLE contacts DROP COLUMN `group_id_pending`");
        $this->check_error('issue_696_upgrade: drop group_id_pending column: ', $r);
    }

    private function get_phone_psql($field)
    {
        return
            "CONCAT(".
            "IF (SUBSTRING($field, 01, 1) REGEXP '[0-9]', SUBSTRING($field, 01, 1), ''),".
            "IF (SUBSTRING($field, 02, 1) REGEXP '[0-9]', SUBSTRING($field, 02, 1), ''),".
            "IF (SUBSTRING($field, 03, 1) REGEXP '[0-9]', SUBSTRING($field, 03, 1), ''),".
            "IF (SUBSTRING($field, 04, 1) REGEXP '[0-9]', SUBSTRING($field, 04, 1), ''),".
            "IF (SUBSTRING($field, 05, 1) REGEXP '[0-9]', SUBSTRING($field, 05, 1), ''),".
            "IF (SUBSTRING($field, 06, 1) REGEXP '[0-9]', SUBSTRING($field, 06, 1), ''),".
            "IF (SUBSTRING($field, 07, 1) REGEXP '[0-9]', SUBSTRING($field, 07, 1), ''),".
            "IF (SUBSTRING($field, 08, 1) REGEXP '[0-9]', SUBSTRING($field, 08, 1), ''),".
            "IF (SUBSTRING($field, 09, 1) REGEXP '[0-9]', SUBSTRING($field, 09, 1), ''),".
            "IF (SUBSTRING($field, 10, 1) REGEXP '[0-9]', SUBSTRING($field, 10, 1), ''),".
            "IF (SUBSTRING($field, 11, 1) REGEXP '[0-9]', SUBSTRING($field, 11, 1), ''),".
            "IF (SUBSTRING($field, 12, 1) REGEXP '[0-9]', SUBSTRING($field, 12, 1), ''),".
            "IF (SUBSTRING($field, 13, 1) REGEXP '[0-9]', SUBSTRING($field, 13, 1), ''),".
            "IF (SUBSTRING($field, 14, 1) REGEXP '[0-9]', SUBSTRING($field, 14, 1), ''),".
            "IF (SUBSTRING($field, 15, 1) REGEXP '[0-9]', SUBSTRING($field, 15, 1), '')".
            ")";
    }




    private function fix_598()
    {
        return false; // DISABLED: This code should not be run more than once

        $contact_ids = array(
            3669, 8289, 9955, 10519, 12742, 17155, 19958, 28698, 29594, 30329,
            30561, 34214, 39361, 45625, 48737, 48739, 49113, 57346, 59079, 59133,
            59828, 61489, 61524, 65703, 67593, 68183, 102844, 110026, 112417, 112991,
            113238, 113481, 113482, 114167, 114177, 114181, 114191, 114198, 114210, 114212,
            114221, 114222, 114224, 114225, 114370, 114696, 114700, 114701, 114706, 114713,
            114732, 115232, 115968, 119819, 120370, 120602, 124571, 126475, 127486, 128891,
            129763, 129764, 129766, 129769, 129772, 129774, 129775, 129777, 158878, 159347,
            163756, 168091, 174242, 177409, 177412, 177414, 177416, 177421, 177432, 178021,
            180218, 180308, 184324, 184509, 185226, 186526, 188228, 188683, 189524, 189707,
            191334, 191429, 191563, 194415, 194839, 194840, 194841, 195003, 195592, 195617,
            195664, 195692, 195875, 196270, 196279, 196286, 196551, 197155, 199104, 202117,
            202213, 202215, 204918, 217884, 221982, 222253, 222254, 222256, 222677, 222678,
            223486, 223546, 224763, 224772, 224993, 229582, 232590, 232721, 232803, 232846,
            236082, 236525, 240998, 241195, 254074, 254078, 254308, 255038, 256521, 257571,
            258587, 263803, 267179, 268375, 271587, 276588, 276590, 276591, 276613, 276714,
            277154, 281776, 284289, 284423, 286018, 286481, 289016, 289073, 289839, 293539,
            297300, 297301, 298460, 298998, 300357, 302490, 302601, 304536, 304542, 305120,
            305468, 305667, 305927, 306499, 306695, 306828, 310185, 311951, 311977, 316367,
            320615, 320617, 320919, 320991, 322206, 328152, 329898, 333557, 336791, 337212,
            337333, 337967, 338907, 339112, 339163, 339507, 342713, 343028, 347866, 349981,
            349991, 350003, 352388, 352484, 359011, 359116, 360131, 362138, 362158, 362220,
            365174, 366425, 366431, 367482, 368175, 369242, 369532, 371230, 371235, 376110,
            376390, 377228, 378576, 379246, 387589, 390087, 393610, 394101, 394269, 394952,
            396229, 396880, 398811, 399384, 402942, 405090, 407287, 413061, 416054, 416996,
            421622, 427473, 427572, 431026, 434889, 439363, 442057, 442076, 448917, 449041,
            449318, 449404, 449422, 450555, 450870, 450880, 450891, 454425, 454499, 458527,
            462923, 463202, 464843, 465311, 467922, 469337, 474468, 477008, 477112, 477717,
            480331, 481607, 482340, 482407, 486948, 499052, 501788, 501818, 504031, 513867,
            515525, 521384, 528632, 529644, 529683, 529685, 532675, 540205, 546490, 548298,
            548377, 549515, 566505, 570959, 574997, 582024, 595445, 609006, 634449, 643974,
            659303, 666898, 673348, 673349, 676429, 688034,
            );
        $contact_ids = array(); //DISABLED

        $poached = array(
            3669=>array(218, 822), 8289=>array(368, 2615), 9955=>array(394, 251), 10519=>array(453, 2844),
            12742=>array(422, 2559), 17155=>array(544, 3216), 19958=>array(663, 310), 28698=>array(635, 2833),
            29594=>array(579, 3059), 30329=>array(579, 3059), 30561=>array(579, 3059), 34214=>array(728, 3270),
            39361=>array(802, 1167), 45625=>array(714, 3216), 48737=>array(846, 2272), 48739=>array(846, 2272),
            49113=>array(846, 2272), 57346=>array(702, 3486), 59079=>array(912, 3290), 59133=>array(912, 3290),
            59828=>array(887, 782), 61489=>array(629, 2345), 61524=>array(912, 3290), 65703=>array(940, 1146),
            67593=>array(993, 3216), 68183=>array(982, 831), 102844=>array(881, 3816), 110026=>array(504, 2648),
            112417=>array(1038, 3331), 112991=>array(912, 3290), 113238=>array(145, 3216), 113481=>array(943, 2544),
            113482=>array(943, 2544), 114167=>array(943, 2544), 114177=>array(943, 2544), 114181=>array(943, 2544),
            114191=>array(943, 2544), 114198=>array(943, 2544), 114210=>array(943, 2544), 114212=>array(943, 2544),
            114221=>array(943, 2544), 114222=>array(943, 2544), 114224=>array(943, 2544), 114225=>array(943, 2544),
            114370=>array(943, 2544), 114696=>array(943, 2544), 114700=>array(943, 2544), 114701=>array(943, 2544),
            114706=>array(943, 2544), 114713=>array(943, 2544), 114732=>array(943, 2544), 115232=>array(943, 2544),
            115968=>array(943, 2544), 119819=>array(943, 2544), 120370=>array(1088, 831), 120602=>array(943, 2844),
            124571=>array(1004, 3244), 126475=>array(1079, 3258), 127486=>array(943, 1260), 128891=>array(870, 2930),
            129763=>array(1177, 3374), 129764=>array(1177, 3374), 129766=>array(1177, 3374), 129769=>array(1177, 3374),
            129772=>array(1177, 3374), 129774=>array(1177, 3374), 129775=>array(1177, 3374), 129777=>array(1177, 3374),
            158878=>array(943, 2544), 159347=>array(617, 1747), 163756=>array(943, 2544), 168091=>array(943, 2544),
            174242=>array(1177, 3374), 177409=>array(943, 2544), 177412=>array(943, 2544), 177414=>array(943, 2544),
            177416=>array(943, 2544), 177421=>array(943, 2544), 177432=>array(943, 2544), 178021=>array(943, 2544),
            180218=>array(943, 2544), 180308=>array(943, 1260), 184324=>array(1237, 1698), 184509=>array(854, 2590),
            185226=>array(944, 1683), 186526=>array(1177, 3374), 188228=>array(938, 586), 188683=>array(943, 2544),
            189524=>array(943, 2544), 189707=>array(1072, 401), 191334=>array(943, 2544), 191429=>array(943, 2544),
            191563=>array(1225, 2647), 194415=>array(943, 2544), 194839=>array(943, 2544), 194840=>array(943, 2544),
            194841=>array(943, 2544), 195003=>array(637, 2523), 195592=>array(1237, 1698), 195664=>array(1237, 1698),
            195875=>array(1237, 1698), 196270=>array(943, 2544), 196279=>array(943, 2544), 196286=>array(943, 2544),
            196551=>array(943, 2544), 197155=>array(943, 2544), 199104=>array(372, 3331), 202117=>array(943, 2544),
            202213=>array(1032, 3380), 202215=>array(1032, 3380), 204918=>array(943, 2544), 217884=>array(943, 1260),
            221982=>array(1392, 1870), 222253=>array(943, 2544), 222254=>array(943, 2544), 222256=>array(943, 2544),
            222677=>array(943, 2544), 222678=>array(943, 2544), 223486=>array(943, 2544), 223546=>array(1365, 2073),
            224763=>array(1250, 3646), 224772=>array(943, 2544), 224993=>array(943, 2544), 229582=>array(1340, 851),
            232590=>array(911, 788), 232721=>array(1534, 1237), 232803=>array(1237, 1698), 232846=>array(1698, 1237),
            236082=>array(1358, 3709), 236525=>array(943, 2544), 240998=>array(943, 1260), 241195=>array(1669, 851),
            254074=>array(943, 2544), 254078=>array(943, 2544), 254308=>array(1543, 2696), 255038=>array(1705, 3216),
            256521=>array(1701, 3484), 257571=>array(943, 2544), 258587=>array(1680, 2282), 263803=>array(1209, 3349),
            267179=>array(1245, 1167), 268375=>array(1761, 2981), 271587=>array(1534, 3258), 276588=>array(943, 2544),
            276590=>array(943, 2544), 276591=>array(943, 2544), 276613=>array(943, 2544), 276714=>array(1836, 1705),
            277154=>array(943, 2544), 281776=>array(943, 2544), 284289=>array(1572, 586), 284423=>array(1569, 788),
            286018=>array(1874, 1705), 286481=>array(851, 2948), 289016=>array(1839, 1167), 289073=>array(1839, 1167),
            289839=>array(1728, 492), 293539=>array(1591, 3255), 297300=>array(1973, 2345), 297301=>array(1973, 2345),
            298460=>array(1728, 3646), 298998=>array(1728, 3646), 300357=>array(1728, 2700), 302490=>array(1728, 2700),
            302601=>array(1728, 3553), 304536=>array(943, 2544), 304542=>array(943, 2544), 305120=>array(943, 2544),
            305468=>array(943, 2544), 305667=>array(1874, 1705), 305927=>array(802, 1167), 306499=>array(943, 2544),
            306695=>array(1534, 1237), 306828=>array(943, 2544), 310185=>array(1871, 1870), 311951=>array(1896, 1705),
            311977=>array(1896, 1705), 316367=>array(1960, 1705), 320615=>array(943, 2544), 320617=>array(943, 2544),
            320919=>array(295, 2783), 320991=>array(943, 2544), 322206=>array(1973, 2345), 328152=>array(943, 2544),
            329898=>array(1960, 1705), 333557=>array(1568, 2647), 336791=>array(943, 2544), 337212=>array(943, 2544),
            337333=>array(1996, 3392), 337967=>array(1973, 2345), 338907=>array(1812, 1870), 339112=>array(1812, 1870),
            339163=>array(1812, 1870), 339507=>array(1660, 2575), 342713=>array(2168, 1966), 343028=>array(943, 2544),
            347866=>array(1133, 492), 349981=>array(2044, 3149), 349991=>array(2044, 3149), 350003=>array(2044, 3149),
            352388=>array(2094, 2073), 352484=>array(1452, 3367), 359011=>array(1870, 1812), 359116=>array(1146, 1237),
            360131=>array(2062, 2844), 362138=>array(1960, 1705), 362158=>array(1960, 1705), 362220=>array(1960, 1705),
            365174=>array(2271, 2844), 366425=>array(822, 2502), 366431=>array(822, 2240, 2345), 367482=>array(1088, 831),
            368175=>array(1264, 2575), 369242=>array(1698, 1146), 369532=>array(1960, 1705), 371230=>array(1960, 1705),
            371235=>array(606, 2401), 376110=>array(1814, 3380), 376390=>array(2167, 3483), 377228=>array(2167, 44),
            378576=>array(1960, 1705), 379246=>array(2302, 1760), 387589=>array(2342, 2345), 390087=>array(822, 2405),
            393610=>array(486, 2575), 394101=>array(1960, 1705), 394269=>array(1874, 1705), 394952=>array(1133, 492),
            396229=>array(1291, 3816), 396880=>array(189, 3270), 398811=>array(2382, 401), 399384=>array(1874, 1705),
            402942=>array(2389, 1705), 405090=>array(1895, 1705), 407287=>array(1534, 1698), 413061=>array(1874, 1705),
            416054=>array(1960, 1705), 416996=>array(1960, 1705), 421622=>array(2428, 3816), 427473=>array(1534, 1237),
            427572=>array(2557, 2992), 431026=>array(1896, 1705), 434889=>array(1896, 1705), 439363=>array(1591, 2930),
            442057=>array(1591, 3255), 442076=>array(1591, 3255), 448917=>array(1874, 1705), 449041=>array(1874, 1705),
            449318=>array(1960, 1705), 449404=>array(1960, 1705), 449422=>array(1960, 1705), 450555=>array(1960, 1705),
            450870=>array(1960, 1705), 450880=>array(1960, 1705), 450891=>array(1960, 1705), 454425=>array(1121, 2143),
            454499=>array(1121, 2143), 458527=>array(2617, 2839), 462923=>array(2294, 3154), 463202=>array(1960, 1705),
            464843=>array(2120, 3151), 465311=>array(1960, 1705), 467922=>array(2656, 2073), 469337=>array(2663, 3650),
            474468=>array(1751, 1682), 477008=>array(387, 1549), 477112=>array(44, 822), 477717=>array(2724, 3367),
            480331=>array(387, 1549), 481607=>array(2776, 1682), 482340=>array(2773, 2544), 482407=>array(2773, 2544),
            486948=>array(2049, 2783),
            );

        $this->load->model('contacts_model');
        $this->load->model('groups_model');

        foreach ($contact_ids as $contact_id) {
            $account_ids = isset($poached[$contact_id]) ? $poached[$contact_id] : array();

            $result = $this->contacts_model->get_contact($contact_id);
            $contact = $result ? $result->row() : false;
            if (!$contact) {
                error_log('fix_598: invalid contact: '.$contact_id);
                continue;
            }
            if (!$contact->contact_active) {
                continue;
            }

            $this->db->select('gc.*,g.account_id');
            $this->db->from('group_contacts gc');
            $this->db->join('groups g', 'gc.group_id=g.group_id', 'inner');
            $this->db->where(array('contact_id'=>$contact_id));
            $group_contacts = $this->db->get()->result();
            if (empty($group_contacts)) {
                $group_contacts = array();
            }

            if (!in_array($contact->account_id, $account_ids)) {
                $account_ids[] = $contact->account_id;
            }

            foreach ($group_contacts as $group_contact) {
                if (!in_array($group_contact->account_id, $account_ids)) {
                    $account_ids[] = $group_contact->account_id;
                }
            }

            if (count($account_ids) > 1) {
                $data = array();
                $fields = array(
                    'contact_first_name', 'contact_last_name', 'contact_email',
                    'contact_phone', 'contact_mobile', 'contact_mobile_type', 'contact_mobile_carrier',
                    'contact_address1', 'contact_address2', 'contact_city', 'contact_state', 'contact_zip',
                    'contact_comments', 'contact_optout_email', 'contact_optout_sms',
                    'contact_create_date', 'contact_reply_date', 'contact_stamp'
                    );
                foreach ($fields as $field) {
                    $data[$field] = $contact->$field;
                }

                foreach ($account_ids as $account_id) {
                    if ($contact->account_id != $account_id) {
                        $data['account_id'] = $account_id;

                        $new_contact_id = $this->contacts_model->add_contact($data, array(), false, false);
                        $new_contact = $new_contact_id ? $this->contacts_model->get_contact($new_contact_id)->row() : false;
                        if ($new_contact) {
                            error_log('fix_598: added contact: '.$contact_id.' -> '.$new_contact_id.' ('.$account_id.')');
                        } else {
                            error_log('fix_598: error duplicating contact: '.$contact_id.': '.var_export($data, 1));
                            return;
                        }

                        foreach ($group_contacts as $group_contact) {
                            if ($group_contact->account_id == $account_id) {
                                $this->db->where('group_contact_id', $group_contact->group_contact_id);
                                $this->db->update('group_contacts', array('contact_id'=>$new_contact_id));

                                error_log('fix_598: remapped group: '.$group_contact->group_id.': '.$contact_id.' -> '.$new_contact_id);
                            }
                        }
                    }
                }
            }
        }
    }

    public function fix_1354()
    {
        $account_data = $this->session->userdata('account_data');
        if (empty($account_data) || $account_data->account_type != 0) {
            die('Authorization error.');
        }

        $sql =
            "SELECT DISTINCT cv1.conv_id,cv1.contact_id,COUNT(DISTINCT m.msg_id) AS num ".
            "FROM conversations cv1 ".
            "LEFT JOIN conversations cv2 ON cv1.contact_id=cv2.contact_id ".
            "LEFT JOIN messages m ON m.conv_id=cv1.conv_id ".
            "WHERE cv1.conv_id != cv2.conv_id AND cv1.contact_id != 0 ".
            "GROUP BY cv1.conv_id ".
            "ORDER BY cv1.contact_id ASC, num DESC";
        $result = $this->db->query($sql);
        $tuples = $result ? $result->result() : array();

        $conversations = array();
        foreach ($tuples as $tuple) {
            if (!isset($conversations[$tuple->contact_id])) {
                $conversations[$tuple->contact_id] = array('conv_id'=>$tuple->conv_id, 'num'=>$tuple->num);
            } elseif ($tuple->num < 2) {
                error_log('delete: '.var_export($tuple, 1));

                /* Delete receipts for duplicate messages */
                $sql =
                    "DELETE r FROM receipts r ".
                    "LEFT JOIN messages m ON m.msg_id=r.msg_id ".
                    "WHERE m.conv_id=$tuple->conv_id";
                $result = $this->db->query($sql);

                /* Delete duplicate message */
                $sql =
                    "DELETE m FROM messages m ".
                    "WHERE m.conv_id=$tuple->conv_id";
                $result = $this->db->query($sql);

                /* Delete duplicate conversation */
                $sql =
                    "DELETE c FROM conversations c ".
                    "WHERE conv_id=$tuple->conv_id";
                $result = $this->db->query($sql);
            } else {
                error_log('anomoly: '.var_export($tuple, 1));
            }
        }

        echo count($tuples);
    }

    private function fixup_campaign_messages()
    {
        /* Repair missing account_ids by copying from contact record */
        $r = $this->db->query(
            "UPDATE campaign_messages cm ".
            "JOIN contacts c ON cm.contact_id=c.contact_id ".
            "SET cm.account_id=c.account_id, cm.campaign_msg_stamp=cm.campaign_msg_stamp ".
            "WHERE cm.account_id=0"
            );
        $this->check_error(__FUNCTION__.': error fixing up missing account id from contact table: ', $r);

        /* Repair missing account_ids by copying from campaign step/group record */
        $r = $this->db->query(
            "UPDATE campaign_messages cm ".
            "JOIN campaign_steps cs ON cm.campaign_step_id=cs.campaign_step_id ".
            "JOIN groups g ON g.group_id=cs.campaign_id ".
            "SET cm.account_id=g.account_id, cm.campaign_msg_stamp=cm.campaign_msg_stamp ".
            "WHERE cm.account_id=0"
            );
        $this->check_error(__FUNCTION__.': error fixing up missing account id from contact table: ', $r);

        /* Some rows will still have no account_id, but we have no way to fix those up. */

        error_log('fixup_campaign_messages: successfully fixed up campaign messages.');
    }

    private function fixup_contact_countries()
    {
        /* Set contact country to 'CA' for any contacts without a state where the church is in Canada.
         */
        $this->db->set('contact_country', 'CA', true);
        $this->db->where("(contact_state IS NULL OR contact_state='')", null, false);
        $this->db->where('account_country', 'CA');
        $this->db->where('contact_country !=', 'CA');
        $r = $this->db->update('contacts JOIN accounts ON contacts.account_id=accounts.account_id');
        $this->check_error(__FUNCTION__.': error fixing up contact countries: ', $r);

        error_log('fixup_contact_countries: successfully fixed up '.$this->db->affected_rows().' contact countries.');
    }

    private function fixup_infusionsoft_emails()
    {
        $this->load->model('accounts_model');

        $this->load->helper('infusionsoft-loader');
        $isApp = get_infusionsoft_service();

        $limit = 1000;
        $ttl_contacts = $page = 0;
        $contacts = $names = $phones = array();
        do {
            $data = $isApp->dsQuery('Contact', $limit, $page++, array('Id'=>'%'), array('Id', 'Email', 'Phone1', 'Phone2', 'FirstName', 'LastName', 'City', 'State'));
            foreach ($data as $contact) {
                $contacts[$contact['Id']] = !empty($contact['Email']) ? $contact['Email'] : '';
                if (!empty($contact['LastName']) && !empty($contact['FirstName'])
                    && !empty($contact['City']) && !empty($contact['State'])) {
                    $ln = strtolower($contact['LastName']);
                    if (!isset($names[$ln])) {
                        $names[$ln] = array();
                    }
                    $names[$ln][] = array($contact['Id'], strtolower($contact['FirstName']), strtolower($contact['City']), strtolower($contact['State']));

                    if (isset($contact['Phone1'])) {
                        $phone = preg_replace('/\D/', '', $contact['Phone1']);
                        if (!empty($phone)) {
                            $phones[$phone] = $contact['Id'];
                        }
                    }
                    if (isset($contact['Phone2'])) {
                        $phone = preg_replace('/\D/', '', $contact['Phone2']);
                        if (!empty($phone)) {
                            $phones[$phone] = $contact['Id'];
                        }
                    }
                }
            }

            $ttl_contacts += count($data);
            error_log('fixup_infusionsoft_emails: infusionsoft contacts read: '.$ttl_contacts);
        } while (count($data) >= $limit);

        $sql  = "SELECT account_id, account_email, account_phone, account_mobile, account_first_name, account_last_name, account_city, account_state, member_id FROM accounts ";
        $sql .= "WHERE member_id != 0 ";
        $sql .= "ORDER BY account_id ASC ";

        $num_total = $num_fixed = 0;
        $unmatched = array();
        $page = 0;
        do {
            $tsql = $sql . "LIMIT ".($page++*1000).",1000";
            $accounts = $this->db->query($tsql);
            if (!$accounts) {
                die('fixup_infusionsoft_emails: error: '.$this->db->last_query());
            }

            foreach ($accounts->result_array() as $account) {
                if ($account['account_id'] === 2 || $account['account_id'] == 44) {
                    continue;
                }

                $num_total++;

                if (isset($contacts[$account['member_id']])) {
                    if ($contacts[$account['member_id']] != $account['account_email']) {
                        $num_fixed++;
                        error_log('fix: email sync: '.$account['account_id'].': '.$account['member_id'].': '.$account['account_email']);

                        $isApp->updateContact($account['member_id'], array('Email'=>$account['account_email']));
                    }
                } elseif (($contact_id = array_search($account['account_email'], $contacts)) !== false) {
                    $num_fixed++;
                    error_log('fix: reset member_id: '.$account['account_id'].': '.$account['member_id'].' <- '.$contact_id.': '.$account['account_email']);

                    $this->accounts_model->update_account($account['account_id'], array('member_id'=>$contact_id));
                } else {
                    $afn = strtolower($account['account_first_name']);
                    $aln = strtolower($account['account_last_name']);
                    $aci = strtolower($account['account_city']);
                    $ast = strtolower($account['account_state']);
                    $aph1 = preg_replace('/\D/', '', $account['account_phone']);
                    $aph2 = preg_replace('/\D/', '', $account['account_mobile']);

                    $contact_id = false;
                    if (isset($names[$aln])) {
                        foreach ($names[$aln] as $values) {
                            if ($values[1] == $afn && $values[2] == $aci && $values[3] == $ast) {
                                $contact_id = $values[0];
                                break;
                            } elseif (isset($phones[$aph1])) {
                                $contact_id = $phones[$aph1];
                                break;
                            } elseif (isset($phones[$aph2])) {
                                $contact_id = $phones[$aph2];
                                break;
                            }
                        }
                    }

                    if ($contact_id) {
                        $num_fixed++;
                        error_log('fix: contact match: '.$account['account_id'].': now '.$contact_id.': '.
                            $contacts[$contact_id].' <- '.$account['account_email']);

                        $this->accounts_model->update_account($account['account_id'], array('member_id'=>$contact_id));
                        if ($contacts[$contact_id] != $account['account_email']) {
                            $isApp->updateContact($contact_id, array('Email'=>$account['account_email']));
                        }
                    } else {
                        $unmatched[] = $account['account_id'];
                    }
                }
            }
        } while ($accounts->num_rows() >= 1000);

        error_log("fixup_infusionsoft_emails: stats: # accounts=$num_total, # fixed=$num_fixed, # unfixed=".count($unmatched));
        error_log('fixup_infusionsoft_emails: unfixed account IDs: '.implode(', ', $unmatched));
    }

    private function fixup_phone_numbers()
    {
        $sql = "UPDATE accounts SET account_mobile=".$this->get_phone_psql('account_mobile');
        $r = $this->db->query($sql);
        $this->check_error(__FUNCTION__.': error fixing up account_mobile', $r);

        $sql = "UPDATE accounts SET account_phone=".$this->get_phone_psql('account_phone');
        $r = $this->db->query($sql);
        $this->check_error(__FUNCTION__.': error fixing up account_phone ', $r);

        $sql = "UPDATE contacts SET contact_mobile=".$this->get_phone_psql('contact_mobile');
        $r = $this->db->query($sql);
        $this->check_error(__FUNCTION__.': error fixing up contact_mobile', $r);

        $sql = "UPDATE contacts SET contact_phone=".$this->get_phone_psql('contact_phone');
        $r = $this->db->query($sql);
        $this->check_error(__FUNCTION__.': error fixing up contact_phone ', $r);
    }

    private function install_addons()
    {
        if (!$this->table_exists('addons')) {
            $r = $this->db->query(
                "CREATE TABLE `addons` (".
                "`addon_id` int(11) NOT NULL,".

                "`addon_name` varchar(100) NOT NULL DEFAULT '',".
                "`addon_description` varchar(255) NOT NULL DEFAULT '',".
                "`addon_price` DECIMAL(10,5) NOT NULL DEFAULT '0.00',".
                "`addon_product_id` int(11) NOT NULL DEFAULT '0',".
                "`addon_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP".

                ") ENGINE=MyISAM DEFAULT CHARSET=latin1;"
                );
            $this->check_error('install_addons: error creating addons table: ', $r);

            $r = $this->db->query(
                "ALTER TABLE `addons` ADD PRIMARY KEY (`addon_id`), ".
                "ADD KEY `addon_stamp` (`addon_stamp`)"
                );
            $this->check_error('install_addons: error adding addons keys: ', $r);

            $r = $this->db->query("ALTER TABLE `addons` MODIFY `addon_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1");
            $this->check_error('install_addons: error adding autoincrement for addon_id column: ', $r);
        }

        if (!$this->table_exists('account_addons')) {
            $r = $this->db->query(
                "CREATE TABLE `account_addons` (".
                "`account_addon_id` int(11) NOT NULL,".
                "`account_id` int(11) NOT NULL,".
                "`addon_id` int(11) NOT NULL,".

                "`account_addon_enabled` int(1) NOT NULL DEFAULT '1',".
                "`account_addon_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP".

                ") ENGINE=MyISAM DEFAULT CHARSET=latin1;"
                );
            $this->check_error('install_addons: error creating account_addons table: ', $r);

            $r = $this->db->query(
                "ALTER TABLE `account_addons` ADD PRIMARY KEY (`account_addon_id`),".
                "ADD FOREIGN KEY fk_account(account_id) REFERENCES accounts(account_id) ON DELETE CASCADE ON UPDATE CASCADE,".
                "ADD FOREIGN KEY fk_addon(addon_id) REFERENCES addons(addon_id) ON DELETE CASCADE ON UPDATE CASCADE,".
                "ADD KEY `account_addon_stamp` (`account_addon_stamp`)"
                );
            $this->check_error('install_addons: error adding account_addons keys: ', $r);

            $r = $this->db->query("ALTER TABLE `account_addons` MODIFY `account_addon_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1");
            $this->check_error('install_addons: error adding autoincrement for account_addon_id column: ', $r);
        }
    }

    private function install_conversations()
    {
        if ($this->column_exists('conversations', 'conv_send_time')) {
            $r = $this->db->query(
                "ALTER TABLE `conversations` RENAME `conversations_legacy`"
                );
            $this->check_error('install_conversations: error renaming legacy table: ', $r);

            $r = $this->db->query(
                "CREATE TABLE `conversations` (".
                "`conv_id` int(11) NOT NULL,".

                "`account_id` int(11) NOT NULL,".
                "`contact_id` int(11) DEFAULT NULL,".
                "`group_id` int(11) DEFAULT NULL,".

                "`conv_archived` int(1) NOT NULL DEFAULT 0,".
                "`conv_deleted` int(1) NOT NULL DEFAULT 0".

                ") ENGINE=MyISAM DEFAULT CHARSET=latin1;"
                );
            $this->check_error('install_conversations: error creating table: ', $r);

            $r = $this->db->query(
                "ALTER TABLE `conversations` ADD PRIMARY KEY (`conv_id`), ".
                "ADD FOREIGN KEY fk_account(account_id) REFERENCES accounts(account_id) ON DELETE CASCADE ON UPDATE CASCADE,".
                "ADD FOREIGN KEY fk_contact(contact_id) REFERENCES contacts(contact_id) ON DELETE CASCADE ON UPDATE CASCADE,".
                "ADD FOREIGN KEY fk_group(group_id) REFERENCES groups(group_id) ON DELETE CASCADE ON UPDATE CASCADE"
                );
            $this->check_error('install_conversations: error adding keys: ', $r);

            $r = $this->db->query("ALTER TABLE `conversations` MODIFY `conv_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1");
            $this->check_error('install_conversations: error adding autoincrement for conv_id column: ', $r);
        }
    }

    private function install_statistics()
    {
        if (!$this->table_exists('statistics')) {
            $r = $this->db->query(
                "CREATE TABLE `statistics` (".
                "`stat_id` int(11) NOT NULL,".

                "`account_id` int(11) NOT NULL,".

                "`stat_sms_count` int(11) NOT NULL DEFAULT '0',".
                "`stat_email_count` int(11) NOT NULL DEFAULT '0',".
                "`stat_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP".

                ") ENGINE=MyISAM DEFAULT CHARSET=latin1;"
                );
            $this->check_error('install_statistics: error creating table: ', $r);

            $r = $this->db->query(
                "ALTER TABLE `statistics` ADD PRIMARY KEY (`stat_id`), ".
                "ADD FOREIGN KEY fk_account(account_id) REFERENCES accounts(account_id) ON DELETE CASCADE ON UPDATE CASCADE,".
                "ADD KEY `stat_stamp` (`stat_stamp`)"
                );
            $this->check_error('install_statistics: error adding keys: ', $r);

            $r = $this->db->query("ALTER TABLE `statistics` MODIFY `stat_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1");
            $this->check_error('install_statistics: error adding autoincrement for stat_id column: ', $r);
        }
    }

    private function install_messages()
    {
        if (!$this->table_exists('messages')) {
            $r = $this->db->query(
                "CREATE TABLE `messages` (".
                "`msg_id` int(11) NOT NULL,".

                "`campaign_step_id` int(11) NOT NULL,".
                "`conv_id` int(11) NOT NULL,".
                "`user_id` int(11) DEFAULT NULL,".

                "`msg_name` varchar(100) DEFAULT NULL,".
                "`msg_type` char(8) NOT NULL DEFAULT 'sms',".
                "`msg_from_email` varchar(100) DEFAULT NULL,".
                "`msg_from_name` varchar(100) DEFAULT NULL,".
                "`msg_to_email` varchar(100) DEFAULT NULL,".
                "`msg_subject` varchar(100) DEFAULT NULL,".
                "`msg_content` text,".
                "`msg_media_type` varchar(64) NOT NULL DEFAULT '',".
                "`msg_media_url` varchar(255) NOT NULL DEFAULT '',".
                "`msg_incoming` int(1) NOT NULL DEFAULT '0',".
                "`msg_sent` int(1) NOT NULL DEFAULT '0',".
                "`msg_send_time` datetime DEFAULT NULL,".
                "`msg_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP".

                ") ENGINE=MyISAM DEFAULT CHARSET=latin1;"
                );
            $this->check_error('install_messages: error creating table: ', $r);

            $r = $this->db->query(
                "ALTER TABLE `messages` ADD PRIMARY KEY (`msg_id`), ".
                "ADD FOREIGN KEY fk_conv(conv_id) REFERENCES conversations(conv_id) ON DELETE CASCADE ON UPDATE CASCADE,".
                "ADD KEY `user_id` (`user_id`),".
                "ADD KEY `msg_send_time` (`msg_send_time`)"
                );
            $this->check_error('install_messages: error adding keys: ', $r);

            $r = $this->db->query("ALTER TABLE `messages` MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1");
            $this->check_error('install_messages: error adding autoincrement for msg_id column: ', $r);
        }
    }

    private function install_receipts()
    {
        if (!$this->table_exists('receipts')) {
            $r = $this->db->query(
                "CREATE TABLE `receipts` (".
                "`rcp_id` int(11) NOT NULL,".

                "`contact_id` int(11) NOT NULL,".
                "`msg_id` int(11) NOT NULL,".

                "`rcp_sid` varchar(34) NOT NULL DEFAULT '',".
                "`rcp_bill` int(11) NOT NULL DEFAULT '0',".
                "`rcp_status` char(4) NOT NULL DEFAULT 'pend',".
                "`rcp_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP".

                ") ENGINE=MyISAM DEFAULT CHARSET=latin1;"
                );
            $this->check_error('install_receipts: error creating table: ', $r);

            $r = $this->db->query(
                "ALTER TABLE `receipts` ADD PRIMARY KEY (`rcp_id`),".
                "ADD FOREIGN KEY fk_contact(contact_id) REFERENCES contacts(contact_id) ON DELETE CASCADE ON UPDATE CASCADE,".
                "ADD FOREIGN KEY fk_message(msg_id) REFERENCES messages(msg_id) ON DELETE CASCADE ON UPDATE CASCADE"
                );
            $this->check_error('install_receipts: error adding keys: ', $r);

            $r = $this->db->query(
                "ALTER TABLE `receipts` ADD FOREIGN KEY fk_msg(msg_id) REFERENCES messages(msg_id) ON DELETE CASCADE ON UPDATE CASCADE"
                );
            $this->check_error('install_receipts: error adding fk_msg foreign key: ', $r);

            $r = $this->db->query(
                "ALTER TABLE `receipts` MODIFY `rcp_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1"
                );
            $this->check_error('install_receipts: error adding autoincrement for rcp_id column: ', $r);
        }
    }

    private function install_installations()
    {
        if (!$this->table_exists('installations')) {
            $r = $this->db->query(
                "CREATE TABLE `installations` (".
                "`installation_id` int(11) NOT NULL,".

                "`user_id` int(11) NOT NULL,".
                "`app_gcm_sender_id` text NOT NULL,".

                "`app_identifier` text NOT NULL DEFAULT '',".
                "`app_name` varchar(200) NOT NULL DEFAULT '',".
                "`app_version` varchar(55) NOT NULL DEFAULT '',".
                "`app_badge` int(11) NOT NULL DEFAULT 0,".
                "`app_channels` text NOT NULL ,".
                "`app_device_token` text NOT NULL ,".
                "`app_device_type` varchar (22) NOT NULL DEFAULT '',".
                "`app_installation_id` text NOT NULL ,".
                "`app_local_identifier` text NOT NULL ,".
                "`app_time_zone` text NOT NULL DEFAULT ''".
                ") ENGINE=MyISAM DEFAULT CHARSET=latin1;"
                );

            $this->check_error('install_installations: error creating table: ', $r);

            $r = $this->db->query(
                "ALTER TABLE `installations` ADD PRIMARY KEY (`installation_id`),".
                "ADD FOREIGN KEY fk_users(user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE"
                );
            $this->check_error('install_installations: error adding keys: ', $r);

            $r = $this->db->query(
                "ALTER TABLE `installations` MODIFY `installation_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1"
                );
            $this->check_error('install_installations: error adding autoincrement for installation_id column: ', $r);
        }
    }

    private function install_sessions()
    {
        if (!$this->table_exists('sessions')) {
            $r = $this->db->query(
                "CREATE TABLE IF NOT EXISTS `sessions` (".
                  "`session_id` varchar(40) NOT NULL default '0',".
                  "`ip_address` varchar(45) NOT NULL default '0',".
                  "`user_agent` varchar(120) NOT NULL,".
                  "`last_activity` int(10) unsigned NOT NULL default '0',".
                  "`user_data` text NOT NULL,".
                  "PRIMARY KEY (`session_id`),".
                  "KEY `last_activity_idx` (`last_activity`)".
                ") ENGINE=MyISAM DEFAULT CHARSET=utf8;"
                );
            $this->check_error('install_sessions: error creating ci_sessions table: ', $r);
        }
    }

    private function migrate_campaign_messages()
    {
        $migrated = 0;

        /* Setup a mapping of contact IDs to conversation IDs */
        $convMap = array();
        $this->db->select("conv_id,contact_id");
        $this->db->from('conversations');
        $this->db->where("contact_id IS NOT NULL", null, false);
        array_walk($this->db->get()->result_array(), function ($val, $key) use (&$convMap) {
            $convMap[intval($val['contact_id'])] = intval($val['conv_id']);
        });

        $limit = 1000;
        do {
            $this->db->select("cm.campaign_msg_id,cm.account_id,cm.contact_id,cm.campaign_msg_sent,cm.campaign_msg_stamp,cs.*");
            $this->db->from('campaign_messages cm');
            $this->db->join('campaign_steps cs', 'cs.campaign_step_id=cm.campaign_step_id', 'left');
            $this->db->where('cm.account_id !=', 0);
            $this->db->limit($limit, 0);

            $cmsgs = $this->db->get();

            foreach ($cmsgs->result_object() as $cmsg) {
                if (!isset($convMap[intval($cmsg->contact_id)])) {
                    $conv_data = array(
                        'account_id'=>$cmsg->account_id,
                        'contact_id'=>$cmsg->contact_id,
                        );

                    $this->db->insert('conversations', $conv_data);
                    $conv_id = $convMap[$cmsg->contact_id] = $this->db->insert_id();
                    if (!$conv_id) {
                        die('migrate_campaign_messages: abort: error inserting conversation: '.$this->db->_error_message().
                            '<pre>'.var_export($conv_data, 1).'</pre>');
                    }
                } else {
                    $conv_id = $convMap[$cmsg->contact_id];
                }

                $msg_data = array(
                    'conv_id'=>$conv_id,
                    'campaign_step_id'=>$cmsg->campaign_step_id ? $cmsg->campaign_step_id : 0,
                    'msg_name'=>$cmsg->campaign_step_name,
                    'msg_sent'=>$cmsg->campaign_msg_sent,
                    'msg_send_time'=>$cmsg->campaign_msg_stamp,
                    'msg_stamp'=>$cmsg->campaign_msg_stamp,
                    );

                switch ($cmsg->campaign_step_type) {
                case 'email':
                    //$msg_data['msg_content'] = $cmsg->campaign_step_message;
                    $msg_data['msg_from_email'] = $cmsg->campaign_step_from_email;
                    $msg_data['msg_from_name'] = $cmsg->campaign_step_from_name;
                    $msg_data['msg_subject'] = $cmsg->campaign_step_subject;
                    $msg_data['msg_type'] = 'email';
                    break;

                case 'reminder':
                    //$msg_data['msg_content'] = $cmsg->campaign_step_message;
                    $msg_data['msg_to_email'] = $cmsg->campaign_step_to_email;
                    $msg_data['msg_subject'] = $cmsg->campaign_step_subject;
                    $msg_data['msg_type'] = 'email';
                    break;

                case 'sms':
                    $msg_data['msg_content'] = $cmsg->campaign_step_message;
                    $msg_data['msg_media_type'] = $cmsg->campaign_step_media_type;
                    $msg_data['msg_media_url'] = $cmsg->campaign_step_media_url;
                    $msg_data['msg_type'] = 'sms';
                    break;

                default:
                    $msg_data['msg_type'] = 'unknown';
                    break;
                }

                $this->db->insert('messages', $msg_data);
                $msg_id = $this->db->insert_id();
                if (!$msg_id) {
                    die('migrate_campaign_messages: abort: error inserting message: '.$this->db->_error_message().
                        '<pre>'.var_export($msg_data, 1).'</pre>');
                }

                /* Generate a receipt for this campaign message.
                 */
                $rcp_data = array(
                    'contact_id'=>$cmsg->contact_id,
                    'msg_id'=>$msg_id,
                    'rcp_sid'=>'',
                    'rcp_bill'=>$cmsg->campaign_msg_sent ? 1 : 0,
                    'rcp_status'=>$cmsg->campaign_msg_sent ? 'sent' : 'pend',
                    'rcp_stamp'=>$cmsg->campaign_msg_stamp,
                    );

                $this->db->insert('receipts', $rcp_data);
                $rcp_id = $this->db->insert_id();
                if (!$rcp_id) {
                    die('migrate_chat_messages: abort: error inserting receipt: '.$this->db->_error_message().
                        '<pre>'.var_export($rcp_data, 1).'</pre>');
                }

                /* Delete the old campaign messge */
                $this->db->delete('campaign_messages', array('campaign_msg_id'=>$cmsg->campaign_msg_id));
                $migrated++;
            }

            error_log('migrator: migrate_campaign_messages: migrated '.$migrated.' campaign messages.');
        } while ($cmsgs->num_rows() >= $limit);

        return $migrated;
    }

    private function migrate_chat_messages()
    {
        $migrated = 0;

        /* Setup a mapping of contact IDs to conversation IDs */
        $convMap = array();
        $this->db->select("conv_id,contact_id");
        $this->db->from('conversations');
        $this->db->where("contact_id IS NOT NULL", null, false);
        array_walk($this->db->get()->result_array(), function ($val, $key) use (&$convMap) {
            $convMap[$val['contact_id']] = intval($val['conv_id']);
        });

        $limit = 1000;
        do {
            $this->db->select("cm.*, c.account_id");
            $this->db->from('chat_messages cm');
            $this->db->join('contacts c', 'c.contact_id=cm.contact_id');
            $this->db->limit($limit, 0);

            $chats = $this->db->get();
            foreach ($chats->result_object() as $chat) {
                if (!isset($convMap[$chat->contact_id])) {
                    $conv_data = array(
                        'account_id'=>$chat->account_id,
                        'contact_id'=>$chat->contact_id,
                        );

                    $this->db->insert('conversations', $conv_data);
                    $conv_id = $convMap[$chat->contact_id] = $this->db->insert_id();
                    if (!$conv_id) {
                        die('migrate_chat_messages: abort: error inserting conversation: '.$this->db->_error_message().
                            '<pre>'.var_export($conv_data, 1).'</pre>');
                    }
                } else {
                    $conv_id = $convMap[$chat->contact_id];
                }

                $msg_data = array(
                    'conv_id'=>$conv_id,
                    'msg_type'=>'sms',
                    'msg_content'=>$chat->chat_msg_text,
                    'msg_sent'=>1,
                    'msg_send_time'=>$chat->chat_msg_date,
                    'msg_stamp'=>$chat->chat_msg_date,
                );
                if ($chat->user_id == 0) {
                    $msg_data['msg_incoming'] = 1;
                }

                $this->db->insert('messages', $msg_data);
                $msg_id = $this->db->insert_id();
                if (!$msg_id) {
                    die('migrate_chat_messages: abort: error inserting message: '.$this->db->_error_message().
                        '<pre>'.var_export($msg_data, 1).'</pre>');
                }

                /* Generate a receipt for this chat message.
                 */
                $rcp_data = array(
                    'contact_id'=>$chat->contact_id,
                    'msg_id'=>$msg_id,
                    'rcp_sid'=>'',
                    'rcp_bill'=>$chat->user_id == 0 ? 0 : 1,
                    'rcp_status'=>'sent',
                    'rcp_stamp'=>$chat->chat_msg_date,
                    );

                $this->db->insert('receipts', $rcp_data);
                $rcp_id = $this->db->insert_id();
                if (!$rcp_id) {
                    die('migrate_chat_messages: abort: error inserting receipt: '.$this->db->_error_message().
                        '<pre>'.var_export($rcp_data, 1).'</pre>');
                }

                /* Delete the old chat messge */
                $this->db->delete('chat_messages', array('chat_msg_id'=>$chat->chat_msg_id));
                $migrated++;
            }

            error_log('migrator: migrate_chat_messages: migrated '.$migrated.' chat messages.');
        } while ($chats->num_rows() >= $limit);

        return $migrated;
    }

    private function migrate_conversations()
    {
        $migrated = 0;

        $this->db->delete('conversations', array('conv_id <='=>100));
        $this->db->delete('conversations', array('account_id'=>0));
        $this->db->delete('conversation_messages', array('conv_id <'=>100));

        $conv_limit = 100;
        do {
            $this->db->select("*, GROUP_CONCAT(group_id) as group_ids");
            $this->db->from('conversations_legacy cv');
            $this->db->join('conversation_groups cg', 'cg.conv_id=cv.conv_id');
            $this->db->where(array('cv.account_id !='=>0));
            $this->db->limit($conv_limit, 0);
            $this->db->group_by('cv.conv_id');
            $convs = $this->db->get();
            foreach ($convs->result_object() as $conv) {
                $group_ids = explode(',', $conv->group_ids);

                $conv_data = array(
                    'account_id'=>$conv->account_id,
                    );
                $msg_data = array(
                    'msg_name'=>$conv->conv_name,
                    'msg_type'=>$conv->conv_type,
                    'msg_from_email'=>$conv->conv_from_email,
                    'msg_from_name'=>$conv->conv_from_name,
                    'msg_subject'=>$conv->conv_subject,
                    'msg_content'=>$conv->conv_message,
                    'msg_media_type'=>$conv->conv_media_type,
                    'msg_media_url'=>$conv->conv_media_url,
                    'msg_sent'=>$conv->conv_sent,
                    'msg_send_time'=>$conv->conv_send_time,
                    'msg_stamp'=>$conv->conv_stamp,
                    );

                $first_msg_id = false;
                foreach ($group_ids as $group_id) {
                    $conv_data['group_id'] = $group_id;

                    /* For each account/group pair find and add if necessary a 'blast' conversation object. */
                    $this->db->select('conv_id');
                    $this->db->from('conversations');
                    $this->db->where($conv_data);
                    $row = $this->db->get()->row();
                    $conv_id = $row ? $row->conv_id : 0;
                    if (!$conv_id) {
                        $this->db->insert('conversations', $conv_data);
                        $conv_id = $this->db->insert_id();
                        if (!$conv_id) {
                            die('migrate_conversations: abort: error inserting conversation: '.$this->db->_error_message().
                                '<pre>'.var_export($conv_data, 1).'</pre>');
                        }
                    }

                    /* For each legacy conversation add a message object. */
                    $msg_data['conv_id'] = $conv_id;
                    $this->db->insert('messages', $msg_data);
                    $msg_id = $this->db->insert_id();
                    if ($msg_id) {
                        if (!$first_msg_id) {
                            $first_msg_id = $msg_id;
                        }
                    } else {
                        $this->db->delete('messages', array('conv_id'=>$conv->conv_id));
                        die('migrate_conversations: abort: error inserting message: '.$this->db->_error_message().
                            '<pre>'.var_export($msg_data, 1).'</pre>');
                    }
                }

                /* Generate a list of all of the receipts for this blast.
                 *
                 * We don't have a good way of determining which conversation group a given conversation message
                 * should be associated with.  It matters little, though, so we just store the first message id
                 * with each receipt. That'll usually be correct anyway.
                 */
                $rcp_data = array();
                if ($first_msg_id) {
                    /* For each conversation message in this legacy conversation add a receipt object. */
                    $conv_msg_limit = 1000;
                    do {
                        $this->db->from('conversation_messages cm');
                        $this->db->limit($conv_msg_limit, 0);
                        $this->db->where(array('conv_id'=>$conv->conv_id));
                        $conv_msgs = $this->db->get();
                        foreach ($conv_msgs->result_object() as $conv_msg) {
                            $rcp_data[] = array(
                                'contact_id'=>$conv_msg->contact_id,
                                'msg_id'=>$first_msg_id,
                                'rcp_sid'=>'',
                                'rcp_bill'=>$conv_msg->conv_msg_sent ? 1 : 0,
                                'rcp_status'=>$conv_msg->conv_msg_sent ? 'sent' : 'pend',
                                'rcp_stamp'=>$conv_msg->conv_msg_stamp,
                                );
                        }
                    } while (count($conv_msgs) >= $conv_msg_limit);
                }

                /* Batch insert all of the receipts (there may be none). */
                if (!empty($rcp_data)) {
                    $this->db->insert_batch('receipts', $rcp_data);
                    if ($this->db->_error_message() != '') {
                        $this->db->delete('messages', array('conv_id'=>$conv->conv_id));
                        $this->db->delete('receipts', array('conv_id'=>$conv->conv_id));
                        die('migrate_conversations: abort: error inserting receipts for conversation #'.$conv->conv_id);
                    }
                }

                $this->db->delete('conversations_legacy', array('conv_id'=>$conv->conv_id));
                //$this->db->delete('conversation_groups', array('conv_id'=>$conv->conv_id));       SAVES 50% of migration time to not perform these operations.
                //$this->db->delete('conversation_messages', array('conv_id'=>$conv->conv_id));     We're going to drop the tabels anyway, so...
                $migrated++;
            }

            error_log('migrator: migrate_conversations: migrated '.$migrated.' legacy conversations.');
        } while ($convs->num_rows() >= $conv_limit);

        return $migrated;
    }

    private function migrate_timezones()
    {
        $r = $this->db->query("ALTER TABLE `accounts` MODIFY `account_timezone` VARCHAR(24) NOT NULL DEFAULT ''");
        $this->check_error('migrate_timezones: error adding changing column to VARCHAR(24): ', $r);

        $zones = array(
            'UM12'=>'Pacific/Fiji', 'UM11'=>'Pacific/Midway', 'UM10'=>'America/Adak', 'UM9'=>'America/Anchorage',
            'UM8'=>'America/Los_Angeles', 'UM7'=>'America/Denver', 'UM6'=>'America/Chicago', 'UM5'=>'America/New_York',
            'UM4'=>'Canada/Atlantic', 'UM35'=>'Canada/Newfoundland', 'UM3'=>'America/Buenos_Aires',
            'UM2'=>'Atlantic/Stanley', 'UM1'=>'Atlantic/Azores', 'UTC'=>'Europe/London', 'UP1'=>'Europe/Berlin',
            'UP2'=>'Africa/Cairo', 'UP3'=>'Asia/Riyadh', 'UP25'=>'Asia/Tehran', 'UP4'=>'Asia/Tbilisi', 'UP35'=>'Asia/Kabul',
            'UP5'=>'Asia/Karachi', 'UP45'=>'Asia/Kolkata', 'UP6'=>'Asia/Almaty', 'UP7'=>'Asia/Bangkok',
            'UP8'=>'Asia/Hong_Kong', 'UP9'=>'Asia/Seoul', 'UP85'=>'Australia/Darwin', 'UP10'=>'Australia/Melbourne',
            'UP11'=>'Asia/Vladivostok', 'UP12'=>'Pacific/Auckland',
            );
        foreach ($zones as $code=>$zone) {
            $r = $this->db->query("UPDATE `accounts` SET `account_timezone`='$zone' WHERE `account_timezone`='$code'");
            $this->check_error("migrate_timezones: error adding remapping timezone: $code => $zone: ", $r);
        }
    }

    private function uninstall_addons_and_levels()
    {
        /* This is for later. */
        return false;

        $r = $this->db->query("DROP TABLE IF EXISTS account_addons");
        $this->check_error("uninstall_addons_and_levels: error dropping account_addons table: ", $r);

        $r = $this->db->query("DROP TABLE IF EXISTS addons");
        $this->check_error("uninstall_addons_and_levels: error dropping addons table: ", $r);

        $r = $this->db->query("DROP TABLE IF EXISTS membership_levels");
        $this->check_error("uninstall_addons_and_levels: error dropping membership_levels table: ", $r);

        $r = $this->db->query("ALTER TABLE accounts DROP COLUMN `level_id`");
        $this->check_error('uninstall_addons_and_levels: drop level_id column: ', $r);
    }

    private function upgrade_campaign_steps()
    {
        /* Add hint columns for schedule_campaign_messages. */
        if (!$this->column_exists('campaign_steps', 'campaign_step_is_immediate')) {
            $r = $this->db->query("ALTER TABLE campaign_steps ADD `campaign_step_is_immediate` TINYINT(1) NOT NULL DEFAULT 0 AFTER `campaign_step_is_active`");
            $this->check_error('campaign_steps: add campaign_step_is_immediate column: ', $r);
            $r = $this->db->query("ALTER TABLE campaign_steps ADD `campaign_step_at_ntime` INT(10) NOT NULL DEFAULT 0 AFTER `campaign_step_is_immediate`");
            $this->check_error('campaign_steps: add campaign_step_at_ntime column: ', $r);
            $r = $this->db->query("ALTER TABLE campaign_steps ADD `campaign_step_at_dow` INT(10) NOT NULL DEFAULT -1 AFTER `campaign_step_at_ntime`");
            $this->check_error('campaign_steps: add campaign_step_at_dow column: ', $r);
        }

        if (!$this->column_exists('campaign_steps', 'campaign_step_num_repeat')) {
            $r = $this->db->query("ALTER TABLE `campaign_steps` ADD `campaign_step_num_repeat` INT(11) NOT NULL DEFAULT '0' AFTER `campaign_step_repeat`;");
            $this->check_error('campaign_steps: add campaign_step_num_repeat column: ', $r);
        }

        /* Purge orphan steps. */
        $this->db->query("DELETE campaign_steps FROM campaign_steps LEFT JOIN groups g on g.group_id=campaign_id WHERE g.group_id IS NULL");

        /* Set hint values for all campaign steps in each group. */
        $this->load->model('campaign_steps_model');
        $this->db->select('account_id');
        $this->db->from('accounts');
        foreach ($this->db->get()->result() as $account) {
            $this->campaign_steps_model->refresh_hints_for_account($account->account_id);
        }
    }

    private function upgrade_messages()
    {
        if (!$this->column_exists('messages', 'msg_uniqid')) {
            $r = $this->db->query("ALTER TABLE messages ADD `msg_uniqid` INT(11) NOT NULL DEFAULT 0 AFTER `msg_media_url`");
            $this->check_error('messages: add msg_uniqd column: ', $r);
        }
    }

    private function upgrade_receipts()
    {
        if (!$this->column_exists('receipts', 'rcp_uniqid')) {
            $r = $this->db->query("ALTER TABLE receipts ADD `msg_uniqid` INT(11) NOT NULL DEFAULT 0 AFTER `rcp_bill`");
            $this->check_error('receipts: add msg_uniqid column: ', $r);
        }
    }


    /* Common Utility Methods
     */

    private function check_error($message, $r)
    {
        if (is_object($r)) {
            if ($r->result_id !== true) {
                error_log($message.var_export($r, 1));
                error_log('  error: '.$this->db->_error_message());
                return true;
            }
        } elseif ($r !== true) {
            error_log($message.var_export($r, 1));
            error_log('  error: '.$this->db->_error_message());
            return true;
        }

        return false;
    }

    private function column_exists($table, $column)
    {
        $r = $this->db->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
        return is_object($r) && $r->num_rows() != 0;
    }

    private function column_is_type($table, $column, $type)
    {
        $r = $this->db->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
        if (is_object($r) && $r->num_rows() > 0) {
            $row = $r->row_array();
            return strcasecmp($row['Type'], $type) === 0;
        } else {
            return false;
        }
    }

    private function index_exists($table, $index)
    {
        $r = $this->db->query("SHOW INDEX FROM `$table` WHERE `Key_name`='$index'");
        return $r->num_rows() != 0;
    }

    private function rename_columns($table, $columns)
    {
        foreach ($columns as $column) {
            $r = $this->db->query("ALTER TABLE $table CHANGE `".$column['old_name']."` `".$column['new_name']."` ".$column['type']);
            if ($r !== true) {
                error_log("$table : rename column: ".$column['old_name'].' -> '.$column['new_name'].': '.var_export($r, 1));
                error_log('  error: '.$this->db->_error_message());
            }
        }
    }

    private function table_exists($table)
    {
        $r = $this->db->query("SHOW TABLES LIKE '$table'");
        return $r->num_rows() != 0;
    }

    private function upgrade_test()
    {
        $this->load->database();
        error_log("hostname: ".$this->db->hostname);
        error_log("username: ".$this->db->username);
        error_log("password: ".$this->db->password);
        error_log("database: ".$this->db->database);
    }

    private function get_mappings()
    {
        return array(
        '1'=>array('products'=>array('21'),'discounts'=>'33'),
        '10'=>array('products'=>array('6','85'),'discounts'=>'5'),
        '18.5'=>array('products'=>array('1'),'discounts'=>'6'),
        '25'=>array('products'=>array('1'),'discounts'=>'7'),
        '27'=>array('products'=>array('21'),'discounts'=>'8'),
        '29'=>array('products'=>array('1'),'discounts'=>'9'),
        '31.45'=>array('products'=>array('1'),'discounts'=>'13'),
        '35'=>array('products'=>array('1','85'),'discounts'=>'18'),
        '37'=>array('products'=>array('1'),'discounts'=>''),
        '40'=>array('products'=>array('11'),'discounts'=>'25'),
        '47'=>array('products'=>array('11'),'discounts'=>'19'),
        '49'=>array('products'=>array('11'),'discounts'=>'16'),
        '50'=>array('products'=>array('11','85'),'discounts'=>'23'),
        '52'=>array('products'=>array('11','85'),'discounts'=>'22'),
        '56.95'=>array('products'=>array('11'),'discounts'=>'13'),
        '60'=>array('products'=>array('11'),'discounts'=>'12'),
        '67'=>array('products'=>array('11'),'discounts'=>''),
        '77'=>array('products'=>array('21','85'),'discounts'=>'18'),
        '79'=>array('products'=>array('21','85'),'discounts'=>'17'),
        '82.45'=>array('products'=>array('21'),'discounts'=>'13'),
        '89'=>array('products'=>array('21'),'discounts'=>'11'),
        '97'=>array('products'=>array('21'),'discounts'=>''),
        '107'=>array('products'=>array('21','85'),'discounts'=>'10'),
        '109'=>array('products'=>array('21','85'),'discounts'=>''),
        '127'=>array('products'=>array('51'),'discounts'=>''),
        '157'=>array('products'=>array('51'),'discounts'=>''),
        '175'=>array('products'=>array('6'),'discounts'=>'31'),
        '177'=>array('products'=>array('61'),'discounts'=>'12'),
        '197'=>array('products'=>array('61'),'discounts'=>''),
        '257'=>array('products'=>array('6'),'discounts'=>'27'),
        '297'=>array('products'=>array('6'),'discounts'=>'21'),
        '337'=>array('products'=>array('66'),'discounts'=>''),
        '370'=>array('products'=>array('6'),'discounts'=>''),
        '397'=>array('products'=>array('6','85'),'discounts'=>'7'),
        '400'=>array('products'=>array('6'),'discounts'=>'29'),
        '405'=>array('products'=>array('16'),'discounts'=>'6'),
        '417'=>array('products'=>array('6','85'),'discounts'=>'18'),
        '437'=>array('products'=>array('16'),'discounts'=>'28'),
        '440'=>array('products'=>array('43','50'),'discounts'=>'32'),
        '444'=>array('products'=>array('6'),'discounts'=>''),
        '477'=>array('products'=>array('16'),'discounts'=>'26'),
        '490'=>array('products'=>array('6','85'),'discounts'=>'14'),
        '577'=>array('products'=>array('16','85'),'discounts'=>'24'),
        '588'=>array('products'=>array('6','85'),'discounts'=>''),
        '597'=>array('products'=>array('16','85'),'discounts'=>'23'),
        '670'=>array('products'=>array('16'),'discounts'=>'14'),
        '790'=>array('products'=>array('16','85'),'discounts'=>'14'),
        '797'=>array('products'=>array('43'),'discounts'=>'7'),
        '799'=>array('products'=>array('43'),'discounts'=>'20'),
        '800'=>array('products'=>array('16'),'discounts'=>'6'),
        '804'=>array('products'=>array('16'),'discounts'=>''),
        '948'=>array('products'=>array('16'),'discounts'=>'85'),
        '970'=>array('products'=>array('43'),'discounts'=>'14'),
        '980'=>array('products'=>array('43','85'),'discounts'=>'15'),
        '1000'=>array('products'=>array('43'),'discounts'=>'30'),
        '1164'=>array('products'=>array('43'),'discounts'=>''),
        '1308'=>array('products'=>array('43','85'),'discounts'=>''),
        '1524'=>array('products'=>array('56'),'discounts'=>''),
        '1527'=>array('products'=>array('56'),'discounts'=>''),
        '2357'=>array('products'=>array('1'),'discounts'=>'86')
        );
    }


    private function group_settings_column()
    {
        if (!$this->column_exists('groups', 'group_settings')) {
            $r = $this->db->query("ALTER TABLE `groups` ADD `group_settings` TEXT NOT NULL AFTER `group_type`;");
            $this->check_error('groups: add group_settings column: ', $r);
            }
    }

    private function groups_add_deleted_column(){
        if (!$this->column_exists('groups', 'group_deleted')) {
            $this->db->query("ALTER TABLE `groups` ADD `group_deleted` TINYINT NOT NULL DEFAULT '0' AFTER `group_comment_title`;");
            $this->db->query("ALTER TABLE `textinchurch`.`groups` DROP INDEX `aid`, ADD INDEX `aid` (`account_id`, `group_deleted`);");
        }
    }

     private function group_contacts_fix_add_and_add_remove_date(){
        if (!$this->column_exists('group_contacts', 'group_contact_remove_date')) {
            $this->db->query("ALTER TABLE `group_contacts` CHANGE `group_contact_create_date` `group_contact_create_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;");
            $this->db->query("ALTER TABLE `group_contacts` ADD `group_contact_remove_date` TIMESTAMP NULL DEFAULT NULL AFTER `group_contact_create_date`;");
            $this->db->query("ALTER TABLE `group_contacts` ADD INDEX `still_in_group` (`group_contact_remove_date`);");
        }
    }


    public function issue_2023()
    {
        if (!$this->column_exists('contacts', 'contact_connected_accounts')) {
            $r = $this->db->query("ALTER TABLE `contacts` ADD `contact_connected_accounts` TEXT NOT NULL AFTER `contact_data_full`;");
            $this->check_error('contacts: add contact_data_synced column: ', $r);
            $this->db->query('update contacts set contact_connected_accounts=CONCAT("PCOID=",contact_pco_id) where contact_pco_id>0');
        }
    }
    private function addNewUserField()
    {
        if (!$this->column_exists('users', 'user_notification_enable')) {
            $r = $this->db->query("ALTER TABLE `users` ADD `user_notification_enable` TINYINT(1) NOT NULL DEFAULT '0' AFTER `user_onboarded`");
            $this->check_error('users: add user_notification_enable column: ', $r);
        }
    }

    private function add_group_integration_log_table()
    {
        if (!$this->table_exists('group_integration_log')) {
            $r = $this->db->query(
                "CREATE TABLE `group_integration_log` (
                `group_integration_log_id` int(11) NOT NULL AUTO_INCREMENT,
                `account_id` int(11) NOT NULL DEFAULT '0',
                `group_id` int(11) NOT NULL DEFAULT '0',
                `other_id` varchar(255) NOT NULL,
                `contact_id` int(11) NOT NULL DEFAULT '0',
                `group_integration_log_first_name` varchar(255) DEFAULT NULL,
                `group_integration_log_last_name` varchar(255) DEFAULT NULL,
                `group_integration_log_mobile` varchar(255) DEFAULT NULL,
                `group_integration_log_email` varchar(255) DEFAULT NULL,
                `group_integration_log_details` text NOT NULL,
                `group_integration_log_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (group_integration_log_id),
                INDEX ac_gr_other_id (account_id,group_id,other_id)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
            );
            $this->check_error('import group integration log table: error creating group_integration_log table: ', $r);
        }
    }
}
