Yii 2 Saml
==========

Ready to work fork of hpaulowicz/yii2saml repository

Connect Yii 2 application to a Saml Identity Provider for Single Sign On

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist hpaulowicz/yii2saml "*"
```

or add

```
"hpaulowicz/yii2saml": "*"
```

to the require section of your `composer.json` file.

Configuration
-------------

Register ``hpaulowicz\yii2saml\Saml`` to your components in ``config/web.php``.

```php
'components' => [
    'saml' => [
        'class' => 'vendor\hpaulowicz\yii2saml\Saml',
        'configFileName' => 'saml.php', // OneLogin_Saml config file (Optional)
    ]
]
```

This component already has a ``OneLogin_Saml`` core library. Config stored in a php file inside ``@app/config`` folder. The default value for ``configFileName`` is ``saml.php`` so make sure to create this file before. See this [link](https://github.com/onelogin/php-saml/blob/master/settings_example.php) for example configuration.

Usage
-----

This extension provides 4 actions:

1. LoginAction

    This actions will initiate login process to Identity Provider specified in config file. To use this action, just register this action to your actions in your controller.

    ```php
    <?php
    
    class SamlController extends Controller {

        public function actions() {
            return [
                'login' => [
                    'class' => 'vendor\hpaulowicz\yii2saml\actions\LoginAction'
                ]
            ];
        }

    }
    ```

    Now you can login to your Identity Provider by visiting ``saml/login``.

2. AcsAction

    This action will process saml response sent by Identity Provider after succesfull login. You can register a callback to do some operation like read the attributes sent by Identity Provider and create a new user from that attributes. To use this action just register this action to you controllers's actions.

    ```php
    <?php

    class SamlController extends Controller {

        public function actions() {
            return [
                'acs' => [
                    'class' => 'vendor\hpaulowicz\yii2saml\actions\AcsAction',
                    'successCallback' => [$this, 'callback'],
                    'successUrl' => Url::to('site/welcome'),
                ]
            ];
        }

        /**
         * @param array $attributes attributes sent by Identity Provider.
         */
        public function callback($attributes) {
            // do something
        }

    }
    ```
    
    **NOTE: Make sure to register the acs action's url to ``AssertionConsumerService`` in Identity Provider.** 

3. MetadataAction

    This action will show metadata of you application in xml. To use this action, just register the action to your controller's action.
    
    ```php
    <?php
    
    class SamlController extends Controller {
        
        public function actions() {
            return [
                'metadata' => [
                    'class' => 'vendor\hpaulowicz\yii2saml\actions\MetadataAction'
                ]
            ];
        }
        
    }
    ```

4. LogoutAction

    This action will initiate SingleLogout process to Identity Provider. To use this action, just register this action to your controller's actions.
    
    ```php
    <?php
    
    class SamlController extends Controller {
        
        public function actions() {
            return [
                'logout' => [
                    'class' => 'vendor\hpaulowicz\yii2saml\actions\LogoutAction',
                    'returnTo' => Url::to('site/bye'),
                ]
            ];
        }
        
    }
    ```

5. Testing as Service Provider

    Create a SSOCircle account: https://idp.ssocircle.com/sso/UI/Login
    Click 'Manage Metadata' (https://idp.ssocircle.com/sso/hos/ManageSPMetadata.jsp)
    Add new Service Provider (https://idp.ssocircle.com/sso/hos/SPMetaInter.jsp)
    Enter the FQDN of the ServiceProvider ex.: http://localhost/back/user/login
    Choose attributes sent in assertion
    Insert your metadata information ex.:
    ```xml
    <?xml version="1.0"?>
    <md:EntityDescriptor xmlns:md="urn:oasis:names:tc:SAML:2.0:metadata" validUntil="2016-06-09T22:57:34Z" cacheDuration="PT1400M" entityID="http://localhost/back/user/login">
      <md:SPSSODescriptor AuthnRequestsSigned="true" WantAssertionsSigned="false" protocolSupportEnumeration="urn:oasis:names:tc:SAML:2.0:protocol">
        <md:KeyDescriptor use="signing">
          <ds:KeyInfo xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
            <ds:X509Data>
              <ds:X509Certificate>MIIEP..........4+22ssI=</ds:X509Certificate>
            </ds:X509Data>
          </ds:KeyInfo>
        </md:KeyDescriptor>
        <md:KeyDescriptor use="encryption">
          <ds:KeyInfo xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
            <ds:X509Data>
              <ds:X509Certificate>MIIEP..........4+22ssI=</ds:X509Certificate>
            </ds:X509Data>
          </ds:KeyInfo>
        </md:KeyDescriptor>
        <md:NameIDFormat>urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified</md:NameIDFormat>
        <md:AssertionConsumerService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST" Location="http://localhost/back/index.php?r=user%2Fauth%2Facs" index="1"/>
      </md:SPSSODescriptor>
    </md:EntityDescriptor>
    ```
    
LICENCE
-------

MIT Licence
