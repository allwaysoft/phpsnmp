<?php
error_reporting(0);
    class TCP_Connections
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
            		$tcp = array();
		$result = snmpwalk($this->host_address, $this->community, '.iso.org.dod.internet.mgmt.mib-2.tcp.tcpConnTable.tcpConnEntry');
        $tcp = array_chunk($result,count($result)/5);
//		return $tcp;
            
//            $tcpConnState       = snmpwalkoid($this->host_address, $this->community, ".iso.org.dod.internet.mgmt.mib-2.tcp.tcpConnTable.tcpConnEntry.tcpConnState");
//            $tcpConnLocalAddress     = snmpwalkoid($this->host_address, $this->community, ".iso.org.dod.internet.mgmt.mib-2.tcp.tcpConnTable.tcpConnEntry.tcpConnLocalAddress");
//            $tcpConnLocalPort       = snmpwalkoid($this->host_address, $this->community, ".iso.org.dod.internet.mgmt.mib-2.tcp.tcpConnTable.tcpConnEntry.tcpConnLocalPort");
//            $tcpConnRemAddress = snmpwalkoid($this->host_address, $this->community, ".iso.org.dod.internet.mgmt.mib-2.tcp.tcpConnTable.tcpConnEntry.tcpConnRemAddress");
//            $tcpConnRemPort       = snmpwalkoid($this->host_address, $this->community, ".iso.org.dod.internet.mgmt.mib-2.tcp.tcpConnTable.tcpConnEntry.tcpConnRemPort");
            $tcpConnState               = $tcp[0];//snmpwalkoid($this->host_address, $this->community, ".iso.org.dod.internet.mgmt.mib-2.tcp.tcpConnTable.tcpConnEntry.tcpConnState");
            $tcpConnLocalAddress        = $tcp[1];//snmpwalkoid($this->host_address, $this->community, ".iso.org.dod.internet.mgmt.mib-2.tcp.tcpConnTable.tcpConnEntry.tcpConnLocalAddress");
            $tcpConnLocalPort           = $tcp[2];//snmpwalkoid($this->host_address, $this->community, ".iso.org.dod.internet.mgmt.mib-2.tcp.tcpConnTable.tcpConnEntry.tcpConnLocalPort");
            $tcpConnRemAddress          = $tcp[3];//snmpwalkoid($this->host_address, $this->community, ".iso.org.dod.internet.mgmt.mib-2.tcp.tcpConnTable.tcpConnEntry.tcpConnRemAddress");
            $tcpConnRemPort             = $tcp[4];//snmpwalkoid($this->host_address, $this->community, ".iso.org.dod.internet.mgmt.mib-2.tcp.tcpConnTable.tcpConnEntry.tcpConnRemPort");

            if(
                $tcpConnState       != FALSE and
                $tcpConnLocalAddress       != FALSE and
                $tcpConnLocalPort       != FALSE and
                $tcpConnRemAddress       != FALSE and
                $tcpConnRemPort != FALSE
            )
            {
                
                $length   = count($tcpConnState);
                $count    = array();
                $count[0] = count($tcpConnState);
                $count[1] = count($tcpConnLocalAddress);
                $count[2] = count($tcpConnLocalPort);
                $count[3] = count($tcpConnRemAddress);
                $count[4] = count($tcpConnRemPort);
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
                $this->populate_data_array($tcpConnState, $tcpConnLocalAddress, $tcpConnLocalPort, $tcpConnRemAddress,$tcpConnRemPort);
            }
        }
        private function populate_data_array($tcpConnState, $tcpConnLocalAddress, $tcpConnLocalPort, $tcpConnRemAddress,$tcpConnRemPort)
        {
            $State       = array();
            $LocalAddress     = array();
            $LocalPort       = array();
            $RemAddress = array();
            $RemPort         = array();
                        
            //---------------------------------------------------------------------------------------
            $i = 0;
            foreach($tcpConnState as $key => $value)
            { 
                $data       = str_replace('INTEGER: ', '', $value);
                $State[$i] = $data;
                $i = $i + 1;
            }
            $tcpConnState = null;
            //---------------------------------------------------------------------------------------
                        
            $i = 0;
            foreach($tcpConnLocalAddress as $key => $value)
            {
                $data       = str_replace('IpAddress: ', '', $value);
                $LocalAddress[$i] = $data;
                $i = $i + 1;
            }
            $tcpConnLocalAddress = null;

 
                        //---------------------------------------------------------------------------------------
                        
            $i = 0;
            foreach($tcpConnLocalPort as $key => $value)
            {
                $data       = str_replace('INTEGER: ', '', $value);
                $LocalPort[$i] = $data;
                $i = $i + 1;
            }
            $tcpConnLocalPort = null;
            //---------------------------------------------------------------------------------------

                        //---------------------------------------------------------------------------------------
                        
            $i = 0;
            foreach($tcpConnRemAddress as $key => $value)
            {
                $data       = str_replace('IpAddress: ', '', $value);
                $RemAddress[$i] = $data;
                $i = $i + 1;
            }
            $tcpConnRemAddress = null;
            //---------------------------------------------------------------------------------------
                        //---------------------------------------------------------------------------------------
                        
            $i = 0;
            foreach($tcpConnRemPort as $key => $value)
            {
                $data       = str_replace('INTEGER: ', '', $value);
                $RemPort[$i] = $data;
                $i = $i + 1;
            }
            $tcpConnRemPort = null;
            //---------------------------------------------------------------------------------------
                                    
            $this->data_array[0] = $State;
            $this->data_array[1] = $LocalAddress;
            $this->data_array[2] = $LocalPort;
            $this->data_array[3] = $RemAddress;
            $this->data_array[4] = $RemPort;
                                                
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