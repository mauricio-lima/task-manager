DELIMITER ;;

DROP PROCEDURE IF EXISTS `task_insert`;;
CREATE PROCEDURE `task_insert`(IN insert_name VARCHAR(30), IN insert_description VARCHAR(600), IN insert_start DATE, IN insert_finish DATE, IN insert_status INT, IN insert_active INT)
BEGIN
	INSERT INTO `tasks` (`name`, `description`, `start`, `finish`, `status`, `active`) VALUES (insert_name, insert_description, insert_start, insert_finish, insert_status, insert_active);
END;;

DELIMITER ;