<?php
class ModelExtensionModuleFeedbackRequest extends Model {

    public function addFeedbackRequest($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "feedback_request SET name = '" . $data['name'] . "', email = '" . $data['email'] . "', phone = '" . $data['email'] . "', status = '0', `date` = NOW() ");

    }

}
