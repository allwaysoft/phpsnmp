<!DOCTYPE HTML>
<html>
    <head>
        <Meta http-equiv="Content-Type" Content="text/html; Charset=gb2312">
        <title>information.php</title>
        <style type="text/css">
            body {
                font-family: arial;
                font-size: 13px;
            }
        </style>
        <link href="css/common.css" rel="stylesheet" type="text/css" />
        <link href="css/information.css" rel="stylesheet" type="text/css" />
		
    </head>
    <body id="body_id" class="body_common">
        <?php
            //error_reporting(0);
            include('include/header.php');
            
            print '<div id="container">';
            
            /*+----------------------------------------------+
             *|         Displaying sytem information         |
             *+----------------------------------------------+
             */
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
				
				include('include/System_Information.php');
				$object = new System_Information($_POST['host_address'],$_POST['community']);
				if($object->data_fetched == 'no')
				{
					print '<div id="host_down">ERROR. HOST IS PROBABLY DOWN OR HAS SNMP SERVICE TURNED OFF</div>';
					die();
				}
				$data_array = $object->get_data_array();
				//----------------------------------------------------------------
				print '<div id="operation_system" class="information">';
					$operating_system_name = $object->get_operating_system();
					print $_POST['host_address'].' is running <span id="operating_system_name">'.$operating_system_name.'</span> operating system';
				print '</div>';
				//----------------------------------------------------------------
				$i = 1;
				print '<div id="system_information" class="information">';
					
					print '<div id="block_header_id_1" class="block_header">';
						print '<span class="click">click for </span><a href="#">System Information</a>';
					print '</div>';
					
					print '<div id="block_data_1" class="block_data">';
						foreach($data_array as $key => $value)
						{
							print '<div id="row_id_'.$i.'" class="row">';
								print '<div id="col_id_1" class="col_left">';
									print $key;
								print '</div>';
								print '<div id="col_id_2" class="col_right">';
									print $value;
								print '</div>';
							print '</div>';
							$i = $i + 1;
						}
					print '</div>';
					
				print '</div>';
				
				/*+----------------------------------------------+
				*|         Displaying storage information        |
				*+-----------------------------------------------+
				*/
				if($operating_system_name != 'unknown')
				{
					$object = null;
					include('include/Data_Storage.php');
					$object = new Data_Storage($operating_system_name, $_POST['host_address'],$_POST['community']);
					if($object->data_fetched == 'yes')
					{
						print '<div id="storage_information" class="information">';
						
							print '<div id="block_header_id_2" class="block_header">';
								print '<span class="click">click for </span><a href="#">Storage Information</a>';
							print '</div>';
							
							print '<div id="block_data_2" class="block_data">';
							
								$data_array = null;
								$data_array = $object->get_data_array();
								$row_number = count($data_array[0]);
								for($i=0; $i<$row_number; $i++)
								{
									$allocation_unit = $data_array[2][$i];
									if($allocation_unit == '0')
									{
										$used  = 0;
										$total = 0;
									}
									else
									{
										$divide_unit     = 1024*1024*1024;
										$used            = $data_array[4][$i]*$allocation_unit;
										$total           = $data_array[3][$i]*$allocation_unit;
										$used            = round($used/$divide_unit, 1);
										$total           = round($total/$divide_unit, 1);
									}
									print '<div id="row_number_'.($i+1).'" class="row">';
										print '<span class="drive_description"><b>'.$data_array[1][$i].'</b></span><br>';
										//print ($total - $used).' GB free of '.$total.' GB</br>';
										print '<span class="total_and_free">'.$used.' GB used Total ( '.$total.' GB )</span></br>';
									print '</div>';
									
									print '<div id="row_number_'.($i+1).'_image" class="row row_drive">';
										print '<div class="disk">';
											//$total = round($total, 0);
											//$used  = round($used, 0);
											$length_percentage = round(($used*100)/$total)*4;
											print '<div class="disk_used" style="width: '.$length_percentage.'px;">';
											print '</div>';
										print '</div>';
									print '</div>';
								}
							print '</div>';
							
						print '</div>';
					}
				}
								
				/*+----------------------------------------------+
				*|         Displaying running applications       |
				*+-----------------------------------------------+
				*/
				
				//----------------------------------------------------------------
				
				if($operating_system_name != 'unknown')
				{
					$object = null;
					include('include/Running_Applications.php');
					$object = new Running_Applications($operating_system_name, $_POST['host_address'],$_POST['community']);
					
					if($object->data_fetched == 'yes')
					{
						print '<div id="running_applications" class="information">';
				
							print '<div id="block_header_id_3" class="block_header">';
								print '<span class="click">click for </span><a href="#">Running applications</a>';
							print '</div>';
					
							print '<table cellspacing="0" cellpadding="0" border="1">';
								$data_array = null;
								$data_array = $object->get_data_array();
								print '<tr>';
									print '<td>';
										print '<b>Name</b>';
									print '</td>';
									print '<td>';
										print '<b>Memory</b>';
									print '</td>';
									print '<td>';
										print '<b>Type</b>';
									print '</td>';
									print '<td>';
										print '<b>Status</b>';
									print '</td>';
                                    print '<td>';
										print '<b>ID</b>';
									print '</td>';
                                    print '<td>';
										print '<b>Path</b>';
									print '</td>';
                                    print '<td>';
										print '<b>Parameter</b>';
									print '</td>';
                                    print '<td>';
										print '<b>CPU</b>';
									print '</td>';
								print '</tr>';
								
								$row_number = count($data_array[0]);
								for($i=0; $i<$row_number; $i++)
								{
									print '<tr>';
										print '<td>';
											print $data_array[0][$i];
										print '</td>';
										print '<td>';
											print $data_array[1][$i];
										print '</td>';
										print '<td>';
											print $data_array[2][$i];
										print '</td>';
										print '<td>';
											print $data_array[3][$i];
										print '</td>';
                                        print '<td>';
											print $data_array[4][$i];
										print '</td>';
                                        print '<td>';
											print $data_array[5][$i];
										print '</td>';
                                        print '<td>';
											print $data_array[6][$i];
										print '</td>';
                                        print '<td>';
											print $data_array[7][$i];
										print '</td>';
									print '</tr>';
								}
								
							print '</table>';
				
						print '</div>';
					}
					
				}
				
				//----------------------------------------------------------------
            
            				/*+----------------------------------------------+
				*|         Displaying TCP_Connections       |
				*+-----------------------------------------------+
				*/
				
				//----------------------------------------------------------------
				
				if($operating_system_name != 'unknown')
				{
					$object = null;
					include('include/TCP_Connections.php');
					$object = new TCP_Connections($operating_system_name, $_POST['host_address'],$_POST['community']);
					
					if($object->data_fetched == 'yes')
					{
							print 'TCP Connections';
					
							print '<table cellspacing="0" cellpadding="0" border="1">';
							
								$data_array = null;
								$data_array = $object->get_data_array();
								print '<tr>';
									print '<td>';
										print '<b>LocalAddress</b>';
									print '</td>';
									print '<td>';
										print '<b>LocalPort</b>';
									print '</td>';
									print '<td>';
										print '<b>RemAddress</b>';
									print '</td>';
                                    print '<td>';
										print '<b>RemPort</b>';
									print '</td>';
									print '<td>';
										print '<b>State</b>';
									print '</td>';
								print '</tr>';
								
								$row_number = count($data_array[0]);
								for($i=0; $i<$row_number; $i++)
								{
									print '<tr>';
										print '<td>';
											print $data_array[1][$i];
										print '</td>';
										print '<td>';
											print $data_array[2][$i];
										print '</td>';
										print '<td>';
											print $data_array[3][$i];
										print '</td>';
                                        print '<td>';
											print $data_array[4][$i];
										print '</td>';
										print '<td>';
											print $data_array[0][$i];
										print '</td>';
									print '</tr>';
								}
								
							print '</table>';
				

					}
					
				}
				
				//----------------------------------------------------------------


				//----------------------------------------------------------------
            
            				/*+----------------------------------------------+
				*|         Displaying UDP_Connections       |
				*+-----------------------------------------------+
				*/
				
				//----------------------------------------------------------------
				
				if($operating_system_name != 'unknown')
				{
					$object = null;
					include('include/UDP_Connections.php');
					$object = new UDP_Connections($operating_system_name, $_POST['host_address'],$_POST['community']);
					
					if($object->data_fetched == 'yes')
					{
						
							print 'UDP Connections';
						
					
							print '<table cellspacing="0" cellpadding="0" border="1">';
							
								$data_array = null;
								$data_array = $object->get_data_array();
								print '<tr>';
									print '<td>';
										print '<b>LocalAddress</b>';
									print '</td>';
									print '<td>';
										print '<b>LocalPort</b>';
									print '</td>';
								print '</tr>';
								
								$row_number = count($data_array[0]);
								for($i=0; $i<$row_number; $i++)
								{
									print '<tr>';
										print '<td>';
											print $data_array[0][$i];
										print '</td>';
										print '<td>';
											print $data_array[1][$i];
										print '</td>';
									print '</tr>';
								}
								
							print '</table>';
					}
					
				}
				
				//----------------------------------------------------------------


                
                /*+----------------------------------------------+
				*|       Displaying installed information        |
				*+-----------------------------------------------+
				*/
				if($operating_system_name != 'unknown')
				{
					$object = null;
					include('include/Installed_Information.php');
					$object = new Installed_Information($operating_system_name, $_POST['host_address'],$_POST['community']);
					if($object->data_fetched == 'yes')
					{
						print '<div id="installed_information" class="information">';
						
							print '<div id="block_header_id_4" class="block_header">';
								print '<span class="click">click for </span><a href="#">Installed Applications</a>';
							print '</div>';
							
							print '<table cellspacing="0" cellpadding="0" border="1">';
							
								$data_array = null;
								$data_array = $object->get_data_array();
								print '<tr>';
									print '<td>';
										print '<b>Name</b>';
									print '</td>';
									print '<td>';
										print '<b>Installation Datetime</b>';
									print '</td>';
                                    print '<td>';
										print '<b>Type</b>';
									print '</td>';
								print '</tr>';
								for($i=0; $i<count($data_array[0]); $i++)
                                {
                                    $installation_date = explode(',', $data_array[2][$i])[0];
                                    $installation_time = explode('.', explode(',', $data_array[2][$i])[1])[0];
                                    print '<tr>';
                                        print '<td>';
                                            print $data_array[1][$i];
                                        print '</td>';
                                        print '<td>';
                                            print $installation_date.'&nbsp';
                                            print $installation_time;
                                        print '</td>';
                                        print '<td>';
                                            print $data_array[0][$i];
                                        print '</td>';
                                    print '</tr>';
                                }
                                
							print '</table>';
							
						print '</div>';
					}
				}
				
            }
            else
            {
                /*
                 * If someone tries to add GET variables with the URL redirect to index.php
                 */
                header('Location: index.php');
                
            }

            print '</div>';
            
            include('include/footer.php');
        ?>

    </body>
</html>