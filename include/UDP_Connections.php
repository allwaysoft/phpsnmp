<?php
error_reporting(0);
    class UDP_Connections
    {
        public function __construct($operating_system, $host_address,$community)
        {
            $this->operating_system = $operating_system;
            $this->host_address     = $host_address;
            $this->community = $community;
            if($this->operating_system == 'windows')
            {
                $this->create_data_array_windows();
            }
            if($this->operating_system == 'linux')
            {
                $this->create_data_array_windows();
            }
        }
        private function create_data_array_windows()
        {
            $udp = array();
            $result = snmpwalk($this->host_address, $this->community, '.iso.org.dod.internet.mgmt.mib-2.udp.udpTable.udpEntry');
            $udp = array_chunk($result,count($result)/2);
        
            $udpLocalAddress        = $udp[0];//snmpwalkoid($this->host_address, $this->community, ".iso.org.dod.internet.mgmt.mib-2.udp.udpTable.udpEntry.udpLocalAddress");
            $udpLocalPort           = $udp[1];//snmpwalkoid($this->host_address, $this->community, ".iso.org.dod.internet.mgmt.mib-2.udp.udpTable.udpEntry.udpLocalPort");
            if(
                $udpLocalAddress       != FALSE and
                $udpLocalPort != FALSE
            )
            {
                
                $length   = count($udpLocalAddress);
                $count    = array();
                $count[0] = count($udpLocalAddress);
                $count[1] = count($udpLocalPort);
                for($i=0; $i<count($count); $i++)
                {
                    if($count[$i] == $length)
                    {
                        $this->data_fetched = 'yes';
                    }
                    else
                    {
                        $this->data_fetched = 'no';
                    }
                }
            }
            else
            {
                $this->data_fetched = 'no';
            }
            if($this->data_fetched == 'yes')
            {
                $this->populate_data_array($udpLocalAddress, $udpLocalPort);
            }
        }
        private function populate_data_array($udpLocalAddress, $udpLocalPort)
        {
            $LocalAddress     = array();
            $LocalPort       = array();
                        
                        //---------------------------------------------------------------------------------------
            $i = 0;
            foreach($udpLocalAddress as $key => $value)
            {
                $data       = str_replace('IpAddress: ', '', $value);
                $LocalAddress[$i] = $data;
                $i = $i + 1;
            }
            $udpLocalAddress = null;

 
                        //---------------------------------------------------------------------------------------
                        
            $i = 0;
            foreach($udpLocalPort as $key => $value)
            {
                $data       = str_replace('INTEGER: ', '', $value);
                $LocalPort[$i] = $data;
                $i = $i + 1;
            }
            $udpLocalPort = null;
            //---------------------------------------------------------------------------------------

                                    
            $this->data_array[0] = $LocalAddress;
            $this->data_array[1] = $LocalPort;
                                                
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
        private $operating_system;
        private $host_address;
        private $community;
        private $data_array = array();
    }
?>