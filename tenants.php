<?php include('db_connect.php');?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">
				
			</div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>List of Tenant</b>
						<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_tenant">
					<i class="fa fa-plus"></i> New Tenant
				</a></span>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Name</th>
									<th class="">Property Rented</th>
									<th class="">Monthly Rate</th>
									<th class="">Outstanding Balance</th>
									<th class="">Last Payment</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$tenant = $conn->query("SELECT t.*, c.fname, c.lname, h.house_no, h.price, (select date_created from payments where t.customer_id = customer_id and approved_date is not null order by date_created DESC limit 1) as last_payment, (SELECT sum(amount) FROM `bills` WHERE STR_TO_DATE(due_date, '%Y-%m-%d') < CURRENT_TIMESTAMP() and is_active = 1 and customer_id = c.id) as outstanding_balance from tenants t left join customer c on c.id = t.customer_id left join houses h on h.id = t.house_id;");
								while($row=$tenant->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td>
										<?php echo ucwords($row['fname']) . " " . ucwords($row['lname']) ?>
									</td>
									<td class="">
										 <p> <b><?php echo $row['house_no'] ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo number_format($row['price'],2) ?></b></p>
									</td>
									<td class="text-right">
										 <p> <b><?php echo number_format($row['outstanding_balance'],2) ?></b></p>
									</td>
									<td class="">
										 <p><b><?php echo  $row['last_payment'] ?></b></p>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-outline-primary view_payment" type="button" data-id="<?php echo $row['id'] ?>" >View</button>
										<button class="btn btn-sm btn-outline-primary edit_tenant" type="button" data-id="<?php echo $row['id'] ?>" >Edit</button>
										<button class="btn btn-sm btn-outline-danger delete_tenant" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}
	img{
		max-width:100px;
		max-height: :150px;
	}
</style>
<script>
	$(document).ready(function(){
		$('table').dataTable()
	})
	
	$('#new_tenant').click(function(){
		uni_modal("New Tenant","manage_tenant.php","mid-large")
		
	})

	$('.view_payment').click(function(){
		uni_modal("Tenants Payments","view_payment.php?id="+$(this).attr('data-id'),"large")
		
	})
	$('.edit_tenant').click(function(){
		uni_modal("Manage Tenant Details","manage_tenant.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.delete_tenant').click(function(){
		_conf("Are you sure to delete this Tenant?","delete_tenant",[$(this).attr('data-id')])
	})
	
	function delete_tenant($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_tenant',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>