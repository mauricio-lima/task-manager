DELIMITER ;;
DROP PROCEDURE IF EXISTS `tasks_list`;;
CREATE PROCEDURE `tasks_list`()
BEGIN
		SELECT 
			task_id, `tasks`.name, description, `start`, `finish`, `status`.name AS `status`, `active` AS `state` 
		FROM 
			`tasks`
				LEFT JOIN 
			`status`
				ON tasks.status_id = `status`.status_id
		ORDER BY
			task_id;
				
END;;
DELIMITER ;


DELIMITER ;;
DROP PROCEDURE IF EXISTS `status_list`;;
CREATE PROCEDURE `status_list`()
BEGIN
		SELECT 
			status_id, `status`.name AS `name` 
		FROM 
			`status`
		ORDER BY
			status_id;				
END;;
DELIMITER ;


DELIMITER ;;
DROP PROCEDURE IF EXISTS `task_insert`;;
CREATE PROCEDURE `task_insert`(IN insert_name VARCHAR(30), IN insert_description VARCHAR(600), IN insert_start DATE, IN insert_finish DATE, IN insert_status INT, IN insert_active INT, OUT insert_json VARCHAR(1024) )
main:BEGIN
	IF DATE(insert_start) = DATE(0) THEN
		SET insert_json = '{ "success" : false, "code" : 201, "message" : "Invalid start date"  }';
		LEAVE main;
	END IF;

	IF DATE(insert_finish) = DATE(0) THEN
		SET insert_json = '{ "success" : false, "code" : 202, "message" : "Invalid finish date" }';
		LEAVE main;
	END IF;

	IF DATE(insert_finish) < DATE(insert_start) THEN
		SET insert_json = '{ "success" : false, "code" : 203, "message" : "Finish date is before start date" }';
		LEAVE main;
	END IF;
		
	INSERT INTO `tasks` (`name`, `description`, `start`, `finish`, `status_id`, `active`) VALUES (insert_name, insert_description, insert_start, insert_finish, insert_status, insert_active);
	
	SET insert_json = '{ "success" : true, 
	                     "record"  : { 
	                                  "task_id"      : $task_id$, 
	                                  "name"         : "$name$", 
	                                   "description" : "$description$",
	                                   "start"       : "$start$",
	                                   "finish"      : "$finish$",	                                    
	                                   "status"      :  $status$, 
	                                   "active"      :  $active$ 
	                                  } 
	                   }';
	SET insert_json = REPLACE(insert_json COLLATE utf8_general_ci, '$task_id$',     CAST(LAST_INSERT_ID() AS CHAR));
	SET insert_json = REPLACE(insert_json COLLATE utf8_general_ci, '$name$',        insert_name);
	SET insert_json = REPLACE(insert_json COLLATE utf8_general_ci, '$description$', insert_description);
	SET insert_json = REPLACE(insert_json COLLATE utf8_general_ci, '$start$',       insert_start);
	SET insert_json = REPLACE(insert_json COLLATE utf8_general_ci, '$finish$',      insert_finish);
	SET insert_json = REPLACE(insert_json COLLATE utf8_general_ci, '$status$',      insert_status);
	SET insert_json = REPLACE(insert_json COLLATE utf8_general_ci, '$active$',      insert_active);
END;;
DELIMITER ;

