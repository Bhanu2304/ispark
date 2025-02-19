<?php
class DashsController extends AppController 
{
    public $uses=array('Addbranch','Dash','Targets','DashboardData','BillMaster','CostCenterMaster','DashboardProcess','TMPdashboardTarget',
        'FreezeData','DashboardBusPart','Provision','ExpenseMaster','ExpenseMasterOld','ExpenseParticular','ExpenseParticularOld');
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
            $this->Auth->allow('dashboard','provisionDetails','showReport','get_dash_data','get_report11','view4','get_cost_center','view_process_report');
	}
    }
    
    
    public function view4()
    {
        $this->layout='ajax';
        $this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'))));
    }
    public function get_cost_center()
    {	
            $this->layout = "ajax";
            $branch = $this->request->data('branch');
            $tower = $this->CostCenterMaster->find('all',array('fields'=>array('id','cost_center','process_name'),'conditions'=>array('branch'=>$branch
     ,'active'=>'1',"(close>date(now()) or close is null)")));
    
    //print_r($tower); exit;
    if(!empty($tower))
    {
        foreach($tower as $tow)
        {
            $cost_arr[$tow['CostCenterMaster']['id']] =  $tow['CostCenterMaster']['cost_center'].'-'.$tow['CostCenterMaster']['process_name'];
        }
    }
    
        $this->set('cost_arr',$cost_arr);
    }
    public function view()
    {	
        $this->layout = "home";
        $this->set('branch_master', $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'conditions'=>"active=1")));
        $this->set('financeYearArr',$this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>array('14-15','2014-15','2015-16','2016-17'))))));
    }
    
    
    public function get_dash_data()
    {
      $this->layout = "ajax";
      $result = $this->params->query; 
      $ReportType = $this->params->query['ReportType'];
      $finYear = $this->params->query['FinanceYear'];
      $finMonth = $this->params->query['FinanceMonth'];
      $Branch = $this->params->query['barnch'];
      $cost_id = $this->params->query['cost_center'];
      $type = $this->params->query['type'];
      //print_r($result); exit;
         $this->set("DataNew",$result);
         $this->set('ReportType',$ReportType);
         $this->set('Branch',$Branch);
         $this->set('FinanceYear',$finYear);
         $this->set('FinanceMonth',$finMonth);
         $this->set('type',$type);
         
        // get values here 
       // echo $result['barnch'];exit;
         $revnue_table = 'provision_master';
         $budget_table_master = 'expense_master';
         $budget_table_particular = 'expense_particular';
         if($this->FreezeData->find('first',array('conditions'=>"Branch='$Branch' and FinanceYear='$finYear' and FinanceMonth='$finMonth' and ApproveStatus='2'")))
         {
            $revnue_table = 'dashboard_save_prov';
            $budget_table_master = 'expense_master_old';
            $budget_table_particular = 'expense_particular_old';
         }
         
        if($ReportType== 'Branch')
        {
            if($Branch!='All')
            {
                $BranchAll = " and cm.branch='$Branch'";
            }
            else
            {
                $BranchAll = '';
            }

               $AspirationalQry = "SELECT * FROM `dashboard_Target` dt
    INNER JOIN cost_master cm ON dt.cost_centerId=cm.id $BranchAll
    WHERE dt.FinanceYear='$finYear' AND dt.FinanceMonth='$finMonth'   group by cost_centerId "; 

            $AspirationalData = $this->Targets->query($AspirationalQry);




            foreach($AspirationalData as $asp)
            {
                $NewData[$asp['cm']['branch']][$asp['dt']['cost_centerId']]['Asp']['revenue'] =  $asp['dt']['target'];
                $NewData[$asp['cm']['branch']][$asp['dt']['cost_centerId']]['Asp']['dc'] =  $asp['dt']['target_directCost'];
                $NewData[$asp['cm']['branch']][$asp['dt']['cost_centerId']]['Asp']['idc'] =  $asp['dt']['target_IDC'];
                $cost_master[$asp['cm']['branch']][] = $asp['cm']['id'];
                $BranchArr[] =  $asp['cm']['branch'];
            }

            $Actual = $this->Targets->query("SELECT cm.id,cm.branch,dd.branch,cost_centerId,branch_process,
    `commit` Revenue,
    direct_cost DirectCost,
    indirect_cost InDirectCost
    FROM `dashboard_data` dd
    INNER JOIN cost_master cm ON dd.cost_centerId=cm.id $BranchAll
    WHERE YEAR(dd.createdate)=YEAR(CURDATE())  AND dd.FinanceYear='$finYear' AND dd.FinanceMonth='$finMonth'   AND 
    dd.createdate = (SELECT MAX(createdate) FROM dashboard_data AS dd1 WHERE YEAR(dd.createdate)=YEAR(CURDATE())  
    AND  dd1.FinanceYear='$finYear' AND dd1.FinanceMonth='$finMonth'  AND dd.cost_centerId=dd1.cost_centerId)");

            foreach($Actual as $bas)
            {
               // if(empty($TmpActual[$bas['dd']['cost_centerId']]))
               // {
                    $NewData[$bas['cm']['branch']][$bas['dd']['cost_centerId']]['Actual']['revenue'] =  $bas['dd']['Revenue'];
                    $NewData[$bas['cm']['branch']][$bas['dd']['cost_centerId']]['Actual']['dc'] =  $bas['dd']['DirectCost'];
                    $NewData[$bas['cm']['branch']][$bas['dd']['cost_centerId']]['Actual']['idc'] =  $bas['dd']['InDirectCost'];
              //  }
//                else
//                {
//                    if(empty($TmpActual[$bas['cm']['branch']][$bas['cm']['id']]['Actual']['revenue']!='') || $TmpActual[$bas['cm']['branch']][$bas['cm']['id']]['Actual']['revenue']!=null)
//                    {
//                        $NewData[$bas['cm']['branch']][$bas['dd']['cost_centerId']]['Actual']['revenue'] =  $TmpActual[$bas['cm']['branch']][$bas['cm']['id']]['Actual']['revenue'];
//                    }
//                    else
//                    {
//
//                        $NewData[$bas['cm']['branch']][$bas['dd']['cost_centerId']]['Actual']['revenue'] =  $bas['dd']['Revenue'];
//                    }
//
//                    if($TmpActual[$bas['cm']['branch']][$bas['cm']['id']]['Actual']['dc']!='' || $TmpActual[$bas['cm']['branch']][$bas['cm']['id']]['Actual']['dc']!=null)
//                    {
//
//                        $NewData[$bas['cm']['branch']][$bas['dd']['cost_centerId']]['Actual']['dc'] =  $TmpActual[$bas['cm']['branch']][$bas['cm']['id']]['Actual']['dc'];
//                    }
//                    else
//                    {
//                        $NewData[$bas['cm']['branch']][$bas['dd']['cost_centerId']]['Actual']['dc'] =  $bas['dd']['DirectCost']; 
//                    }
//
//                    if($TmpActual[$bas['cm']['branch']][$bas['cm']['id']]['Actual']['idc']!='' || $TmpActual[$bas['cm']['branch']][$bas['cm']['id']]['Actual']['idc']!=null)
//                    {
//
//                        $NewData[$bas['dd']['cost_centerId']]['Actual']['idc'] =  $TmpActual[$bas['cm']['branch']][$bas['cm']['id']]['Actual']['idc'];
//                    }
//                    else
//                    {
//                        $NewData[$bas['cm']['branch']][$bas['dd']['cost_centerId']]['Actual']['idc'] =  $bas['dd']['InDirectCost'];
//                    }
//                }
                $cost_master[[$bas['cm']['branch']]][] = $bas['cm']['id'];
                $BranchArr[] =  $bas['cm']['branch'];
            }

    
           $NewFinanceMonth = $finMonth; 
        $monthArr = array('Jan','Feb','Mar'); 
            $split = explode('-',$finYear); 
            if(in_array($finMonth, $monthArr)) 
            {
                $NewFinanceMonth .= '-'.$split[1];    //Year from month
            }
            else
            {
                $NewFinanceMonth .= '-'.($split[1]-1);    //Year from month
            }



            $RevenueBasic = $this->Targets->query("SELECT cm.id,cm.branch,pm.provision FROM $revnue_table pm
    LEFT JOIN 
    (
    SELECT ti.cost_center,ti.month,SUM(ti.total) total FROM tbl_invoice ti
    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center $BranchAll
     WHERE  ti.month='$NewFinanceMonth' group by cm.id) ti 
    ON pm.month = ti.month AND pm.cost_center = ti.cost_center
    INNER JOIN cost_master cm ON pm.cost_center=cm.cost_center $BranchAll
    WHERE  pm.month='$NewFinanceMonth'");

            foreach($RevenueBasic as $rev_)
            {
                $NewData[$rev_['cm']['branch']][$rev_['cm']['id']]['Basic']['revenue'] =  round($rev_['pm']['provision'],2);
                $cost_master[$rev_['cm']['branch']][] = $rev_['cm']['id'];
                $BranchArr[] =  $rev_['cm']['branch'];
            }

            //print_r($NewData); exit;

            //$NewBasicBusiness = $this->DashboardBusPart->find('list',array('fields'=>array('EpId','Amount'),'conditions'=>array("FinanceYear"=>$finYear,'FinanceMonth'=>$finMonth,'Branch'=>$Branch)));
            //print_r($NewData); exit;
            $DirectActualBusinessCase = $this->Targets->query("SELECT ep.id,ExpenseTypeId,ep.Amount,cm.id,cm.branch FROM $budget_table_particular ep 
    INNER JOIN $budget_table_master em ON ep.ExpenseId = em.Id AND ExpenseType='CostCenter'
    INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id $BranchAll
    INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId='24' and EntryBy=''
    WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$finYear' AND em.FinanceMonth='$finMonth' 
     ");

            foreach($DirectActualBusinessCase as $DirectBC)
            {
                $NewData[$DirectBC['cm']['branch']][$DirectBC['cm']['id']]['Basic']['dc'] +=  $DirectBC['ep']['Amount'];   
                $cost_master[$DirectBC['cm']['branch']][] = $DirectBC['cm']['id'];
                $BranchArr[] =  $DirectBC['cm']['branch'];
            }

            $InDirectActualBusinessCase = $this->Targets->query("SELECT ep.id,ExpenseTypeId,ep.Amount,cm.id,cm.branch FROM $budget_table_particular ep 
    INNER JOIN $budget_table_master em ON ep.ExpenseId = em.Id 
    INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id $BranchAll
    INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId!='24' and EntryBy='' 
    WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$finYear' AND em.FinanceMonth='$finMonth' 
     ");

            foreach($InDirectActualBusinessCase as $InDirectBC)
            {    
                $NewData[$InDirectBC['cm']['branch']][$InDirectBC['cm']['id']]['Basic']['idc'] +=  $InDirectBC['ep']['Amount'];    
                $cost_master[$InDirectBC['cm']['branch']][] = $InDirectBC['cm']['id'];
                $BranchArr[] =  $InDirectBC['cm']['branch'];
            }

            $BranchArr = array_unique($BranchArr);
            
            foreach($BranchArr as $Branch)
            {
                $NewCostMaster[$Branch] = array_unique($cost_master[$Branch]);
            }
            //$cost_master = $NewCostMaster;
            //$cost_master = array_unique($cost_master);
            $newCostMaster = array();
            foreach($NewCostMaster as $k=>$v)
            {
                $cost_arr = $this->CostCenterMaster->find("all",array("conditions"=>array('id'=>$v)));
                

                foreach($cost_arr as $cost)
                {
                    $newCostMaster[$k][$cost['CostCenterMaster']['id']]['PrcoessName'] = $cost['CostCenterMaster']['process_name'];
                    $newCostMaster[$k][$cost['CostCenterMaster']['id']]['CostCenter'] = $cost['CostCenterMaster']['cost_center'];
                }
            }
            
           // print_r($cost_master); exit;
            
            sort($BranchArr);
            $this->set('BranchArr',$BranchArr);
            $this->set('CostCenter',$newCostMaster);
            $this->set('Data',$NewData); 

    }
        else if($ReportType== 'CostCenter')
        {
            if($cost_id!='All')
            {
                $cost_id = " and cm.id='$cost_id'";
            }
            else
            {
                $cost_id = '';
            }
            
           $AspirationalQry = "SELECT * FROM `dashboard_Target` dt
INNER JOIN cost_master cm ON dt.cost_centerId=cm.id $cost_id
WHERE dt.FinanceYear='$finYear' AND dt.FinanceMonth='$finMonth' and dt.branch='$Branch'  group by cost_centerId "; 
        
        $AspirationalData = $this->Targets->query($AspirationalQry);
        
        
        
        
        foreach($AspirationalData as $asp)
        {
            $NewData[$asp['dt']['cost_centerId']]['Asp']['revenue'] =  $asp['dt']['target'];
            $NewData[$asp['dt']['cost_centerId']]['Asp']['dc'] =  $asp['dt']['target_directCost'];
            $NewData[$asp['dt']['cost_centerId']]['Asp']['idc'] =  $asp['dt']['target_IDC'];
            $cost_master[] = $asp['cm']['id'];
        }
      
        $Actual = $this->Targets->query("SELECT cm.id,dd.branch,cost_centerId,branch_process,
`commit` Revenue,
direct_cost DirectCost,
indirect_cost InDirectCost
FROM `dashboard_data` dd
INNER JOIN cost_master cm ON dd.cost_centerId=cm.id $cost_id
WHERE YEAR(dd.createdate)=YEAR(CURDATE())  AND dd.FinanceYear='$finYear' AND dd.FinanceMonth='$finMonth' AND  dd.branch='$Branch'  AND 
dd.createdate = (SELECT MAX(createdate) FROM dashboard_data AS dd1 WHERE YEAR(dd.createdate)=YEAR(CURDATE())  
AND  dd1.FinanceYear='$finYear' AND dd1.FinanceMonth='$finMonth' AND dd1.branch='$Branch' AND dd.cost_centerId=dd1.cost_centerId)");
        
        foreach($Actual as $bas)
        {
            if(empty($TmpActual[$bas['dd']['cost_centerId']]))
            {
                $NewData[$bas['dd']['cost_centerId']]['Actual']['revenue'] =  $bas['dd']['Revenue'];
                $NewData[$bas['dd']['cost_centerId']]['Actual']['dc'] =  $bas['dd']['DirectCost'];
                $NewData[$bas['dd']['cost_centerId']]['Actual']['idc'] =  $bas['dd']['InDirectCost'];
            }
            else
            {
                if(empty($TmpActual[$bas['cm']['id']]['Actual']['revenue']!='') || $TmpActual[$bas['cm']['id']]['Actual']['revenue']!=null)
                {
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['revenue'] =  $TmpActual[$bas['cm']['id']]['Actual']['revenue'];
                }
                else
                {
                    
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['revenue'] =  $bas['dd']['Revenue'];
                }
                
                if($TmpActual[$bas['cm']['id']]['Actual']['dc']!='' || $TmpActual[$bas['cm']['id']]['Actual']['dc']!=null)
                {
                    
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['dc'] =  $TmpActual[$bas['cm']['id']]['Actual']['dc'];
                }
                else
                {
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['dc'] =  $bas['dd']['DirectCost']; 
                }
                
                if($TmpActual[$bas['cm']['id']]['Actual']['idc']!='' || $TmpActual[$bas['cm']['id']]['Actual']['idc']!=null)
                {
                    
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['idc'] =  $TmpActual[$bas['cm']['id']]['Actual']['idc'];
                }
                else
                {
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['idc'] =  $bas['dd']['InDirectCost'];
                }
            }
            $cost_master[] = $bas['cm']['id'];
        }
        
//       $OSActual = $this->Targets->query("SELECT cm.id,SUM(total) os FROM tbl_invoice ti
//INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
//WHERE cm.id='' AND ti.bill_no!='' AND ti.status='0' AND ti.finance_year='2018-19' AND ti.month='Oct-18'
//GROUP BY ti.month,cm.id");
//        
//        foreach($OSActual as $os_)
//        {
//            $NewData[$os_['cm']['id']]['Processed']['revenue'] =  round($os_['0']['os']/100000,2);
//            $cost_master[] = $os_['cm']['id'];
//        } 
       $NewFinanceMonth = $finMonth; 
    $monthArr = array('Jan','Feb','Mar'); 
        $split = explode('-',$finYear); 
        if(in_array($finMonth, $monthArr)) 
        {
            $NewFinanceMonth .= '-'.$split[1];    //Year from month
        }
        else
        {
            $NewFinanceMonth .= '-'.($split[1]-1);    //Year from month
        }
       
        
        
        $RevenueBasic = $this->Targets->query("SELECT cm.id,pm.provision FROM $revnue_table pm
LEFT JOIN 
(
SELECT ti.cost_center,ti.month,SUM(ti.total) total FROM tbl_invoice ti
INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center $cost_id
 WHERE  ti.month='$NewFinanceMonth' group by cm.id) ti 
ON pm.month = ti.month AND pm.cost_center = ti.cost_center
INNER JOIN cost_master cm ON pm.cost_center=cm.cost_center $cost_id
WHERE pm.branch_name='$Branch' and pm.month='$NewFinanceMonth'");
  
        foreach($RevenueBasic as $rev_)
        {
            $NewData[$rev_['cm']['id']]['Basic']['revenue'] =  round($rev_['pm']['provision'],2);
            $cost_master[] = $rev_['cm']['id'];
        }
        
        //print_r($NewData); exit;
        
        //$NewBasicBusiness = $this->DashboardBusPart->find('list',array('fields'=>array('EpId','Amount'),'conditions'=>array("FinanceYear"=>$finYear,'FinanceMonth'=>$finMonth,'Branch'=>$Branch)));
        //print_r($NewData); exit;
        $DirectActualBusinessCase = $this->Targets->query("SELECT ep.id,ExpenseTypeId,ep.Amount,cm.id FROM $budget_table_particular ep 
INNER JOIN $budget_table_master em ON ep.ExpenseId = em.Id AND ExpenseType='CostCenter'
INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id $cost_id
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId='24' and EntryBy=''
WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$finYear' AND em.FinanceMonth='$finMonth' 
AND em.branch='$Branch' ");
        
        foreach($DirectActualBusinessCase as $DirectBC)
        {
            if(isset($NewBasicBusiness[$DirectBC['ep']['id']]))
            {
                
                $NewData[$DirectBC['cm']['id']]['Basic']['dc'] +=  $NewBasicBusiness[$DirectBC['ep']['id']];
            }
            else
            {
                $NewData[$DirectBC['cm']['id']]['Basic']['dc'] +=  $DirectBC['ep']['Amount'];
            }
            $cost_master[] = $DirectBC['cm']['id'];
        }
        
        $InDirectActualBusinessCase = $this->Targets->query("SELECT ep.id,ExpenseTypeId,ep.Amount,cm.id FROM $budget_table_particular ep 
INNER JOIN $budget_table_master em ON ep.ExpenseId = em.Id 
INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id $cost_id
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId!='24' and EntryBy='' 
WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$finYear' AND em.FinanceMonth='$finMonth' 
AND em.branch='$Branch' ");
        
        foreach($InDirectActualBusinessCase as $InDirectBC)
        {
            if(isset($NewBasicBusiness[$InDirectBC['ep']['id']]))
            {
                //echo $NewBasicBusiness[$InDirectBC['ep']['id']]; exit;
                $NewData[$InDirectBC['cm']['id']]['Basic']['idc'] +=  $NewBasicBusiness[$InDirectBC['ep']['id']];
            }
            else
            {
                $NewData[$InDirectBC['cm']['id']]['Basic']['idc'] +=  $InDirectBC['ep']['Amount'];
            }
            $cost_master[] = $InDirectBC['cm']['id'];
        }
        
        
        $cost_master = array_unique($cost_master);
        $cost_arr = $this->CostCenterMaster->find("all",array("conditions"=>array('id'=>$cost_master)));
        $newCostMaster = array();
        
        foreach($cost_arr as $cost)
        {
            $cost_name = implode("/",$cost['CostCenterMaster']['cost_center']);
            $cnt = count($cost_name);
            $new_cost_name = $cost_name[$cnt-2].$cost_name[$cnt-1];
            $newCostMaster[$cost['CostCenterMaster']['id']]['PrcoessName'] = $cost['CostCenterMaster']['process_name'];
            $newCostMaster[$cost['CostCenterMaster']['id']]['CostCenter'] = $cost['CostCenterMaster']['cost_center'];
        }
        
        $this->set('CostCenter',$newCostMaster);
        $this->set('Data',$NewData); 
                    
        }
         

        
}
     public function get_report11()
    {
        $this->layout = "ajax";
      $result = $this->params->query; 
         $this->set("DataNew",$result);

        // get values here 
       // echo $result['barnch'];exit;
         if($result['select1']== 'Branch'){
              $this->set("Data",
           $this->DashboardData->query("SELECT cd, DATE_FORMAT(MAX(DATE(md)),'%d-%b-%y') md, SUM(cmt) `cmt`,SUM(dc) `dc`,SUM(idc)`idc`,branch,branch_process,bp,cc,cost_centerId,process_name,IF(mde IS NULL OR mde ='',cd,mde)mde,SUM(target) `target`,bar,bpro, SUM(target_directCost) `target_directCost`,SUM(target_IDC) `target_IDC` FROM (
SELECT *,n.brac branch FROM (SELECT DATE_FORMAT(SUBDATE(dd.createdate,1),'%b-%y') cd,ft.reatedate md,  SUM(dd.`commit`) cmt,
SUM(dd.`direct_cost`) dc, ROUND(SUM(dd.`indirect_cost`),2) idc,ft.branch brac,dd.branch_process,ft.bps bp,ft.cc,dd.cost_centerId,ft.process_name
 FROM dashboard_data dd 
JOIN (SELECT MAX(DATE(nd.createdate)) reatedate,nd.cost_centerId,nd.branch,nd.branch_process,fdp.tower bps,fdp.cost_center cc,fdp.company_name,fdp.id,fdp.process_name FROM dashboard_data nd JOIN `cost_master` fdp ON  
nd.cost_centerId = fdp.id WHERE   ( (DATE(fdp.close) IS NULL) OR (DATE(fdp.close) >= CURDATE()))
   GROUP BY cost_centerId ) ft
ON DATE(dd.createdate) = ft.reatedate AND ft.id=dd.cost_centerId    GROUP BY dd.cost_centerId )n
LEFT JOIN
(
SELECT DATE_FORMAT(dt.`month`,'%b-%y') mde, SUM(dt.target) target,dt.branch bar, dt.branch_process bpro,ld.company_name,
SUM(dt.target_directCost) target_directCost,SUM(dt.`target_IDC`) target_IDC ,dt.cost_centerId cci FROM `dashboard_Target` dt
JOIN
(SELECT MAX(DATE(pr.createdate)) reatedate,pr.cost_centerId,pr.branch,dp.tower brc,pr.branch_process,dp.id,dp.company_name FROM dashboard_data pr JOIN `cost_master` dp ON  
  pr.cost_centerId = dp.id WHERE   ((DATE(dp.close) IS NULL) OR (DATE(dp.close) >= CURDATE()))
GROUP BY pr.cost_centerId)ld
 ON DATE_FORMAT(SUBDATE(ld.reatedate, 1),'%b-%y') = DATE_FORMAT(dt.`month`,'%b-%y')
    AND dt.cost_centerId= ld.id  GROUP BY dt.cost_centerId  
)f ON f.mde = n.cd AND f.cci = n.cost_centerId  GROUP BY f.cci,n.cost_centerId
)t GROUP BY branch ORDER BY branch"
                   ));
               
         }
         
//    elseif($result['select1']== 'Process'){
//       $this->set("Data",
//           $this->DashboardData->query("SELECT cd, DATE_FORMAT(MAX(DATE(md)),'%d-%b-%y') md , SUM(cmt) `cmt`,SUM(dc) `dc`,SUM(idc)`idc`,branch,branch_process,bp,cc,cost_centerId,process_name,if(mde is null or mde ='',cd,mde)mde,SUM(target) `target`,bar,bpro, SUM(target_directCost) `target_directCost`,SUM(target_IDC) `target_IDC` FROM (
//SELECT *,n.brac branch FROM (SELECT DATE_FORMAT(SUBDATE(dd.createdate,1),'%b-%y') cd,ft.reatedate md,  SUM(dd.`commit`) cmt,
//SUM(dd.`direct_cost`) dc, ROUND(SUM(dd.`indirect_cost`),2) idc,ft.branch brac,dd.branch_process,ft.bps bp,ft.cc,dd.cost_centerId,ft.process_name
// FROM dashboard_data dd 
//JOIN (SELECT MAX(DATE(nd.createdate)) reatedate,nd.cost_centerId,nd.branch,nd.branch_process,fdp.tower bps,fdp.cost_center cc,fdp.company_name,fdp.id,fdp.process_name FROM dashboard_data nd JOIN `cost_master` fdp ON  
//nd.cost_centerId = fdp.id WHERE  ( (DATE(fdp.close) IS NULL) OR (DATE(fdp.close) >= CURDATE()))
//   GROUP BY cost_centerId ) ft
//ON DATE(dd.createdate) = ft.reatedate AND ft.id=dd.cost_centerId    GROUP BY dd.cost_centerId )n
//LEFT JOIN
//(
//SELECT DATE_FORMAT(dt.`month`,'%b-%y') mde, SUM(dt.target) target,dt.branch bar, dt.branch_process bpro,ld.company_name,
//SUM(dt.target_directCost) target_directCost,SUM(dt.`target_IDC`) target_IDC ,dt.cost_centerId cci FROM `dashboard_Target` dt
//JOIN
//(SELECT MAX(DATE(pr.createdate)) reatedate,pr.cost_centerId,pr.branch,dp.tower brc,pr.branch_process,dp.id,dp.company_name FROM dashboard_data pr JOIN `cost_master` dp ON  
//  pr.cost_centerId = dp.id  WHERE   ((DATE(dp.close) IS NULL) OR (DATE(dp.close) >= CURDATE()))
//GROUP BY pr.cost_centerId)ld
// ON  DATE_FORMAT(SUBDATE(ld.reatedate, 1),'%b-%y') = DATE_FORMAT(dt.`month`,'%b-%y')
//    AND dt.cost_centerId= ld.id  GROUP BY dt.cost_centerId  
//)f ON f.mde = n.cd AND f.cci = n.cost_centerId  GROUP BY f.cci,n.cost_centerId
//)t GROUP BY branch_process ORDER BY branch
//
// 
//
//"));
       
      
  //  }
    else if($result['barnch1']!=''&&$result['select1']== 'CostCenter')
    {     // echo $result['barnch'];exit;
        
 
     
    
     
     
     
     
      $this->set("Data",
           $this->DashboardData->query("SELECT cd, DATE_FORMAT(MAX(DATE(md)),'%d-%b-%y') md , SUM(cmt) `cmt`,SUM(dc) `dc`,SUM(idc)`idc`,branch,branch_process,bp,cc,cost_centerId,process_name,if(mde is null or mde ='',cd,mde)mde,SUM(target) `target`,bar,bpro, SUM(target_directCost) `target_directCost`,SUM(target_IDC) `target_IDC` FROM (SELECT * FROM (SELECT DATE_FORMAT(SUBDATE(dd.createdate,1),'%b-%y') cd,ft.reatedate md,  SUM(dd.`commit`) cmt,
SUM(dd.`direct_cost`) dc, ROUND(SUM(dd.`indirect_cost`),2) idc,dd.branch,dd.branch_process,ft.bps bp,ft.cc,dd.cost_centerId,ft.process_name
 FROM dashboard_data dd 
JOIN (SELECT MAX(DATE(nd.createdate)) reatedate,nd.cost_centerId,fdp.Branch,nd.branch_process,fdp.tower bps,fdp.cost_center cc,fdp.id,fdp.process_name FROM dashboard_data nd JOIN `cost_master` fdp ON  
nd.cost_centerId = fdp.id AND ( (DATE(fdp.close) IS NULL) OR (DATE(fdp.close) >= CURDATE()))
 WHERE fdp.branch = '{$result['barnch1']}' GROUP BY cost_centerId ) ft
ON DATE(dd.createdate) = ft.reatedate AND ft.id=dd.cost_centerId WHERE  dd.branch = '{$result['barnch1']}' GROUP BY dd.cost_centerId )n
LEFT JOIN
(
SELECT DATE_FORMAT(dt.`month`,'%b-%y') mde, SUM(dt.target) target,dt.branch bar, dt.branch_process `bpro`,
SUM(dt.target_directCost) target_directCost,SUM(dt.`target_IDC`) target_IDC ,dt.cost_centerId `cci` FROM `dashboard_Target` dt
JOIN
(SELECT MAX(DATE(pr.createdate)) reatedate,pr.cost_centerId,pr.branch,dp.tower brc,pr.branch_process ,dp.id FROM dashboard_data pr JOIN `cost_master` dp ON  
  pr.cost_centerId = dp.id WHERE pr.branch = '{$result['barnch1']}' AND ((DATE(dp.close) IS NULL) OR (DATE(dp.close) >= CURDATE()))
GROUP BY pr.cost_centerId)ld
 ON  DATE_FORMAT(SUBDATE(ld.reatedate, 1),'%b-%y') = DATE_FORMAT(dt.`month`,'%b-%y')
    AND dt.cost_centerId= ld.id WHERE dt.branch = '{$result['barnch1']}' GROUP BY dt.cost_centerId  
)f ON f.mde = n.cd AND f.cci = n.cost_centerId  GROUP BY f.cci,n.cost_centerId)t GROUP BY cost_centerId

"));  
    
        
    }
       
        
    }
    
    public function view_process_report()
    {
        $this->layout="home";
        $finYear = $this->params->query['finYear'];
        $finMonth = $this->params->query['finMonth'];
        $Branch = $this->params->query['Branch'];
        $this->set('finYear',$finYear);
        $this->set('finMonth',$finMonth);
        $this->set('Branch',$Branch);
        
        $revnue_table = 'provision_master';
         $budget_table_master = 'expense_master';
         $budget_table_particular = 'expense_particular';
         if($this->FreezeData->find('first',array('conditions'=>"Branch='$Branch' and FinanceYear='$finYear' and FinanceMonth='$finMonth' and ApproveStatus='2'")))
         {
            $revnue_table = 'dashboard_save_prov';
            $budget_table_master = 'expense_master_old';
            $budget_table_particular = 'expense_particular_old';
         }
        
        
        
            
            
           $AspirationalQry = "SELECT * FROM `dashboard_Target` dt
INNER JOIN cost_master cm ON dt.cost_centerId=cm.id $cost_id
WHERE dt.FinanceYear='$finYear' AND dt.FinanceMonth='$finMonth' and dt.branch='$Branch'  group by cost_centerId "; 
        
        $AspirationalData = $this->Targets->query($AspirationalQry);
        
        //print_r($AspirationalData); exit;
        
        
        foreach($AspirationalData as $asp)
        {
            $NewData[$asp['dt']['cost_centerId']]['Asp']['revenue'] =  $asp['dt']['target'];
            $NewData[$asp['dt']['cost_centerId']]['Asp']['dc'] =  $asp['dt']['target_directCost'];
            $NewData[$asp['dt']['cost_centerId']]['Asp']['idc'] =  $asp['dt']['target_IDC'];
            $cost_master[] = $asp['cm']['id'];
        }
      
        $Actual = $this->Targets->query("SELECT cm.id,dd.branch,cost_centerId,branch_process,
`commit` Revenue,
direct_cost DirectCost,
indirect_cost InDirectCost
FROM `dashboard_data` dd
INNER JOIN cost_master cm ON dd.cost_centerId=cm.id $cost_id
WHERE YEAR(dd.createdate)=YEAR(CURDATE())  AND dd.FinanceYear='$finYear' AND dd.FinanceMonth='$finMonth' AND  dd.branch='$Branch'  AND 
dd.createdate = (SELECT MAX(createdate) FROM dashboard_data AS dd1 WHERE YEAR(dd.createdate)=YEAR(CURDATE())  
AND  dd1.FinanceYear='$finYear' AND dd1.FinanceMonth='$finMonth' AND dd1.branch='$Branch' AND dd.cost_centerId=dd1.cost_centerId)");
        
        foreach($Actual as $bas)
        {
            if(empty($TmpActual[$bas['dd']['cost_centerId']]))
            {
                $NewData[$bas['dd']['cost_centerId']]['Actual']['revenue'] =  $bas['dd']['Revenue'];
                $NewData[$bas['dd']['cost_centerId']]['Actual']['dc'] =  $bas['dd']['DirectCost'];
                $NewData[$bas['dd']['cost_centerId']]['Actual']['idc'] =  $bas['dd']['InDirectCost'];
            }
            else
            {
                if(empty($TmpActual[$bas['cm']['id']]['Actual']['revenue']!='') || $TmpActual[$bas['cm']['id']]['Actual']['revenue']!=null)
                {
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['revenue'] =  $TmpActual[$bas['cm']['id']]['Actual']['revenue'];
                }
                else
                {
                    
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['revenue'] =  $bas['dd']['Revenue'];
                }
                
                if($TmpActual[$bas['cm']['id']]['Actual']['dc']!='' || $TmpActual[$bas['cm']['id']]['Actual']['dc']!=null)
                {
                    
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['dc'] =  $TmpActual[$bas['cm']['id']]['Actual']['dc'];
                }
                else
                {
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['dc'] =  $bas['dd']['DirectCost']; 
                }
                
                if($TmpActual[$bas['cm']['id']]['Actual']['idc']!='' || $TmpActual[$bas['cm']['id']]['Actual']['idc']!=null)
                {
                    
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['idc'] =  $TmpActual[$bas['cm']['id']]['Actual']['idc'];
                }
                else
                {
                    $NewData[$bas['dd']['cost_centerId']]['Actual']['idc'] =  $bas['dd']['InDirectCost'];
                }
            }
            $cost_master[] = $bas['cm']['id'];
        }
        
//       $OSActual = $this->Targets->query("SELECT cm.id,SUM(total) os FROM tbl_invoice ti
//INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
//WHERE cm.id='' AND ti.bill_no!='' AND ti.status='0' AND ti.finance_year='2018-19' AND ti.month='Oct-18'
//GROUP BY ti.month,cm.id");
//        
//        foreach($OSActual as $os_)
//        {
//            $NewData[$os_['cm']['id']]['Processed']['revenue'] =  round($os_['0']['os']/100000,2);
//            $cost_master[] = $os_['cm']['id'];
//        } 
       $NewFinanceMonth = $finMonth; 
    $monthArr = array('Jan','Feb','Mar'); 
        $split = explode('-',$finYear); 
        if(in_array($finMonth, $monthArr)) 
        {
            $NewFinanceMonth .= '-'.$split[1];    //Year from month
        }
        else
        {
            $NewFinanceMonth .= '-'.($split[1]-1);    //Year from month
        }
       
        
        
        $RevenueBasic = $this->Targets->query("SELECT cm.id,pm.provision FROM $revnue_table pm
LEFT JOIN 
(
SELECT ti.cost_center,ti.month,SUM(ti.total) total FROM tbl_invoice ti
INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center $cost_id
 WHERE  ti.month='$NewFinanceMonth' group by cm.id) ti 
ON pm.month = ti.month AND pm.cost_center = ti.cost_center
INNER JOIN cost_master cm ON pm.cost_center=cm.cost_center $cost_id
WHERE pm.branch_name='$Branch' and pm.month='$NewFinanceMonth'");
  
        foreach($RevenueBasic as $rev_)
        {
            $NewData[$rev_['cm']['id']]['Basic']['revenue'] =  round($rev_['pm']['provision'],2);
            $cost_master[] = $rev_['cm']['id'];
        }
        
        //print_r($NewData); exit;
        
        //$NewBasicBusiness = $this->DashboardBusPart->find('list',array('fields'=>array('EpId','Amount'),'conditions'=>array("FinanceYear"=>$finYear,'FinanceMonth'=>$finMonth,'Branch'=>$Branch)));
        //print_r($NewData); exit;
        $DirectActualBusinessCase = $this->Targets->query("SELECT ep.id,ExpenseTypeId,ep.Amount,cm.id FROM $budget_table_particular ep 
INNER JOIN $budget_table_master em ON ep.ExpenseId = em.Id AND ExpenseType='CostCenter'
INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id $cost_id
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId='24' and EntryBy=''
WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$finYear' AND em.FinanceMonth='$finMonth' 
AND em.branch='$Branch' ");
        
        foreach($DirectActualBusinessCase as $DirectBC)
        {
            if(isset($NewBasicBusiness[$DirectBC['ep']['id']]))
            {
                
                $NewData[$DirectBC['cm']['id']]['Basic']['dc'] +=  $NewBasicBusiness[$DirectBC['ep']['id']];
            }
            else
            {
                $NewData[$DirectBC['cm']['id']]['Basic']['dc'] +=  $DirectBC['ep']['Amount'];
            }
            $cost_master[] = $DirectBC['cm']['id'];
        }
        
        $InDirectActualBusinessCase = $this->Targets->query("SELECT ep.id,ExpenseTypeId,ep.Amount,cm.id FROM $budget_table_particular ep 
INNER JOIN $budget_table_master em ON ep.ExpenseId = em.Id 
INNER JOIN cost_master cm ON ep.ExpenseTypeId = cm.id $cost_id
INNER JOIN `tbl_bgt_expenseheadingmaster` hm ON em.HeadId = hm.HeadingId AND hm.HeadingId!='24' and EntryBy='' 
WHERE ep.ExpenseType='CostCenter' AND em.FinanceYear='$finYear' AND em.FinanceMonth='$finMonth' 
AND em.branch='$Branch' ");
        
        foreach($InDirectActualBusinessCase as $InDirectBC)
        {
            if(isset($NewBasicBusiness[$InDirectBC['ep']['id']]))
            {
                //echo $NewBasicBusiness[$InDirectBC['ep']['id']]; exit;
                $NewData[$InDirectBC['cm']['id']]['Basic']['idc'] +=  $NewBasicBusiness[$InDirectBC['ep']['id']];
            }
            else
            {
                $NewData[$InDirectBC['cm']['id']]['Basic']['idc'] +=  $InDirectBC['ep']['Amount'];
            }
            $cost_master[] = $InDirectBC['cm']['id'];
        }
        
        
        $cost_master = array_unique($cost_master);
        $cost_arr = $this->CostCenterMaster->find("all",array("conditions"=>array('id'=>$cost_master)));
        $newCostMaster = array();
        
        foreach($cost_arr as $cost)
        {
            $cost_name = explode("/",$cost['CostCenterMaster']['cost_center']);
            $cnt = count($cost_name);
            $new_cost_name = $cost_name[$cnt-2].'/'.$cost_name[$cnt-1];
            $newCostMaster[$cost['CostCenterMaster']['id']]['PrcoessName'] = substr($cost['CostCenterMaster']['process_name'],0,12);
            $newCostMaster[$cost['CostCenterMaster']['id']]['CostCenter'] = $new_cost_name;
        }
        //print_r($NewData); exit;
        $this->set('CostCenter',$newCostMaster);
        $this->set('Data',$NewData); 
                    
        
        
        
        
    }
     
    
    
    
    
    
    
    
       
    }

?>