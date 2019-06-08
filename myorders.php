<?php
	require('header.php');
	if(!isset($_SESSION['username'])){
		header('location:logout.php');
	}
	$tblcolor = array("success", "danger", "active", "info", "warning");
	$tbi=0;
	$selectqry = "SELECT o.orderid, o.userid, o.receipt, o.tt_complete, o.order_time, o.price, u.username from orders o INNER JOIN user_account u on o.userid = u.email and u.email='".$_SESSION['email']."'";
	$execute = mysqli_query($link, $selectqry);
	$num= mysqli_num_rows($execute);
	
	$exe1 = mysqli_query($link, $selectqry);
	$res1 = mysqli_fetch_array($exe1);
	$abc = strtotime($res1['order_time']);
?>
<script>
function calcTimer(var1, var2){
	var tt_make = document.getElementById(var1).textContent;
	if(tt_make!=0){
	var nowDateObj = new Date();
	
	var newTime = nowDateObj.getTime() + tt_make*1000;
	var x = setInterval(function() {

  // Get todays date and time
	var now = new Date();
	var distance = newTime - now.getTime();
	var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	var seconds = Math.floor((distance % (1000 * 60)) / 1000);

	if(hours<10)hours="0"+hours;
	if(minutes<10)minutes="0"+minutes;
	if(seconds<10)seconds="0"+seconds;
  document.getElementById(var2).innerHTML = hours + " : " + minutes + " : " + seconds;

  if (distance < 0) {
    clearInterval(x);
    document.getElementById(var2).innerHTML = "ORDER READY";
  }
}, 1000);
	}
	else{
		document.getElementById(var2).innerHTML = "ORDER READY";
	}
}
</script>


<div class="container">
	
	<div class="row">
		<div class="table-responsive">
			<table class="table table-bordered table-hover">
				<h3><center><b>LIST OF ORDERED ITEMS</b></center></h3>
				<tr class="<?php echo $tblcolor[$tbi]; $tbi=($tbi+1)%5; ?>">
					<th>Order ID</th>
					<th>Price</th>
					<th>Time</th>
					<th>Time Left</th>
				</tr>
				<?php
					
					$cum_sum = 0;
					$order = 0; $tt_make = 0;
					if($num>0){
						while($result=mysqli_fetch_array($execute)){
				?>
				<?php
							$orderi = strtotime($result['order_time']);
							if($cum_sum + $abc <= $orderi){
								$cum_sum = $result['tt_complete']*60;
								$order = $orderi;
								//change
								$abc = $orderi;
								//change
							}
							else{
								$cum_sum +=  $result['tt_complete']*60;
							}
							$currtime = strtotime(date('Y-m-d h:i:sa'));
							if($currtime - $order <= $cum_sum){
								$tt_make = $cum_sum - $currtime + $order;
							}
							if($result['userid']==$_SESSION['email']){
				?>
				<tr class="<?php echo $tblcolor[$tbi]; $tbi=($tbi+1)%5; ?>">
					<td><a href = "yourorder.php?id=<?php echo $result['orderid']; ?>"><?php echo $result['orderid']; ?></a></td>
					<td><?php echo $result['price']; ?></td>
					<td><?php echo($result['tt_complete']); ?></td>
					<td>
						<p id=<?php echo "'time".$result['orderid']."'"; ?>>
							<?php if($result['receipt']=='1'){
									echo "ORDER COMPLETED</p></td>";
								}
									else{?>
						</p>
					</td>
					<td style="display:none;"><p id=<?php echo "'tt".$result['orderid']."'";?>><?php if($result['tt_complete']!=0)echo $tt_make; else echo "0"; ?></p></td>
										<script>
						calcTimer('<?php echo('tt'.$result['orderid']); ?>', '<?php echo('time'.$result['orderid']); ?>');
									</script>
							<?php
									}
							?>
				</tr>
				<?php
							}
						}
					}
				?>
			</table>
		</div>
	</div>
</div>
