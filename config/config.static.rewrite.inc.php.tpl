<?php

/*
 * Все rewrite правила тут
 */

return array(

// -- auth  --
	'auth-login' =>
		RouterStaticRule::create(
			'/auth/login/'
		)->setDefaults(
			array(
				'area' => 'tokenLogin',
			)
		),
	'start' =>
		RouterStaticRule::create(
			'/start'
		)->setDefaults(
			array(
				'area' => 'pageStartWorkCrm',
			)
		),
	'start-user' =>
		RouterStaticRule::create(
			'/start-user'
		)->setDefaults(
			array(
				'area' => 'pageStartUserWorkCrm',
			)
		),
	'login' =>
		RouterStaticRule::create(
			'/login/'
		)->setDefaults(
			array(
				'area' => 'tokenLogin',
			)
		),
	'main' =>
		RouterStaticRule::create(
			'/'
		)->setDefaults(
			array(
				'area' => 'dashboard',
			)
		),
	'help' =>
		RouterStaticRule::create(
			'/help'
		)->setDefaults(
			array(
				'area' => 'userFeedback',
			)
		),

	'logout' =>
		RouterStaticRule::create(
			'/logout/'
		)->setDefaults(
			array(
				'area' => 'logout',
			)
		),
/*
	'company' =>
		RouterStaticRule::create(
			'/company/'
		)->setDefaults(
			array(
				'area' => 'navigationCompany',
			)
		),
*/
	'company/account' =>
		RouterStaticRule::create(
			'/company/account/'
		)->setDefaults(
			array(
				'area' => 'billPaymentHistoryLicenses',
			)
		),
	'company/account/charges' =>
		RouterStaticRule::create(
			'/company/account/charges/'
		)->setDefaults(
			array(
				'area' => 'billPaymentHistoryCharges',
			)
		),
	'company/account/payments' =>
		RouterStaticRule::create(
			'/company/account/payments/'
		)->setDefaults(
			array(
				'area' => 'billPaymentHistoryPayments',
			)
		),
	'company/account/replenish' =>
		RouterStaticRule::create(
			'/company/account/replenish/'
		)->setDefaults(
			array(
				'area' => 'billAccountReplenish',
			)
		),
	'company/account/replenish/success' =>
		RouterStaticRule::create(
			'/company/account/replenish/success'
		)->setDefaults(
			array(
				'area' => 'billAccountReplenishSuccess',
			)
		),
	'company/account/replenish/fail' =>
		RouterStaticRule::create(
			'/company/account/replenish/fail'
		)->setDefaults(
			array(
				'area' => 'billAccountReplenishFail',
			)
		),
    'company/account/invoce/edit' =>
        RouterStaticRule::create(
            '/company/account/invoce/edit'
        )->setDefaults(
            array(
                'area' => 'editInvoice',
            )
        ),
/*
	'company/settings/tm/edit' =>
		RouterStaticRule::create(
			'/company/settings/tm/edit/'
		)->setDefaults(
			array(
				'area' => 'companyTaskSettingsEdit',
			)
		),
	'company/settings/tm' =>
		RouterStaticRule::create(
			'/company/settings/tm/'
		)->setDefaults(
			array(
				'area' => 'companyTaskSettingsList',
			)
		),
*/
	'company/settings/roles' =>
		RouterStaticRule::create(
			'/company/settings/roles/'
		)->setDefaults(
			array(
				'area' => 'getEmployeesRoles',
			)
		),
	'company/settings/roles/edit' =>
		RouterStaticRule::create(
			'/company/settings/roles/edit/'
		)->setDefaults(
			array(
				'area' => 'employeeRolesEdit',
			)
		),
/*
	'company/settings/zip_download' =>
		RouterStaticRule::create(
			'/company/settings/zip_download'
		)->setDefaults(
			array(
				'area' => 'zipQueueGetStatus',
			)
		),
	'company/settings' =>
		RouterStaticRule::create(
			'/company/settings'
		)->setDefaults(
			array(
				'area' => 'companySettingsRights',
			)
		),
*/
	'company-staff-xx-edit' =>
		RouterRegexpRule::create(
			'company/staff/(\d+)/edit'
        )->setReverse(
        	'company/staff/%d/edit'
        )->setMap(
            array(
                1 => 'serialId',
			)
		)->setDefaults(
			array(
				'area' => 'userProfileEdit',
			)
		),
	'company-staff-xx' =>
		RouterRegexpRule::create(
			'company/staff/(\d+)(?:/?)'
        )->setReverse(
        	'company/staff/%d'
        )->setMap(
            array(
                1 => 'serialId',
			)
		)->setDefaults(
			array(
				'area' => 'userProfileShow',
			)
		),
    'contacAdd' =>
        RouterStaticRule::create(
            '/company/staff/contact/add/'
        )->setDefaults(
            array(
                'area' => 'ajaxUserAddContact',
            )
        ),
    'contactDelete' =>
        RouterStaticRule::create(
            '/company/staff/contact/delete/'
        )->setDefaults(
            array(
                'area' => 'ajaxUserDeleteContact',
            )
        ),


	'company/staff' =>
		RouterStaticRule::create(
			'/company/staff/'
		)->setDefaults(
			array(
				'area' => 'userList',
			)
		),

	'company/staff/invite' =>
		RouterStaticRule::create(
			'/company/staff/invite/'
		)->setDefaults(
			array(
				'area' => 'inviteList',
			)
		),
    'company/staff/invite/send' =>
        RouterStaticRule::create(
            '/company/staff/invite/send/'
        )->setDefaults(
            array(
                'area' => 'inviteUser',
            )
        ),
    'company/staff/invite/clear' =>
        RouterStaticRule::create(
            '/company/staff/invite/clear/'
        )->setDefaults(
            array(
                'area' => 'clearInvite',
            )
        ),

		### Avatar ###

		'users/xx/avatar/xx.jpg' =>
		RouterRegexpRule::create(
			'company/staff/(\d+)/avatar/([0-9x]+).jpg'
        )->setMap(
            array(
                1 => 'shid',
                2 => 'type',
			)
		)->setDefaults(
			array(
				'area' => 'avatar',
			)
		),

		### /Avatar ###

	 ### Medals ###
	'company-medals-add' =>
		RouterRegexpRule::create(
			'company/staff/(\d+)/medals/add(?:\/?)'
        )->setMap(
            array(
                1 => 'serialId',
			)
		)->setDefaults(
			array(
				'area' => 'addMedal',
			)
		),
	'company-medals-delete' =>
		RouterRegexpRule::create(
			'company/staff/medals/([0-9a-z-]+)/delete(?:\/?)'
        )->setMap(
            array(
				1 => 'medal'
			)
		)->setDefaults(
			array(
				'area' => 'deleteMedal',
			)
		),
	### Notice list ###
	'company/noticelist' =>
		RouterStaticRule::create(
			'/company/staff/notices'
		)
		->setDefaults(
			array(
				'area' => 'noticeList',
			)
		),
	'registration' =>
		RouterStaticRule::create(
			'registration/'
		)->setDefaults(
			array(
				'area' => 'navigationRegister',
			)
		),
    'regback' =>
        RouterStaticRule::create(
            'regback/'
        )->setDefaults(
            array(
                'area' => 'register',
            )
        ),
	'register-invite' =>
		RouterRegexpRule::create(
			'registration/([0-9a-z-]+)(?:\/?)'
        )
        ->setReverse(
        	'registration/%s'
        )
        ->setMap(
            array(
				1 => 'invite'
			)
		)->setDefaults(
			array(
				'area' => 'registerUser',
			)
		),
	'user-companies-list' =>
		RouterStaticRule::create(
			'user/companies'
		)->setDefaults(
			array(
				'area' => 'getUserCompaniesList',
			)
		),
	'user-settings' =>
		RouterStaticRule::create(
			'user/settings'
		)->setDefaults(
			array(
				'area' => 'userNotificationSetting',
			)
		),
	'user-invite-list' =>
		RouterStaticRule::create(
			'user/invites'
		)->setDefaults(
			array(
				'area' => 'getUserInviteList',
			)
		),
	'activate-invite' =>
		RouterRegexpRule::create(
			'user/invites/([0-9a-z-]+)(?:\/?)'
        )->setReverse(
        	'user/invites/%s'
        )->setMap(
            array(
				1 => 'command'
			)
		)->setDefaults(
			array(
				'area' => 'activateInCompany',
			)
		),
	'recover-password-request' =>
		RouterRegexpRule::create(
			'login/forget'
		)->setDefaults(
			array(
				'area' => 'recoverPasswordRequest',
			)
		),
	'recover-password' =>
		RouterRegexpRule::create(
			'login/recovery/([0-9a-z-]+)(?:\/?)'
		)
		->setReverse(
        	'login/recovery/%s'
        )
		->setMap(
            array(
				1 => 'id'
			)
		)
		->setDefaults(
			array(
				'area' => 'recoverPassword',
			)
		),
	'recover-password-static' =>
		RouterStaticRule::create(
			'login/recovery'
		)->setDefaults(
			array(
				'area' => 'recoverPassword',
			)
		),
	'change-password' =>
		RouterRegexpRule::create(
			'user/password(?:\/?)'
		)->setDefaults(
			array(
				'area' => 'changePassword',
			)
		),
// -- /auth  --

// -- common --
	### Pix and files ###

	'pix' =>
		RouterRegexpRule::create(
			'img/([a-fA-F0-9\-]+)/?([0-9x]+)?/?(?:.*)?'
        )->setMap(
            array(
                1 => 'id',
                2 => 'type',
			)
		)->setDefaults(
			array(
				'area' => 'pix',
			)
		),

	'fileController' =>
		RouterRegexpRule::create(
			'att/([a-fA-F0-9\-]+)/(?:.*)'
        )->setMap(
            array(
                1 => 'id',
			)
		)->setDefaults(
			array(
				'area' => 'fileController',
			)
		),

	### /Pix and files ###

// -- /common --

);