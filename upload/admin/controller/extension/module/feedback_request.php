<?php
class ControllerExtensionModuleFeedbackRequest extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/feedback_request');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$this->load->model('extension/module/feedback_request');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_feedback_request', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }


		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/feedback_request', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/feedback_request', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_feedback_request_status'])) {
			$data['module_feedback_request_status'] = $this->request->post['module_feedback_request_status'];
		} else {
			$data['module_feedback_request_status'] = $this->config->get('module_feedback_request_status');
		}
        $data['feedback_requests'] = array();

        $results = $this->model_extension_module_feedback_request->getFeedbackRequest();

        foreach ($results as $result) {
            $data['feedback_requests'][] = array(
                'feedback_request_id'  => $result['feedback_request_id'],
                'name'       => $result['name'],
                'phone'       => $result['phone'],
                'email'   => $result['email'],
                'date' => date($this->language->get('date_format_short'), strtotime($result['date'])),
                'status'     => ($result['status'] ? $this->language->get('text_done') : $this->language->get('text_waiting')),
            );
        }



		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/feedback_request', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/feedback_request')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

    public function install() {
        $this->load->model('extension/module/feedback_request');
        $this->load->model('setting/event');
        $this->model_setting_event->addEvent('feedback_request', 'catalog/view/*/*/after', 'extension/module/feedback_request/eventshortcode');
        $this->model_extension_module_feedback_request->install();
    }

    public function uninstall() {
        $this->load->model('extension/module/feedback_request');
        $this->load->model('setting/event');
        $this->model_setting_event->deleteEventByCode('feedback_request');
        $this->model_extension_module_feedback_request->uninstall();
    }
}