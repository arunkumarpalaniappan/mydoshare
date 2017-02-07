<?php
include('_Config.php');
                                    $grp = $_POST['grpname'];
									$response=array();
									$i =0;
													 $myuser=$_POST['user'];
                                                     $grpname = $grp.'_expense';
                                                     $get = "SELECT category,SUM($myuser) AS y FROM `$grpname` WHERE MONTH(`c_time`) = MONTH(CURDATE()) GROUP BY category";
                                                     $rst = $conn->query($get);
                                                     if($rst->num_rows > 0)
                                                        {															
                                                               while($row1 = $rst->fetch_assoc()) {
															    $response[$i]=$row1;	
																$i++;
                                                             }
                                                         }
														 echo json_encode($response);
	
?>