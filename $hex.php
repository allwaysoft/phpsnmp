<?php
$hex = FALSE;
		if (substr($snmp, 0, strlen('Hex-STRING: ')) === 'Hex-STRING: ') $hex = TRUE;
		if ($hex) {
			$snmp = str_replace('Hex-STRING: ', '', $snmp);
		} else {
			$snmp = str_replace('Counter32: ',  '', $snmp);
			$snmp = str_replace('INTEGER: ',    '', $snmp);
			$snmp = str_replace('IpAddress: ',  '', $snmp);
			$snmp = str_replace('STRING: ',     '', $snmp);
		}
		$snmp = str_replace('"', '', $snmp);
		if ($hex) {
			$snmp = str_replace(' ', '', $snmp);
			$snmp = preg_replace('/[^a-zA-Z0-9]+/', '', $snmp);
			$snmp = hex2bin($snmp);
		}
		$snmp = trim($snmp);
		return $snmp;
	}
?>


<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Snmp_core {

	private $CI;

	/**
	 * Hostname (IP address or DNS hostname).
	 * 
	 * @var string
	 * @access private
	 */
	private $hostname;

	/**
	 * Timeout.
	 * 
	 * (default value: 200000)
	 * 
	 * @var int
	 * @access private
	 */
	private $timeout = 200000; // 2 seconds

	public function __construct() {
		$this->CI =& get_instance();

		if( ! extension_loaded('snmp')) {
			show_error('PHP SNMP Extension is not loaded.');
			log_message('info', 'PHP SNMP Extension not loaded');
		}

		$config = $this->CI->config->load('ldap', TRUE, TRUE);
		if (isset($config['timeout'])) $this->timeout = $config['timeout'];
	}

	/**
	 * Set hostname.
	 * 
	 * @access public
	 * @param string $hostname
	 * @return void
	 */
	public function set_hostname($hostname) {
		$this->hostname = $hostname;
	}

	/**
	 * Get hostname.
	 * 
	 * @access public
	 * @return string|boolean
	 */
	public function get_hostname() {
		if (isset($this->hostname)) return $this->hostname;

		return FALSE;
	}

	/**
	 * Gets SNMP object ID.
	 * 
	 * @access public
	 * @param string $object_id
	 * @return string|boolean
	 */
	public function get($object_id) {
		if ( ! isset($this->hostname) || is_null($this->hostname)) return FALSE;

		if ( ! is_string($object_id)) return FALSE;

		$snmp = @snmpget($this->hostname, 'public', $object_id, $this->timeout);

		if ($snmp === FALSE) return FALSE;

		return $snmp;
	}

	/**
	 * Walks through SNMP object ID.
	 * 
	 * @access public
	 * @param string $object_id
	 * @return array|boolean
	 */
	public function walk($object_id) {
		if ( ! isset($hostname) || is_null($this->hostname)) return FALSE;

		if ( ! is_string($object_id)) return FALSE;

		$snmp = @snmpwalk($this->hostname, 'public', $object_id, $this->timeout);

		if ($snmp === FALSE) return FALSE;

		return $snmp;
	}

	/**
	 * Gets SNMP object ID with removed quotation marks.
	 * 
	 * @access public
	 * @param string $object_id
	 * @return string|boolean
	 */
	public function get_string($object_id) {
		$snmp = $this->get($object_id);

		if ($snmp === FALSE) return FALSE;

		$hex = FALSE;
		if (substr($snmp, 0, strlen('Hex-STRING: ')) === 'Hex-STRING: ') $hex = TRUE;

		if ($hex) {
			$snmp = str_replace('Hex-STRING: ', '', $snmp);
		} else {
			$snmp = str_replace('Counter32: ',  '', $snmp);
			$snmp = str_replace('INTEGER: ',    '', $snmp);
			$snmp = str_replace('IpAddress: ',  '', $snmp);
			$snmp = str_replace('STRING: ',     '', $snmp);
		}

		$snmp = str_replace('"', '', $snmp);

		if ($hex) {
			$snmp = str_replace(' ', '', $snmp);
			$snmp = preg_replace('/[^a-zA-Z0-9]+/', '', $snmp);
			$snmp = hex2bin($snmp);
		}

		$snmp = trim($snmp);

		return $snmp;
	}
}

/* End of file Snmp_core.php */
/* Location: ./application/libraries/Snmp_core.php */