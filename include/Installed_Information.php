<?php
    class Installed_Information
    {
        public function __construct($operating_system, $host_address,$community)
        {
            $this->operating_system = $operating_system;
            $this->host_address     = $host_address;
            $this->community = $community;
            if($this->operating_system == 'windows')
            {
                $this->create_data_array();
            }
            if($this->operating_system == 'linux')
            {
                $this->create_data_array();
            }
        }

        private function create_data_array()
        {
        
        $soft = array();
		$result = snmpwalk($this->host_address, $this->community, '.iso.org.dod.internet.mgmt.mib-2.host.hrSWInstalled.hrSWInstalledTable.hrSWInstalledEntry');
        $soft = array_chunk($result,count($result)/5);
//            $hrSWInstalledIndex = array();
//            $hrSWInstalledIndex = snmpwalkoid($this->host_address, $this->community, ".iso.org.dod.internet.mgmt.mib-2.host.hrSWInstalled.hrSWInstalledTable.hrSWInstalledEntry.hrSWInstalledIndex");
            if($soft != FALSE)
            {
                if(count($soft) > 0)
                {
                    $this->data_fetched = 'yes';
                    
                    $installed_type = array();
                    $installed_name  = array();
                    $installed_date  = array();
                    for($i=0; $i<count($soft[3]); $i++)
                    {
                        $installed_type[$i] = str_replace('INTEGER: ', '', $soft[3][$i]);
                    }

                    for($i=0; $i<count($soft[1]); $i++)
                    {
                        $split = explode(':', $soft[1][$i], 2);
                        if ( strcmp($split[0], 'Hex-STRING') == 0 ) {
                        //		if ($hex) {
			                $hex = str_replace(' ', '', $split[1]);
                            //$snmp = str_replace(' ', '', $snmp);
			                $hex = preg_replace('/[^a-zA-Z0-9]+/', '', $hex);
			                $hex = hex2bin($hex);
		                    //}
		                    //$snmp = trim($snmp);
                            //$output .= hexStr2Ascii($split[1]);
                            //$hex = str_replace(' ', '', $split[1]);
                            //$hex = str_replace('\r', '', $hex);
                            //$hex = str_replace('\n', '', $hex);                            
                            //$hexStrArr = explode(' ',$hexStr);
                            //$hex='B0D9B6C8CDF8C5CC';
                            //$string='';
                            //for ($i=0; $i < strlen($hex)-1; $i+=2){
                            //    $string .= mb_chr(hexdec($hex[$i].$hex[$i+1]));
                            //}
    
                            //$utf = "";
                            //foreach($hexStrArr as $octet){
                                //$codes = hexdec($octet);
                                //if ($char > 0 ) { $asciiOut .= chr($char); }
                                //if (is_scalar($codes)) $codes= func_get_args();
                                //$str= '';
                               // foreach ($codes as $code) $str.= html_entity_decode('&#'.$code.';',ENT_NOQUOTES,'UTF-8');
                                //$utf=$utf.$str;
                            //}
                            
                            //$string='';
                            //for ($j=0; $j < strlen($hex)-1; $j+=2){
                            //    $string .= mb_chr(hexdec($hex[$j].$hex[$j+1]));
                            //}
                            //mb_convert_encoding($hex, 'utf-8', 'gbk');
                            $installed_name[$i] = $hex;
                            //$str = mb_convert_encoding($str, "UTF-7", "EUC-JP");
                        } else{
                            $data = str_replace('STRING: ', '', $soft[1][$i]);
                            $data = str_replace('"', '', $data);
                            $installed_name[$i] = $data;
                        }
                    }
                    for($i=0; $i<count($soft[4]); $i++)
                    {
                        $data = str_replace('STRING: ', '', $soft[4][$i]);
                        $installed_date[$i] = $data;
                    }
                    $this->data_array[0] = $installed_type;
                    $this->data_array[1] = $installed_name;
                    $this->data_array[2] = $installed_date;
                }
                else
                {
                    $this->data_fetched = 'no';
                }
            }
            else
            {
                
            }
        }
        public function get_data_array()
        {
            return $this->data_array;
        }
        public function __destruct()
        {
            /*
             * No code needed here
             */
        }
        public $data_fetched;
        private $data_array = array();
        private $operating_system;
        private $host_address;
        private $community;
    }
?>