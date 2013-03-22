<?php
final class RewriteHrefFactory
{
	public static function getCompanyUrl(Company $company) {
		$url = PROTOCOL.'://'.$company->getAlias().'.'.DOMAIN;
		$href = Href::create()->setWorker(
		     RewriteUrlWorker::me()
		)->setParams(
		     array(
		         'area' => 'userList',
		         'routerName' => 'company'
		     )
		);
		return $url . $href->toString();
	}
	public static function getStaffWallMessageUrl(Employee $employee, EmployeeComment $comment) {
		$url = PROTOCOL.'://'.$employee->getCompany()->getAlias().'.'.DOMAIN;
		$href = Href::create()->setWorker(
		     RewriteUrlWorker::me()
		)->setParams(
		     array(
		         'serialId' => $employee->getSerialId(),
		         'area' => 'userProfileShow',
		         'routerName' => 'company-staff-xx'
		     )
		);
		return $url . $href->toString();
	}
	public static function getStaffUrl(Company $company, Employee $employee) {
		$url = PROTOCOL.'://'.$company->getAlias().'.'.DOMAIN;
		$href = Href::create()->setWorker(
		     RewriteUrlWorker::me()
		)->setParams(
		     array(
		         'serialId' => $employee->getSerialId(),
		         'area' => 'userProfileShow',
		         'routerName' => 'company-staff-xx'
		     )
		);
		return $url . $href->toString();
	}
	
	public static function getTaskUrl(Company $company, $task) {
		$url = PROTOCOL.'://'.$company->getAlias().'.'.DOMAIN;
		$href = Href::create()->setWorker(
		     RewriteUrlWorker::me()
		)->setParams(
		     array(         
		         'id' => $task->getSerialId(),
		         'area' => 'getTaskInfo',
		         'routerName' => 'task-view'
		     )
		);
		return $url . $href->toString();
	}
	
	public static function getForgetPasswordUrl($hash) {
		$url = PROTOCOL.'://'.GO_DOMAIN;
		$href = Href::create()->setWorker(
		     RewriteUrlWorker::me()
		)->setParams(
		     array(         
		         'id' => $hash,
		         'area' => 'recoverPassword',
		         'routerName' => 'recover-password'
		     )
		);
		return $url . $href->toString();
	}	

	public static function getRegisterUserUrl($invite) {
		$url = PROTOCOL.'://'.GO_DOMAIN;
		$href = Href::create()->setWorker(
		     RewriteUrlWorker::me()
		)->setParams(
		     array(         
		         'invite' => $invite,
		         'area' => 'registerUser',
		         'routerName' => 'register-invite'
		     )
		);
		return $url . $href->toString();		
	}
	public static function getRegistrationUrl() {
		$url = PROTOCOL.'://'.GO_DOMAIN;
		$href = Href::create()->setWorker(
		     RewriteUrlWorker::me()
		)->setParams(
		     array(
		         'area' => 'navigationRegister',
		         'routerName' => 'registration'
		     )
		);
		return $url . $href->toString();
	}
	
	public static function getUserInvitesUrl(User $user) {
		//activate		
		$url = PROTOCOL.'://'.$user->getAlias().'.'.DOMAIN;

		$href = Href::create()->setWorker(
		     RewriteUrlWorker::me()
		)->setParams(
		     array(
		         'area' => 'getUserInviteList',
		         'routerName' => 'user-invite-list'
		     )
		);
		
		return $url . $href->toString();				
	}

	public static function getLoginUrlGo() {
		$url = PROTOCOL.'://'.GO_DOMAIN;
		$href = Href::create()->setWorker(
		     RewriteUrlWorker::me()
		)->setParams(
		     array(         
		         'area' => 'tokenLogin',
		         'routerName' => 'login'
		     )
		);
		return $url . $href->toString();
	}
	
	public static function getLoginUrl($company) {
		$url = PROTOCOL.'://'.$company->getAlias().'.'.DOMAIN;
		$href = Href::create()->setWorker(
		     RewriteUrlWorker::me()
		)->setParams(
		     array(         
		         'area' => 'tokenLogin',
		         'routerName' => 'login'
		     )
		);
		return $url . $href->toString();
	}
	
	public static function getChangeOrganizationUrl($company) {
		//http://motivatortm.com/c10/company/staff/
		$url = PROTOCOL.'://'.$company->getAlias().'.'.DOMAIN;
		$href = Href::create()->setWorker(
		     RewriteUrlWorker::me()
		)->setParams(
		     array(         
		         'area' => 'userList',
		         'routerName' => 'company/staff'
		     )
		);
		return $url . $href->toString();
	}
	public static function getCompanyAccountUrl($company) {
		$url = PROTOCOL.'://'.$company->getAlias().'.'.DOMAIN;
		$href = Href::create()->setWorker(
		     RewriteUrlWorker::me()
		)->setParams(
		     array(
		         'area' => 'billPaymentHistory',
		         'routerName' => 'company/account'
		     )
		);
		return $url . $href->toString();
	}
	public static function getCompanyAccountReplenishUrl($company) {
		$url = PROTOCOL.'://'.$company->getAlias().'.'.DOMAIN;
		$href = Href::create()->setWorker(
		     RewriteUrlWorker::me()
		)->setParams(
		     array(
		         'area' => 'billAccountReplenish',
		         'routerName' => 'company/account/replenish'
		     )
		);
		return $url . $href->toString();
	}
}