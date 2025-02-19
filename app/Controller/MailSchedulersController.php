<?php
class MailSchedulersController extends AppController 
{
    public $uses=array('Addbranch','MailSchedular');
    public $components =array('Session');
    public function beforeFilter()
    {
        parent::beforeFilter();
	 
	if(!$this->Session->check("username"))
	{
            return $this->redirect(array('controller'=>'users','action' => 'logout'));
	}
	else
	{
            $role=$this->Session->read("role");
            $roles=explode(',',$this->Session->read("page_access"));
            $this->Auth->allow('index','business_dashboard_schedular','salary_schedular','profit_and_loss_schedular',
                    'budget_schedular','indirect_expenses_schedular','pending_salary_data_schedular');
            
	}
    }
		
    public function index()
    {  }
    
    public function business_dashboard_schedular()
    {
        $this->layout="home";
        $BranchArr = $this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>"active=1"));
        sort($BranchArr);
        $this->set('BranchArr',(array('All'=>'All') +$BranchArr));
        
        if($this->request->is('POST'))
        {
            $ScheduleData = $this->request->data['Report'];
            $data = array();
            foreach($ScheduleData as $mailer)
            {
                $mailer['ReportType'] = "BusinessDashboard";
                $data[] = $mailer;
            }
            
            $this->MailSchedular->deleteAll(array('ReportType'=>'BusinessDashboard'));
            if($this->MailSchedular->saveMany($data))
            {
                $this->Session->setFlash("Record Added Successfully");
            }
        }
        
        $MailScheduler = $this->MailSchedular->find('all',array('conditions'=>"ReportType='BusinessDashboard'"));
        
        foreach($MailScheduler as $mailer)
        {
            $NewData[$mailer['MailSchedular']['Branch']] = $mailer['MailSchedular'];
        }
        //print_r($NewData); exit;
        $this->set('MailSchedular',$NewData);
    }
    
    public function pending_salary_data_schedular()
    {
        $this->layout="home";
        $BranchArr = $this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>"active=1"));
        sort($BranchArr);
        $this->set('BranchArr',(array('All'=>'All') +$BranchArr)); 
        
        if($this->request->is('POST'))
        {
            $ScheduleData = $this->request->data['Report'];
            $data = array();
            foreach($ScheduleData as $mailer)
            {
                $mailer['ReportType'] = "PendingSalaryReport";
                $data[] = $mailer;
            }
            
            $this->MailSchedular->deleteAll(array('ReportType'=>'PendingSalaryReport'));
            if($this->MailSchedular->saveMany($data))
            {
                $this->Session->setFlash("Record Added Successfully");
            }
        }
        
        $MailScheduler = $this->MailSchedular->find('all',array('conditions'=>"ReportType='PendingSalaryReport'"));
        
        foreach($MailScheduler as $mailer)
        {
            $NewData[$mailer['MailSchedular']['Branch']] = $mailer['MailSchedular'];
        }
        //print_r($NewData); exit;
        $this->set('MailSchedular',$NewData);
    }
    
    public function salary_schedular(){
        $this->layout="home";
        $BranchArr = $this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>"active=1"));
        sort($BranchArr);
        $this->set('BranchArr',(array('All'=>'All') +$BranchArr));
        
        if($this->request->is('POST'))
        {
            
            $ScheduleData = $this->request->data['Report'];
            $data = array();
            foreach($ScheduleData as $mailer)
            {
                $mailer['ReportType'] = "SalaryDashboard";
                $data[] = $mailer;
            }
            
            $this->MailSchedular->deleteAll(array('ReportType'=>'SalaryDashboard'));
            if($this->MailSchedular->saveMany($data))
            {
                $this->Session->setFlash("Record Added Successfully");
            }
        }
        
        $MailScheduler = $this->MailSchedular->find('all',array('conditions'=>"ReportType='SalaryDashboard'"));
        
        foreach($MailScheduler as $mailer)
        {
            $NewData[$mailer['MailSchedular']['Branch']] = $mailer['MailSchedular'];
        }
        //print_r($NewData); exit;
        $this->set('MailSchedular',$NewData);
    }
    
    public function profit_and_loss_schedular(){
        $this->layout="home";
        $BranchArr = $this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>"pnl_active=1"));
        sort($BranchArr);
        $this->set('BranchArr',(array('All'=>'All') +$BranchArr));
        
        if($this->request->is('POST'))
        {
            
            $ScheduleData = $this->request->data['Report'];
            $data = array();
            foreach($ScheduleData as $mailer)
            {
                $mailer['ReportType'] = "ProfitAndLossDashboard";
                $data[] = $mailer;
            }
            
            $this->MailSchedular->deleteAll(array('ReportType'=>'ProfitAndLossDashboard'));
            if($this->MailSchedular->saveMany($data))
            {
                $this->Session->setFlash("Record Added Successfully");
            }
        }
        
        $MailScheduler = $this->MailSchedular->find('all',array('conditions'=>"ReportType='ProfitAndLossDashboard'"));
        
        foreach($MailScheduler as $mailer)
        {
            $NewData[$mailer['MailSchedular']['Branch']] = $mailer['MailSchedular'];
        }
        //print_r($NewData); exit;
        $this->set('MailSchedular',$NewData);
    }
    
    public function budget_schedular(){
        $this->layout="home";
        $BranchArr = $this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>"active=1"));
        sort($BranchArr);
        $this->set('BranchArr',(array('All'=>'All') +$BranchArr));
        
        if($this->request->is('POST'))
        {
            
            $ScheduleData = $this->request->data['Report'];
            $data = array();
            foreach($ScheduleData as $mailer)
            {
                $mailer['ReportType'] = "BudgetDashboard";
                $data[] = $mailer;
            }
            
            $this->MailSchedular->deleteAll(array('ReportType'=>'BudgetDashboard'));
            if($this->MailSchedular->saveMany($data))
            {
                $this->Session->setFlash("Record Added Successfully");
            }
        }
        
        $MailScheduler = $this->MailSchedular->find('all',array('conditions'=>"ReportType='BudgetDashboard'"));
        
        foreach($MailScheduler as $mailer)
        {
            $NewData[$mailer['MailSchedular']['Branch']] = $mailer['MailSchedular'];
        }
        //print_r($NewData); exit;
        $this->set('MailSchedular',$NewData);
    }
    
    public function indirect_expenses_schedular(){
        $this->layout="home";
        $BranchArr = $this->Addbranch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>"active=1"));
        sort($BranchArr);
        $this->set('BranchArr',(array('All'=>'All') +$BranchArr));
        
        if($this->request->is('POST'))
        {
            
            $ScheduleData = $this->request->data['Report'];
            $data = array();
            foreach($ScheduleData as $mailer)
            {
                $mailer['ReportType'] = "IndirectExpensesDashboard";
                $data[] = $mailer;
            }
            
            $this->MailSchedular->deleteAll(array('ReportType'=>'IndirectExpensesDashboard'));
            if($this->MailSchedular->saveMany($data))
            {
                $this->Session->setFlash("Record Added Successfully");
            }
        }
        
        $MailScheduler = $this->MailSchedular->find('all',array('conditions'=>"ReportType='IndirectExpensesDashboard'"));
        
        foreach($MailScheduler as $mailer)
        {
            $NewData[$mailer['MailSchedular']['Branch']] = $mailer['MailSchedular'];
        }
        //print_r($NewData); exit;
        $this->set('MailSchedular',$NewData);
    }
    
}
?>