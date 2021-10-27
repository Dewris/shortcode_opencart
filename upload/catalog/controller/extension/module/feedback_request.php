<?php
class ControllerExtensionModuleFeedbackRequest extends Controller {
    private $error = array();

    public function eventshortcode(&$route, &$data, &$output) {
        if ($this->config->get('module_feedback_request_status')){
            if (preg_match_all('/\{\#(.*?)\\#\}/s', $output, $matches, PREG_SET_ORDER)){
                foreach ($matches as $match) {
                    $output = str_replace($match[0], $this->getForm($match[1]), $output);
                }
            }
        }
    }

    public function add_feedback_request() {
        $json = array();
        $this->load->language('extension/module/feedback_request');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->load->model('extension/module/feedback_request');
            $this->model_extension_module_feedback_request->addFeedbackRequest($this->request->post);
            $json['success'] = true;
        }

        if (isset($this->error['name'])) {
            $json['errors']['name'] = $this->error['name'];
        }

        if (isset($this->error['email'])) {
            $json['errors']['email'] = $this->error['email'];
        }

        if (isset($this->error['phone'])) {
            $json['errors']['phone'] = $this->error['phone'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getForm() {
        $this->load->language('extension/module/feedback_request');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_phone'] = $this->language->get('entry_phone');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_submit'] = $this->language->get('entry_submit');

        return $this->load->view('extension/module/feedback_request',$data);
    }

    protected function validate() {
        $this->load->language('extension/module/feedback_request');

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ((utf8_strlen($this->request->post['phone']) < 7) || (utf8_strlen($this->request->post['phone']) > 15)) {
            $this->error['phone'] = $this->language->get('error_phone');
        }

        return !$this->error;
    }

}