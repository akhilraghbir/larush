<?php
class Messages
{

	/*******************Back End Template ****************************/

	function setMessage($message, $type = 'success')
	{
		$this->CI = &get_instance();
		$data = array(
			'message' => preg_replace('/\s+/', ' ', trim($message)),
			'messageType' => ($type == 'success') ? $type : 'error',
		);
		$this->CI->session->set_userdata($data);
		return true;
	}

	function getMessage()
	{
		$this->CI = &get_instance();
		$message = $this->CI->session->userdata('message');
		$messageType = $this->CI->session->userdata('messageType');
		$this->CI->session->unset_userdata('message');
		$this->CI->session->unset_userdata('messageType');
		$this->CI->session->set_userdata(array('message' => '', 'messageType' => ''));
		if (isset($message) && $message != '') {
			return 	'<script>
						var notif = true;
						var icon = "";
						var type = "'.$messageType.'";
						var message = "'.$message.'";
						</script>';
		} else {
        return 	'<script>
        		var notif = false;
        		var icon = "";
        		var type = "";
        		var message = "";
        		</script>';
		}
	}

	/*******************Front End Template ****************************/

	function setMessageFront($message, $type = 'success')
	{
		$this->CI = &get_instance();
		$data = array('message' => $message, 'messageType' => $type);
		$this->CI->session->set_userdata($data);
		return true;
	}

	function getMessageFront()
	{
		$this->CI = &get_instance();
		$message = $this->CI->session->userdata('message');
		$messageType = $this->CI->session->userdata('messageType');
		if ($messageType == 'error') {
			$messageType = 'danger';
		} else if ($messageType == 'warning') {
			$messageType = 'warning';
		}

		$this->CI->session->unset_userdata('message');
		$this->CI->session->unset_userdata('messageType');
		$this->CI->session->set_userdata(array('message' => '', 'messageType' => ''));
		if (isset($message) && $message != '') {
			return '<div class="alert alert-'.$messageType.' alert-dismissible fade show" role="alert">
			<i class="mdi mdi-block-helper me-2"></i>
			'.$message.'
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>';
		} else {
			return '';
		}
	}
}
