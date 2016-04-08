<?php

return [
	'components' => [
	    'saml' => [
	        'class' => 'vendor\hpaulowicz\yii2saml\Saml',
	        'configFileName' => '@app/config/saml.php', // OneLogin_Saml config file (Optional)
	    ]
	]
];