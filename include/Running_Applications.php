<?php
error_reporting(0);
    class Running_Applications
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
            $perf = array();
            $result_perf = snmpwalk($this->host_address, $this->community, '.iso.org.dod.internet.mgmt.mib-2.host.hrSWRunPerf.hrSWRunPerfTable.hrSWRunPerfEntry');
            $perf = array_chunk($result_perf,count($result_perf)/2);
            $proc = array();
            $result_proc = snmpwalk($this->host_address, $this->community, '.iso.org.dod.internet.mgmt.mib-2.host.hrSWRun.hrSWRunTable.hrSWRunEntry');
            $proc = array_chunk($result_proc,count($result_proc)/7);

            $application_name           = $proc[1];//snmpwalkoid($this->host_address, $this->community, ".iso.org.dod.internet.mgmt.mib-2.host.hrSWRun.hrSWRunTable.hrSWRunEntry.hrSWRunName");
            $application_memory         = $perf[1];//snmpwalkoid($this->host_address, $this->community, ".iso.org.dod.internet.mgmt.mib-2.host.hrSWRunPerf.hrSWRunPerfTable.hrSWRunPerfEntry.hrSWRunPerfMem");
            $application_type           = $proc[5];//snmpwalkoid($this->host_address, $this->community, ".iso.org.dod.internet.mgmt.mib-2.host.hrSWRun.hrSWRunTable.hrSWRunEntry.hrSWRunType");
            $application_run_status     = $proc[6];//snmpwalkoid($this->host_address, $this->community, ".iso.org.dod.internet.mgmt.mib-2.host.hrSWRun.hrSWRunTable.hrSWRunEntry.hrSWRunStatus");
            $application_id             = $proc[0];//snmpwalkoid($this->host_address, $this->community, ".iso.org.dod.internet.mgmt.mib-2.host.hrSWRun.hrSWRunTable.hrSWRunEntry.hrSWRunIndex");
            $application_path           = $proc[3];//snmpwalkoid($this->host_address, $this->community, ".iso.org.dod.internet.mgmt.mib-2.host.hrSWRun.hrSWRunTable.hrSWRunEntry.hrSWRunPath");
            $application_parameter      = $proc[4];//snmpwalkoid($this->host_address, $this->community, ".iso.org.dod.internet.mgmt.mib-2.host.hrSWRun.hrSWRunTable.hrSWRunEntry.hrSWRunParameters");
            $application_cpu            = $perf[0];//snmpwalkoid($this->host_address, $this->community, ".iso.org.dod.internet.mgmt.mib-2.host.hrSWRunPerf.hrSWRunPerfTable.hrSWRunPerfEntry.hrSWRunPerfCPU");
            if(
                $application_id       != FALSE and
                $application_path       != FALSE and
                $application_parameter       != FALSE and
                $application_cpu       != FALSE and
                $application_name       != FALSE and
                $application_memory     != FALSE and
                $application_type       != FALSE and
                $application_run_status != FALSE
            )
            {
                
                $length   = count($application_name);
                $count    = array();
                $count[0] = count($application_name);
                $count[1] = count($application_memory);
                $count[2] = count($application_type);
                $count[3] = count($application_run_status);
                $count[4] = count($application_id);
                $count[5] = count($application_path);
                $count[6] = count($application_parameter);
                $count[7] = count($application_cpu);
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
                $this->populate_data_array($application_name, $application_memory, $application_type, $application_run_status,$application_id,$application_path,$application_parameter,$application_cpu);
            }
        }
        private function populate_data_array($application_name, $application_memory, $application_type, $application_run_status,$application_id,$application_path,$application_parameter,$application_cpu)
        {
            $name       = array();
            $memory     = array();
            $type       = array();
            $run_status = array();
            $id         = array();
            $path         = array();
            $parameter         = array();
            $cpu         = array();
                        
            //---------------------------------------------------------------------------------------
            $i = 0;
            foreach($application_name as $key => $value)
            { 
                $data = $value;
                if(strlen($data) == 0)
                {
                    $data = '<span id="no_data_received"><b>No data received</b></span>';
                }
                else
                {
                    if($data == 'STRING: ')
                    {
                        $data = '<span id="no_data_received"><b>No data received</b></span>';
                    }
                    else
                    {
                        if(substr_count($data, 'STRING: ') > 0)
                        {
                            $data = substr($data, strlen('STRING: '), strlen($data));
                        }
                        else
                        {
                            $data = $data;
                        }
                    }
                }                    
                $data = str_replace('"', '', $data);
                $name[$i] = $data;
                $i = $i + 1;
            }
            $application_name = null;
            //---------------------------------------------------------------------------------------
                        
            $i = 0;
            foreach($application_memory as $key => $value)
            {
                $data       = str_replace('INTEGER: ', '', $value);
                $memory[$i] = $data;
                $i = $i + 1;
            }
            $application_memory = null;
            //---------------------------------------------------------------------------------------
                        
            $i = 0;
            foreach($application_type as $key => $value)
            {
                $data       = $value;
                if(substr_count($value, 'unknown') > 0)
                {
                    $data = 'unknown';
                }
                if(substr_count($value, 'operatingSystem') > 0)
                {
                    $data = 'operating system';
                }
                if(substr_count($value, 'deviceDriver') > 0)
                {
                    $data = 'device driver';
                }
                if(substr_count($value, 'application') > 0)
                {
                    $data = 'application';
                }
                $type[$i] = $data;
                $i = $i + 1;
            }
            $application_type = null;
            //---------------------------------------------------------------------------------------
                        
            $i = 0;
            foreach($application_run_status as $key => $value)
            {
                $data       = $value;
                if(substr_count($value, 'running') > 0)
                {
                    $data = 'running';
                }
                if(substr_count($value, 'runnable') > 0)
                {
                    $data = 'runnable';
                }
                if(substr_count($value, 'notRunnable') > 0)
                {
                    $data = 'not runnable';
                }
                if(substr_count($value, 'invalid') > 0)
                {
                    $data = 'invalid';
                }
                $run_status[$i] = $data;
                $i = $i + 1;
            }
            $application_run_status = null;
            //---------------------------------------------------------------------------------------
            //---------------------------------------------------------------------------------------
                        
            $i = 0;
            foreach($application_id as $key => $value)
            {
                $data       = str_replace('INTEGER: ', '', $value);
                $id[$i] = $data;
                $i = $i + 1;
            }
            $application_id = null;
            //---------------------------------------------------------------------------------------
            //---------------------------------------------------------------------------------------
                        
            $i = 0;
            foreach($application_path as $key => $value)
             { 
                $data = $value;
                if(strlen($data) == 0)
                {
                    $data = '<span id="no_data_received"><b>No data received</b></span>';
                }
                else
                {
                    if($data == 'STRING: ')
                    {
                        $data = '<span id="no_data_received"><b>No data received</b></span>';
                    }
                    else
                    {
                        if(substr_count($data, 'STRING: ') > 0)
                        {
                            $data = substr($data, strlen('STRING: '), strlen($data));
                        }
                        else
                        {
                            $data = $data;
                        }
                    }
                }                    
                $data = str_replace('"', '', $data);
                $path[$i] = $data;
                $i = $i + 1;
            }
            $application_path = null;
            //---------------------------------------------------------------------------------------
                        //---------------------------------------------------------------------------------------
                        
            $i = 0;
            foreach($application_parameter as $key => $value)
             { 
                $data = $value;
                if(strlen($data) == 0)
                {
                    $data = '<span id="no_data_received"><b>No data received</b></span>';
                }
                else
                {
                    if($data == 'STRING: ')
                    {
                        $data = '<span id="no_data_received"><b>No data received</b></span>';
                    }
                    else
                    {
                        if(substr_count($data, 'STRING: ') > 0)
                        {
                            $data = substr($data, strlen('STRING: '), strlen($data));
                        }
                        else
                        {
                            $data = $data;
                        }
                    }
                }                    
                $data = str_replace('"', '', $data);
                $parameter[$i] = $data;
                $i = $i + 1;
            }
            $application_parameter = null;
            //---------------------------------------------------------------------------------------
                        //---------------------------------------------------------------------------------------
                        
            $i = 0;
            foreach($application_cpu as $key => $value)
            {
                $data       = str_replace('INTEGER: ', '', $value);
                $cpu[$i] = $data;
                $i = $i + 1;
            }
            $application_cpu = null;
            //---------------------------------------------------------------------------------------
                                    
            $this->data_array[0] = $name;
            $this->data_array[1] = $memory;
            $this->data_array[2] = $type;
            $this->data_array[3] = $run_status;
            $this->data_array[4] = $id;
            $this->data_array[5] = $path;
            $this->data_array[6] = $parameter;
            $this->data_array[7] = $cpu;
                                                
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