<?php
class ModelExtensionModuleFeedbackRequest extends Model {
	public function install() {
		$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "feedback_request` (
				`feedback_request_id` INT(11) NOT NULL AUTO_INCREMENT,
				`name` varchar(32) NOT NULL,
				`email` varchar(64) NOT NULL,
				`phone` varchar(32) NOT NULL,
				`status` INT(11) NOT NULL,
				`date` DATETIME NOT NULL,
				PRIMARY KEY (`feedback_request_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");


	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "feedback_request`");
	}

    public function getFeedbackRequest()
    {
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'feedback_request ORDER BY `date` DESC';

        $query = $this->db->query($sql);

        return $query->rows;
    }

}
