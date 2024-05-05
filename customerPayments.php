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
                        <b>List of Payments</b>
                    </div>
                    <div class="card-body">
                        <table class="table table-condensed table-bordered table-hover">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="">Date</th>
                                <th class="">Property #</th>
                                <th class="">Invoice</th>
                                <th class="">Amount</th>
                                <!--									<th class="text-center">Action</th>-->
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $id = $_SESSION["login_id"];
                            $i = 1;
                            $invoices = $conn->query("select p.*, c.fname, c.lname, h.house_no from payments p left join customer c on c.id = p.customer_id left join houses h on h.id = p.house_id where p.approved_date is not null and p.customer_id = $id");
                            while($row=$invoices->fetch_assoc()):
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++ ?></td>
                                    <td>
                                        <?php echo date('M d, Y',strtotime($row['date_created'])) ?>
                                    </td>
                                    <td class="">
                                        <p> <b><?php echo ucwords($row['house_no']) ?></b></p>
                                    </td>
                                    <td class="">
                                        <p> <b><?php echo ucwords($row['remarks']) ?></b></p>
                                    </td>
                                    <td class="text-right">
                                        <p> <b><?php echo number_format($row['amount'],2) ?></b></p>
                                    </td>
                                    <!--									<td class="text-center">-->
                                    <!--										<button class="btn btn-sm btn-outline-primary edit_invoice" type="button" data-id="--><?php //echo $row['id'] ?><!--" >Edit</button>-->
                                    <!--										<button class="btn btn-sm btn-outline-danger delete_invoice" type="button" data-id="--><?php //echo $row['id'] ?><!--">Delete</button>-->
                                    <!--									</td>-->
                                </tr>
                            <?php endwhile; ?>
                            <?php if($i === 1) { ?>
                                <tr><td colspan="5" class="text-center">No Data</td></tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Table Panel -->
        </div>
    </div>

</div>