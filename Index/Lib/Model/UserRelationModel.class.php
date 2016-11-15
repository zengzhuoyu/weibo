<?php

	/**
	 * 用户与用户信息表关联模型
	 */
	Class UserRelationModel extends RelationModel {//UserRelationModel 得与文件名一致 | RelationModel 

		//UserRelationModel 表示该模型对应的表是UserRelation，所以下面要进行主表名称的定义
		
		//定义主表名称
		Protected $tableName = 'user';

		//定义用户与用户信处表关联关系属性
		Protected $_link = array(
			'userinfo' => array(//关联userinfo表，想关联其他表可以继续往下写
				'mapping_type' => HAS_ONE,//HAS_ONE：一对一关系 HAS_MANY：一对多关系
				'foreign_key' => 'uid'//关联的外键
				)
			);

		/**
		 * 自动插入的方法
		 */
		Public function insert ($data=NULL) {
			$data = is_null($data) ? $_POST : $data;
			return $this->relation(true)->data($data)->add();
		}
	}
?>