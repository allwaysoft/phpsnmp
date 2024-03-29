<?php

class snmp_moniter{
	public $ip = false;
	public $community = 'public';	

	//系统信息合集
	public function info(){
		$info = array();

		//系统描述
		$info['description'] = $this->format(snmprealwalk($this->ip, $this->community, 'system.sysDescr.0'));
		//连续开机时间
		$info['uptime'] = $this->format(snmprealwalk($this->ip, $this->community, 'system.sysUpTime.0')); 
		//系统名称
		$info['name'] = $this->format(snmprealwalk($this->ip, $this->community, 'system.sysName.0'));
		//system time
		$info['systime'] = $this->format(snmprealwalk($this->ip, $this->community, 'HOST-RESOURCES-MIB::hrSystemDate.0'));

		return $info;
	}

	//当前连接
	public function netstat(){
		$result = snmprealwalk($this->ip, $this->community, '1.3.6.1.2.1.6.13.1.1');
		$return = array();
		foreach($result as $key => $value){
			$name = explode('.', $key);
			$return[] = array(
				$name[0],
				"{$name[1]}.{$name[2]}.{$name[3]}.{$name[4]}",
				$name[5],
				"{$name[6]}.{$name[7]}.{$name[8]}.{$name[9]}",
				$name[10],
				str_replace('INTEGER: ', '', $value)
			);
		}
		return $return;
	}

	//内存memory
	public function memory(){
		$memory = array('total' => false, 'used' => false, 'virtual' => false, 'swap' => false);
		$memory['total'] = $this->format(snmprealwalk($this->ip, $this->community, '1.3.6.1.2.1.25.2.2.0'));
		$result = snmprealwalk($this->ip, $this->community, '1.3.6.1.2.1.25.2');
		//print_r($result);
                echo '<br>';
		foreach($result as $key => $value){
			if($label = strstr($key , '3.6.1.2.1.25.2.3.1.3')){
                                var_dump(strstr($key , '3.6.1.2.1.25.2.3.1.3') ."<br>"); 
				echo 'value='.$this->format($value);  
				$label = explode('.', $label);
				$label = $label[1];
                                echo 'lab='."iso.3.6.1.2.1.25.2.3.1.5.{$label}";
				if(strcasecmp($this->format($value), 'Virtual Memory') == 0 && ($size = $this->format($result["iso.3.6.1.2.1.25.2.3.1.5.{$label}"])) != 0){
					$memory['virtual'][$label] = array(
						'used' => $this->format($result["iso.3.6.1.2.1.25.2.3.1.6.{$label}"]),
						'total' => $this->format($result["iso.3.6.1.2.1.25.2.3.1.5.{$label}"]),
					);
				}
				if(strcasecmp($this->format($value), 'Physical Memory') == 0 && ($size = $this->format($result["iso.3.6.1.2.1.25.2.3.1.5.{$label}"])) != 0){	
					$memory['used'] = $this->format($result["iso.3.6.1.2.1.25.2.3.1.6.{$label}"]); 
                                        $memory['total']  = $size;
				}
				if(strcasecmp($this->format($value), 'Swap space') == 0 && ($size = $this->format($result["HOST-RESOURCES-MIB::hrStorageSize.{$label}"])) != 0){
					$memory['swap'] = array(
						'used' => $this->format($result["HOST-RESOURCES-MIB::hrStorageUsed.{$label}"]),
						'total' => $size
					);

				}

			}
		}
                print_r($memory);
		return $memory;
	}

	//硬盘及使用率
	public function disk(){
		$disk = array();
		$result = snmprealwalk($this->ip, $this->community, '1.3.6.1.2.1.25.2');
                print_r($result);
		foreach($result as $key => $value){
			if($label = strstr($key , 'hrStorageDescr')){
				$label = explode('.', $label);
				$label = $label[1];
				if(($name = strstr($value, '/')) || strstr($value, '\\')){
					if($name === false) $name = $this->format($value);
					if(($size = $this->format($result["HOST-RESOURCES-MIB::hrStorageSize.{$label}"])) != 0){
						$disk[] = array(
							'name' => $name,
							'total' => $size,
							'used' => $this->format($result["HOST-RESOURCES-MIB::hrStorageUsed.{$label}"]) 
						);
					}
				}
			}
		}
		return $disk;
	}

	//获取设备列表
	public function device(){
		$device = array();
		$result = snmprealwalk($this->ip, $this->community, '1.3.6.1.2.1.25.3');
		foreach($result as $key => $value){
			if(!strstr($key, 'hrDeviceIndex')) break;
			$id = $this->format($value);
			$device[] = array(
				'type' => $this->format($result["HOST-RESOURCES-MIB::hrDeviceType.{$id}"]),
				'description' => $this->format($result["HOST-RESOURCES-MIB::hrDeviceDescr.{$id}"]),
			);
			
		}
		return $device;
	}

	//进程列表
	public function run(){
		$run = array();
		$result = snmprealwalk($this->ip, $this->community, '1.3.6.1.2.1.25.4');
		$performance = snmprealwalk($this->ip, $this->community, '1.3.6.1.2.1.25.5');
		if(isset($result['HOST-RESOURCES-MIB::hrSWOSIndex.0'])) unset($result['HOST-RESOURCES-MIB::hrSWOSIndex.0']);
		foreach($result as $key => $value){
			if(!strstr($key, 'hrSWRunIndex')) break;
			$id = $this->format($value);
			$run[] = array(
				'name' => $this->format($result["HOST-RESOURCES-MIB::hrSWRunName.{$id}"]),
				'path' => $this->format($result["HOST-RESOURCES-MIB::hrSWRunPath.{$id}"]),
				'parameter' => $this->format($result["HOST-RESOURCES-MIB::hrSWRunParameters.{$id}"]),
				'type' => $this->format($result["HOST-RESOURCES-MIB::hrSWRunType.{$id}"]),
				'status' => $this->format($result["HOST-RESOURCES-MIB::hrSWRunStatus.{$id}"]),
				'cpu' => $this->format($performance["HOST-RESOURCES-MIB::hrSWRunPerfCPU.{$id}"]),
				'memory' => $this->format($performance["HOST-RESOURCES-MIB::hrSWRunPerfMem.{$id}"])
			);
		}
		return $run;
	}

    	public function process(){
		$process = array();
		$result = snmpwalk($this->ip, $this->community, '1.3.6.1.2.1.25.4.2.1');
        $process = array_chunk($result,count($result)/7);
		return $process;
	}

    	public function tcp(){
		$tcp = array();
		$result = snmpwalk($this->ip, $this->community, '1.3.6.1.2.1.6.13.1');
        $tcp = array_chunk($result,count($result)/5);
		return $tcp;
	}

	//CPU
	public function cpu(){
		$cpu = array();
		$result = snmprealwalk($this->ip, $this->community, '1.3.6.1.2.1.25.3.3.1.2');
		foreach($result as $value){
			$cpu[] = $this->format($value);
		}
		return $cpu;	
	}




	private function format($result){
		if(!$result) return false;
		if(is_array($result)) $result = array_shift($result);
		$result = str_replace('STRING: ','', $result);
		$result = str_replace('INTEGER: ','', $result);
		$result = preg_replace('/^"(.*)"$/', '$1', $result);
		return $result;
	}

}

?>
