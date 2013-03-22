<?php

/*
 * Все rewrite правила тут
 */

return array(

//	'login' =>
//		RouterStaticRule::create(
//			'/login/'
//		)->setDefaults(
//			array(
//				'area' => 'tokenLogin',
//			)
//		),
// -- auth  --
	'auth-login' =>
		RouterStaticRule::create(
			'/auth/login/'
		)->setDefaults(
			array(
				'area' => 'tokenLogin',
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
	'user' =>
		RouterStaticRule::create(
			'/user/'
		)->setDefaults(
			array(
				'area' => 'userDashboard',
			)
		),
	'widgets/shoutbox' =>
		RouterStaticRule::create(
			'/widgets/shoutbox/'
		)->setDefaults(
			array(
				'area' => 'getShoutboxMessage',
			)
		),
	'widgets/shoutbox/dock' =>
		RouterStaticRule::create(
			'/widgets/shoutbox/dock/'
		)->setDefaults(
			array(
				'area' => 'ShoutboxWidget',
			)
		),
	'widgets/shoutbox/add' =>
		RouterStaticRule::create(
			'/widgets/shoutbox/add/'
		)->setDefaults(
			array(
				'area' => 'addShoutboxMessage',
			)
		),
	'widgets/notices' =>
		RouterStaticRule::create(
			'/widgets/notices/'
		)->setDefaults(
			array(
				'area' => 'noticeList',
			)
		),
	'widgets/notices/dock' =>
		RouterStaticRule::create(
			'/widgets/notices/dock/'
		)->setDefaults(
			array(
				'area' => 'noticeList',
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
	'company' =>
		RouterStaticRule::create(
			'/company/'
		)->setDefaults(
			array(
				'area' => 'navigationCompany',
			)
		),
	'company/account' =>
		RouterStaticRule::create(
			'/company/account/'
		)->setDefaults(
			array(
				'area' => 'billPaymentHistory',
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


	'company/staff' =>
		RouterStaticRule::create(
			'/company/staff/'
		)->setDefaults(
			array(
				'area' => 'userList',
			)
		),

	'company/hall_of_fame' =>
		RouterStaticRule::create(
			'/company/hall_of_fame/'
		)->setDefaults(
			array(
				'area' => 'userList',
				'sort' => 'medals',
				'sortType' => 'desc',
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

	 ###  WALL STATUSES  ###
	//List of wall statuses
	'company-staff-xx-comments' =>
		RouterRegexpRule::create(
			'company/staff/(\d+)/comments(?:/?)'
        )->setMap(
            array(
                1 => 'serialId',
			)
		)->setDefaults(
			array(
				'area' => 'wall',
			)
		),
	//Add / edit wall statuses
	'company-staff-xx-comments-edit' =>
		RouterRegexpRule::create(
			'company/staff/(\d+)/comments/(add|edit)(?:\/?)'
        )->setMap(
            array(
                1 => 'serialId',
				2 => 'action'
			)
		)->setDefaults(
			array(
				'area' => 'wall',
			)
		),
	//Like comment (wall status)
	'company-staff-comment-like' =>
		RouterRegexpRule::create(
			'company/staff/comment/([0-9a-z-]+)/like(?:\/?)'
        )->setMap(
            array(
                1 => 'targetId'
			)
		)->setDefaults(
			array(
				'area' => 'addWallLike',
			)
		),
	 ###  /WALL STATUSES  ###

	 ###  UserFrontendStorage  ###
	'company-staff-frontend-storage' =>
		RouterRegexpRule::create(
			'company/staff/user/frontendstorage'
        )->setDefaults(
			array(
				'area' => 'userFrontendStorage',
			)
		),
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
	### Labels ###
	'labels-add' =>
		RouterStaticRule::create(
			'/widgets/labels/add'
		)->setDefaults(
			array(
				'area' => 'labelAdd',
			)
		),
	'labels-edit' =>
		RouterStaticRule::create(
			'/widgets/labels/edit'
		)->setDefaults(
			array(
				'area' => 'labelEdit',
			)
		),
	'labels-delete' =>
		RouterStaticRule::create(
			'/widgets/labels/delete'
		)->setDefaults(
			array(
				'area' => 'labelDel',
			)
		),
	'labels-view' =>
		RouterStaticRule::create(
			'/widgets/labels'
		)->setDefaults(
			array(
				'area' => 'getLabels',
			)
		),
	// 'register' =>
		// RouterRegexpRule::create(
			// 'registration(?:\/?)'
		// )->setDefaults(
			// array(
				// 'area' => 'register',
			// )
		// ),
	'registration' =>
		RouterStaticRule::create(
			'registration/'
		)->setDefaults(
			array(
				'area' => 'navigationRegister',
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

// -- task --
	'task-create' =>
		RouterStaticRule::create(
			'/tasks/add/'
		)->setDefaults(
			array(
				'area' => 'createTask',
			)
		),
	'task/new' =>
		RouterStaticRule::create(
			'/tasks/new/'
		)->setDefaults(
			array(
				'area' => 'navigationNewTask',
			)
		),
	'task-favourite-mark' =>
		RouterStaticRule::create(
			'/tasks/favourite/mark'
		)->setDefaults(
			array(
				'area' => 'changeAddBookmarkTask',
			)
		),
	'task-favourite-unmark' =>
		RouterStaticRule::create(
			'/tasks/favourite/unmark'
		)->setDefaults(
			array(
				'area' => 'changeRemoveBookmarkTask',
			)
		),
	'task-labels-mark' =>
		RouterStaticRule::create(
			'/tasks/labels/mark'
		)->setDefaults(
			array(
				'area' => 'labelsToTasksBind',
			)
		),
	'task-labels-unmark' =>
		RouterStaticRule::create(
			'/tasks/labels/unmark'
		)->setDefaults(
			array(
				'area' => 'labelsToTasksUnBind',
			)
		),
	'tasks-cancel' =>
		RouterStaticRule::create(
			'/tasks/mass/cancel'
		)->setDefaults(
			array(
				'area' => 'cancelTask',
			)
		),
	'tasks-accept' =>
		RouterStaticRule::create(
			'/tasks/mass/accept'
		)->setDefaults(
			array(
				'area' => 'acceptTask',
			)
		),
	'tasks-work' =>
		RouterStaticRule::create(
			'/tasks/mass/work'
		)->setDefaults(
			array(
				'area' => 'workTask',
			)
		),
	'tasks-rework' =>
		RouterStaticRule::create(
			'/tasks/mass/rework'
		)->setDefaults(
			array(
				'area' => 'declineTask',
			)
		),
	'tasks-restore' =>
		RouterStaticRule::create(
			'/tasks/mass/restore'
		)->setDefaults(
			array(
				'area' => 'restoreTask',
			)
		),
	'tasks-take' =>
		RouterStaticRule::create(
			'/tasks/mass/take'
		)->setDefaults(
			array(
				'area' => 'takeTask',
			)
		),
	'tasks-close' =>
		RouterStaticRule::create(
			'/tasks/mass/close'
		)->setDefaults(
			array(
				'area' => 'closeTask',
			)
		),
	'tasks-toaccept' =>
		RouterStaticRule::create(
			'/tasks/mass/toaccept'
		)->setDefaults(
			array(
				'area' => 'toAcceptTask',
			)
		),
	'tasks-list' =>
		RouterStaticRule::create(
			'/tasks/list'
		)->setDefaults(
			array(
				'area' => 'getTaskList',
			)
		),
	'tasks' =>
		RouterStaticRule::create(
			'/tasks'
		)->setDefaults(
			array(
				'area' => 'getTasks',
			)
		),
	'task-view' =>
		RouterRegexpRule::create(
			'tasks/(\d+)'
		)->setReverse(
        	'tasks/%d'
        )->setMap(
			array(
				1 => 'id'
			)
		)
		->setDefaults(
			array(
				'area' => 'getTaskInfo',
			)
		),
	'task-edit-name' =>
		RouterRegexpRule::create(
			'tasks/(\d+)/edit/name'
		)->setMap(
			array(
				1 => 'id'
			)
		)
		->setDefaults(
			array(
				'area' => 'changeNameTask',
			)
		),
	'task-edit-deadline' =>
		RouterRegexpRule::create(
			'tasks/(\d+)/edit/deadline'
		)->setMap(
			array(
				1 => 'id'
			)
		)
		->setDefaults(
			array(
				'area' => 'changeDeadlineTask',
			)
		),
	'task-edit-executor' =>
		RouterRegexpRule::create(
			'tasks/(\d+)/edit/executor'
		)->setMap(
			array(
				1 => 'id'
			)
		)
		->setDefaults(
			array(
				'area' => 'changeExecutorTask',
			)
		),
	'task-edit-members' =>
		RouterRegexpRule::create(
			'tasks/(\d+)/edit/members'
		)->setMap(
			array(
				1 => 'id'
			)
		)
		->setDefaults(
			array(
				'area' => 'changeMembersTask',
			)
		),
	'task-edit-priority' =>
		RouterRegexpRule::create(
			'tasks/(\d+)/edit/priority'
		)->setMap(
			array(
				1 => 'id'
			)
		)
		->setDefaults(
			array(
				'area' => 'changePriorityTask',
			)
		),
	'task-edit-price' =>
		RouterRegexpRule::create(
			'tasks/(\d+)/edit/price'
		)->setMap(
			array(
				1 => 'id'
			)
		)
		->setDefaults(
			array(
				'area' => 'changePriceTask',
			)
		),
	'task-edit-all' =>
		RouterRegexpRule::create(
			'tasks/(\d+)/edit'
		)->setMap(
			array(
				1 => 'id'
			)
		)
		->setDefaults(
			array(
				'area' => 'editTask',
			)
		),
	'task-comment-add' =>
		RouterRegexpRule::create(
			'tasks/(\d+)/comments/add'
		)->setMap(
			array(
				1 => 'id'
			)
		)
		->setDefaults(
			array(
				'area' => 'commentAdd',
			)
		),
	'task-comment-edit' =>
		RouterRegexpRule::create(
			'tasks/(\d+)/comments/edit'
		)->setMap(
			array(
				1 => 'task'
			)
		)
		->setDefaults(
			array(
				'area' => 'commentEdit',
			)
		),
	'task-comments' =>
		RouterRegexpRule::create(
			'tasks/(\d+)/comments'
		)->setMap(
			array(
				1 => 'id'
			)
		)
		->setDefaults(
			array(
				'area' => 'getTaskCommentsByTask',
			)
		),
	'task-requiments-add' =>
		RouterRegexpRule::create(
			'tasks/(\d+)/requirements/add'
		)->setMap(
			array(
				1 => 'id'
			)
		)
		->setDefaults(
			array(
				'area' => 'requimentAdd',
			)
		),
	'task-requiments-edit' =>
		RouterRegexpRule::create(
			'tasks/(\d+)/requirements/edit'
		)->setMap(
			array(
				1 => 'task'
			)
		)
		->setDefaults(
			array(
				'area' => 'requimentEdit',
			)
		),
	'task-requiments-sort' =>
		RouterRegexpRule::create(
			'tasks/(\d+)/requirements/sort'
		)->setMap(
			array(
				1 => 'id'
			)
		)
		->setDefaults(
			array(
				'area' => 'updateRequimentsSorts',
			)
		),
	'task-requiments' =>
		RouterRegexpRule::create(
			'tasks/(\d+)/requirements'
		)->setMap(
			array(
				1 => 'id'
			)
		)
		->setDefaults(
			array(
				'area' => 'getRequimentsByTask',
			)
		),
	'widgets-material' =>
		RouterStaticRule::create(
			'/widgets/material'
		)->setDefaults(
			array(
				'area' => 'getMotivationEmployees',
			)
		),
	'widgets-material-doc' =>
		RouterStaticRule::create(
			'/widgets/material/dock'
		)->setDefaults(
			array(
				'area' => 'getMotivationPercents',
			)
		),
	'widgets-material-set' =>
		RouterStaticRule::create(
			'/widgets/material/set'
		)->setDefaults(
			array(
				'area' => 'setTargetEmployee',
			)
		),
	'widgets-material-view' =>
		RouterRegexpRule::create(
			'widgets/material/(\d+)'
		)->setMap(
			array(
				1 => 'employee'
			)
		)
		->setDefaults(
			array(
				'area' => 'getMotivationEmployees',
			)
		),
// -- /task --


// Reports begin

		'reports/tasks' =>
			RouterStaticRule::create(
				'/reports/tasks/'
			)->setDefaults(
				array(
					'area' => 'salaryReportTasksList',
				)
			),

		'reports/tasks/uid' =>
			RouterTransparentRule::create(
				'/reports/tasks/:id/'
			)->setDefaults(
				array(
					'area' => 'salaryReportTask',
				)
			),


// Reports end


// -- common --
	'tmpl' =>
		RouterRegexpRule::create(
			'api/tmpl/(.*)'
		)->setMap(
			array(
				1 => 'path',
			)
		)->setDefaults(
			array(
				'area' => 'tmpl',
			)
		),

	'tmpl' =>
		RouterRegexpRule::create(
			'api/json/(.*)'
		)->setMap(
			array(
				1 => 'path',
			)
		)->setDefaults(
			array(
				'area' => 'json',
			)
		),


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