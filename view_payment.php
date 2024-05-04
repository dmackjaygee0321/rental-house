<?php include 'db_connect.php' ?>

<?php 
$tenants =$conn->query("SELECT t.*, c.fname, c.lname, h.house_no, h.price, (select date_created from payments where t.customer_id = customer_id and approved_date is not null order by date_created DESC limit 1) as last_payment, (SELECT sum(amount) FROM `bills` WHERE STR_TO_DATE(due_date, '%Y-%m-%d') < CURRENT_TIMESTAMP() and is_active = 1 and customer_id = c.id) as outstanding_balance, (select sum(amount) from payments where customer_id = c.id and house_id = t.house_id) as total_paid
from tenants t 
left join customer c on c.id = t.customer_id 
left join houses h on h.id = t.house_id where t.id = {$_GET['id']} ");
foreach($tenants->fetch_array() as $k => $v){
	if(!is_numeric($k)){
		$$k = $v;
	}
}

?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-md-4">
				<div id="details">
					<large><b>Details</b></large>
					<hr>
					<p>Tenant: <b><?php echo ucwords($fname). " " . ucwords($lname) ?></b></p>
					<p>Monthly Rental Rate: <b><?php echo number_format($price,2) ?></b></p>
					<p>Outstanding Balance: <b><?php echo number_format($outstanding_balance,2) ?></b></p>
					<p>Total Paid: <b><?php echo number_format($total_paid,2) ?></b></p>
					<p>Rent Started: <b><?php echo date("M d, Y",strtotime($date_in)) ?></b></p>
					<p>Payable Months: <b><?php echo 5 ?></b></p>
				</div>
			</div>
			<div class="col-md-8">
				<large><b>Payment List</b></large>
					<hr>
				<table class="table table-condensed table-striped">
					<thead>
						<tr>
							<th>Date</th>
							<th>Invoice</th>
							<th>Amount</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$payments = $conn->query("select p.*, c.fname, c.lname, h.house_no from payments p left join customer c on c.id = p.customer_id left join houses h on h.id = p.house_id where p.approved_date is not null and p.customer_id = $customer_id and p.house_id = $house_id");
						if($payments->num_rows > 0):
						while($row=$payments->fetch_assoc()):
						?>
					<tr>
						<td><?php echo date("M d, Y",strtotime($row['date_created'])) ?></td>
						<td><?php echo $row['remarks'] ?></td>
						<td class='text-right'><?php echo number_format($row['amount'],2) ?></td>
					</tr>
					<?php endwhile; ?>
					<?php else: ?>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<style>
	#details p {
		margin: unset;
		padding: unset;
		line-height: 1.3em;
	}
	td, th{
		padding: 3px !important;
	}
</style>