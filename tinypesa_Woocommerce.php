<?php
/*
Plugin Name: TinyPesa for Woocommerce
Plugin URI: https://umeskiasoftwares.com/
Description: Get paid using One Link, from your website, app, or social media.
Author: Alvin Kiveu [ UMESKIA SOFTWARES ]
Author URI: http://youtube.com/microtechtutorials
Version: 0.1

*/

if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) return;
//LODIING MY PAYMENT FUNCTION
add_action('plugins_loaded', 'initialize_Tinypesa_payment', 11);

function initialize_Tinypesa_payment()
{
  if (class_exists('WC_Payment_Gateway')) {
    class WC_TinyPesa_Gateway extends WC_Payment_Gateway
    {
      public function __construct()
      {
        session_start();
        $this->id   = 'tinypesa';
        $this->icon = apply_filters('woocommerce_noob_icon', plugin_dir_url(__FILE__) . 'logo.png');
        $this->has_fields = false;
        $this->method_title = __('TinyPesa', 'tinypesa-pay-woo');
        $this->method_description = __('Enable customers to make payments to your business Till Number, Paybill and Bank');
        $this->init_form_fields();
        $this->init_settings();

        //GET INFOMATION SET BY THE USER
        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');
        $this->instructions = $this->get_option('instructions', $this->description);
        $this->mer              = $this->get_option('mer');
        //SESSION DATA 
        $_SESSION['apikey']   = $this->get_option('apikey');

        $_SESSION['acctivetionkey']       = $this->get_option('acctivetionkey');

        //LOADING THE PAYMENT OTION
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
      }

      //SETTING INSTALL INFORMATION
      public function init_form_fields()
      {

        $this->form_fields =  array(
          'enabled' => array(

            'title' => __('Enable/Disable', 'tinypesa-pay-woo'),

            'type' => 'checkbox',

            'label' => __('Enable or Disable TinyPesa Payments', 'tinypesa-pay-woo'),

            'default' => 'yes'
          ),

          'title' => array(
            'title' => __('Title', 'tinypesa-pay-woo'),

            'type' => 'text',

            'default'     => __('TINYPESA', 'tinypesa-pay-woo'),

            'desc_tip' => true,

            'description' => __('This controls the title which the user sees during checkout.', 'tinypesa-pay-woo')
          ),

          'description' => array(

            'title'       => __('Description', 'tinypesa-pay-woo'),

            'type'        => 'textarea',

            'description' => __('Payment method description that the customer will see on your checkout.', 'tinypesa-pay-woo'),

            'default'     => __('Place order and pay using TinyPesa.'),

            'desc_tip'    => true,

          ),

          'instructions' => array(

            'title'       => __('Instructions', 'tinypesa-pay-woo'),

            'type'        => 'textarea',

            'description' => __('Instructions that will be added to the thank you page and emails.', 'tinypesa-pay-woo'),

            'default'     => __('Place order and pay using Tiny Pesa.', 'tinypesa-pay-woo'),

            'desc_tip'    => true,

          ),

          'mer' => array(

            'title'       => __('Merchant Name', 'tinypesa-pay-woo'),

            'description' => __('Company name', 'tinypesa-pay-woo'),

            'type'        => 'text',

            'default'     => __('Company Name', 'tinypesa-pay-woo'),

            'desc_tip'    => false,

          ),

          'apikey' => array(

            'title'       =>  __('TinyPesa Api Key', 'tinypesa-pay-woo'),

            'default'     => __('', 'tinypesa-pay-woo'),

            'description' => __('Enter the api key from Tiny Pesa Dashboard', 'tinypesa-pay-woo'),

            'type'        => 'text',

            'desc_tip'    => true,

          ),

          'acctivetionkey' => array(

            'title'       =>  __('Plugin Activetion KEY', 'tinypesa-pay-woo'),

            'default'     => __('', 'tinypesa-pay-woo'),

            'description' => __('Enter the activetion key  from Umeskia Softwares', 'tinypesa-pay-woo'),

            'type'        => 'text',

            'desc_tip'    => true,
          ),


        );
      }
    }
  }
}