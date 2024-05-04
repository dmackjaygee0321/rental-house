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
										<th>Firstname</th>
										<th>Lastname</th>
										<th>House No</th>
                                        <th>Payment</th>
                                        <th>Payment Date</th>
                                        <th>Payment Type</th>
                                        <th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
                                    $ctr = 0;
                                    $query = "select p.*, c.fname, c.lname, h.house_no from payments p left join customer c on c.id = p.customer_id left join houses h on h.id = p.house_id where p.approved_date is null and p.decline_date is null;";
                                    $tenants =$conn->query($query);
                                    while($row=$tenants->fetch_assoc()):
                                        $ctr++;
                                    ?>
                                        <tr>
                                            <td><?= $ctr ?></td>
                                            <td><?= $row["fname"] ?></td>
                                            <td><?= $row["lname"] ?></td>
                                            <td><?= $row["house_no"] ?></td>
                                            <td><?= number_format($row["amount"], 2) ?></td>
                                            <td><?= $row["date_created"] ?></td>
                                            <td><?= $row["payment_type"] ?></td>
                                            <td>
                                                <button class="btn btn-secondary view" data-src="<?= $row["file"] ?>">View Receipt</button>
                                                <button class="btn btn-primary approve" data-id="<?= $row["id"] ?>" data-type="<?= $row["payment_type"] ?>">Approve</button>
                                                <button class="btn btn-danger decline" data-id="<?= $row["id"] ?>">Decline</button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php if($ctr === 0){ ?>
                                    <tr><td colspan="8" class="text-center">No Data</td></tr>
                                <?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <div class="modal" tabindex="-1" role="dialog" id="receiptModal">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Receipt</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="viewImage" width="100%" height="100%" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	$(".approve").on("click", function () {
        const id = $(this).attr("data-id")
        const type = $(this).attr("data-type")

        const remarks = prompt("Enter Invoice");
        if(remarks) {
            if (confirm("Are you sure want to approve this payment?")) {
                start_load()
                $.ajax({
                    url: 'ajax.php?action=approve_payment',
                    method: 'POST',
                    data: {id, type, remarks},
                    success: function (resp) {
                        if (resp == 1) {
                            alert_toast("Data successfully deleted", 'success')
                            setTimeout(function () {
                                location.reload()
                            }, 1500)

                        }
                    }
                })
            }
        }
    })


    $(".decline").on("click", function () {
        const id = $(this).attr("data-id")
        const remarks = prompt("Comment");
        if(remarks) {
            if (confirm("Are you sure want to decline this payment?")) {
                start_load()
                $.ajax({
                    url: 'ajax.php?action=decline_payment',
                    method: 'POST',
                    data: {id, remarks},
                    success: function (resp) {
                        if (resp == 1) {
                            alert_toast("Data successfully deleted", 'success')
                            setTimeout(function () {
                                location.reload()
                            }, 1500)

                        }
                    }
                })

            }
        }
    })
    $(".view").on("click", function () {
        const src = $(this).attr("data-src")
        $("#viewImage").attr("src", "./uploads/"+src)
        $("#receiptModal").modal("show")
    })
</script>