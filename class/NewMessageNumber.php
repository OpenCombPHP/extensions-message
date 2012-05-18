<?php
namespace org\opencomb\message;

use org\jecat\framework\db\DB;

use org\jecat\framework\auth\IdManager;
use org\jecat\framework\db\ExecuteException;
use org\jecat\framework\db\sql\Order;
use org\opencomb\coresystem\mvc\controller\Controller;

class NewMessageNumber extends Controller
{
/**
 * @example /MVC模式/模型/查询/随机排序
 * 
 */
	public function createBeanConfig()
	{
		return array(
			'model:message'=>array(
				'class' => 'model' ,
				'orm'=>array(
					'table'=>'myspace_notification',
			        'disableTableTrans' => true,
                    'keys'=>array('id') ,
				),
                'list'=>true,
			),
			'model:message_bbs'=>array(
				'class' => 'model' ,
				'orm'=>array(
					'table'=>'bbs_home_notification',
			        'disableTableTrans' => true,
                    'keys'=>array('id') ,
				),
                'list'=>true,
			),
		);
	}

	public function process()
	{
	    $aId = IdManager::singleton()->currentId() ;
	    
	    $sSql[] = 'uid = @uid';
	    $sSql[] = 'new = @new';
	    
	    $arrParamsForSql['@uid'] = $aId->userId(); 
	    $arrParamsForSql['@new'] = 1; 
	    
	    $this->message->loadSql(implode(" and ", $sSql),$arrParamsForSql) ;
	    
	    \org\jecat\framework\db\DB::singleton()->selectDB("bbs");
	    $this->message_bbs->loadSql(implode(" and ", $sSql),$arrParamsForSql) ;
	    \org\jecat\framework\db\DB::singleton()->selectDB("www");
	    
	    
	    $arr['wownei'] = $this->message->childrenCount();
	    $arr['bbs'] = $this->message_bbs->childrenCount();
	    
	    echo json_encode($arr);
	    
		exit;
	}
}
?>