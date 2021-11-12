
 define([
    'jquery',
    'Magento_Ui/js/form/form',
    'css',
    'ko',
    'mage/storage',
    'mage/url',
    'mage/translate'
], function ($, Component, css, ko, storage, urlBuilder,$t) {
    'use strict';


    /**
     * simple popup 
     */
     var subscriptionPopup = $("#magenable_subscription_popup ");

     /**
      * button close is clicked
      */
     $("#magenable_subscription_popup .close").unbind('click').bind('click', (e) => {e.preventDefault(); subscriptionPopup.hide()  } );
     /**
      * clicked outer the popup
      */
     //window.onclick = (event) => {event.target.getAttribute('id')!= 'magenable_subscription_popup' ? subscriptionPopup.hide() : null  } 
     
     
    return Component.extend({
        
        defaults: {
            template: 'Magenable_SubscriptionPopup/view/popup'
        },
        /**
         * @override
         */
        initialize: function (config, element) {
            this._super();
            
            /**
             * We load css asynchnomously by using require-css 
             */
            require(['css!./'+config.module_css_path], function(){
                /**
                 * After load css successfully, we show a poup
                 */
                setTimeout(function(){
                    $("#magenable_subscription_popup ").show();
                }, config.delay ? config.delay : 5000 );

            });
            

        },
        initObservable: function () {
            
            this.errorMessages = [];
            this.sucessMessages = [];
            this.email = '';
            this.observe(['email','popup_header','popup_description','email','errorMessages','sucessMessages']);
            this._super();
            return this;
        },

        subscribe : function(){
            $('body').trigger('processStart');
            
            
            var me = this;

            /** reset all messates */
            me.sucessMessages.splice(0, me.sucessMessages().length);
            me.errorMessages.splice(0, me.errorMessages().length);
            if(!this.email()){
                me.errorMessages.push($t('Please type your email address.'));
                return ;
            }

            $.ajax(
                {
                    method: 'POST',
                    url: urlBuilder.build('newsletter_ajax/subscription/subscribe'),
                    data : {
                        email: this.email(),
                        form_key : $.mage.cookies.get('form_key')
                    },
                    success : function(response){
                        if(response.success == true){
                            if(response.error_messages)
                                response.sucess_messages.forEach((item) => { me.sucessMessages.push(item) });

                        }
                        if(response.error == true){

                            if(response.error_messages)    
                                response.error_messages.forEach((item) => { me.errorMessages.push(item) });

                        }
                        jQuery('body').trigger('processStop');
                    },
                    error : function(response){
                        jQuery('body').trigger('processStop');
                    }
                }
            );
            
        },
        getSuccessMessages : function(){
            console.log(this);
            return this.sucessMessages;
        },
        getErrorMessages : function(){
            console.log(this);
            return this.errorMessages;
        }

    });
});
