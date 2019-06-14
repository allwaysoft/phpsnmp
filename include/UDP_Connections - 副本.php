<?php
error_reporting(0);
    class UDP_Connections
    {
        public function __construct($operating_system, $host_address)
        {
            $this->operating_system = $operating_system;
            $this->host_address     = $host_address;
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

            $udpLocalAddress       = snmpwalkoid($this->host_address, "public", ".iso.org.dod.internet.mgmt.mib-2.udp.udpTable.udpEntry.udpLocalAddress");
            $udpLocalPort     = snmpwalkoid($this->host_address, "public", ".iso.org.dod.internet.mgmt.mib-2.udp.udpTable.udpEntry.udpLocalPort");
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
        private $data_array = array();
    }
?>