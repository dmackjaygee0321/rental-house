<?php include 'db_connect.php' ?>
<style>
	.on-print{
		display: none;
	}
</style>
<noscript>
	<style>
		.text-center{
			text-align:center;
		}
		.text-right{
			text-align:right;
		}
		table{
			width: 100%;
			border-collapse: collapse
		}
		tr,td,th{
			border:1px solid black;
		}
	</style>
</noscript>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-body">
				<div class="col-md-12">
					<hr>
						<div class="row">
							<div class="col-md-12 mb-2">
							<button class="btn btn-sm btn-block btn-success col-md-2 ml-1 float-right" type="button" id="print"><i class="fa fa-print"></i> Print</button>
							</div>
						</div>
					<div id="report">
						<div class="on-print">
							 <p><center>Rental Balances Report</center></p>
							 <p><center>As of <b><?php echo date('F ,Y') ?></b></center></p>
						</div>
						<div class="row">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>#</th>
										<th>Property#</th>
										<th>Due Date</th>
										<th>Payable Amount</th>
										<th>Paid</th>
										<th>Outstanding Balance</th>
                                        <th>Status</th>
                                        <th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
                                    $id = $_SESSION["login_id"];
									$i = 1;
									// $tamount = 0;
									$tenants =$conn->query("SELECT b.*, c.fname, c.lname,h.house_no FROM bills b LEFT join tenants t on t.id = b.tenant_id LEFT join customer c on c.id = t.customer_id LEFT join houses h on h.id = b.house_id where t.customer_id = $id and b.is_active = 1 order by STR_TO_DATE(b.due_date, '%Y-%m-%d') asc;");
									if($tenants->num_rows > 0):
									while($row=$tenants->fetch_assoc()):
                                        $balance = $row["amount"] - $row["amount_paid"];
                                        $status = "Pending";
                                        $currentDate = new DateTime();

                                        $dateToCheck = DateTime::createFromFormat('Y-m-d', $row["due_date"]);


                                        if($dateToCheck < $currentDate)
                                            $status = "Past Due";
                                        if($balance == 0)
                                            $status = "Paid";
									?>
									<tr>
										<td><?php echo $i++ ?></td>
										<td><?php echo $row['house_no'] ?></td>
										<td class="text-right"><?php echo $row["due_date"] ?></td>
                                        <td class="text-right"><?php echo number_format($row["amount"],2) ?></td>
										<td class="text-right"><?php echo number_format($row["amount_paid"],2) ?></td>
										<td class="text-right"><?php echo number_format($balance,2) ?></td>
                                        <td class="text-right"><?php echo $status ?></td>
                                        <td class="text-right">
                                            <?php if($status != "Paid") { ?>
                                            <button class="btn btn-primary payment" data-id="<?= $row["id"] ?>">Payment</button>
                                            <?php } ?>
                                        </td>
									</tr>
								<?php endwhile; ?>
								<?php else: ?>
									<tr>
										<th colspan="9"><center>No Data.</center></th>
									</tr>
								<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#print').click(function(){
		var _style = $('noscript').clone()
		var _content = $('#report').clone()
		var nw = window.open("","_blank","width=800,height=700");
		nw.document.write(_style.html())
		nw.document.write(_content.html())
		nw.document.close()
		nw.print()
		setTimeout(function(){
		nw.close()
		},500)
	})
    $(".payment").on("click", function () {
        const id =  $(this).attr("data-id")
        uni_modal("Payment","customer_payment.php?id="+id+"&mtype=own", "", true)
    })
</script>