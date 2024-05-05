<?php
include('db_connect.php');
session_start();
$user = $conn->query("SELECT b.*, h.house_no FROM bills b left join houses h on h.id = b.house_id where b.id =" . $_GET['id']);
foreach ($user->fetch_array() as $k => $v) {
            $meta[$k] = $v;
        }

$balance = $meta["amount"] - $meta["amount_paid"];
$status = "Pending";
$currentDate = new DateTime();

$dateToCheck = DateTime::createFromFormat('Y-m-d', $meta["due_date"]);


if($dateToCheck < $currentDate)
    $status = "Past Due";
if($balance == 0)
    $status = "Paid";
?>
<div>
	<div id="msg"></div>
    <div class="col-lg-12">
        <table style="width: 100%; margin-bottom: 20px">
            <tr>
                <td style="width: 50%">Property #:</td>
                <td style="font-weight: bolder; width: 50%; text-align: right"><?= $meta["house_no"] ?></td>
            </tr>
            <tr>
                <td style="width: 50%">Due Date:</td>
                <td style="font-weight: bolder; width: 50%; text-align: right"><?= $meta["due_date"] ?></td>
            </tr>
            <tr>
                <td style="width: 50%">Status:</td>
                <td style="font-weight: bolder; width: 50%; text-align: right"><?= $status ?></td>
            </tr>
        </table>
        <h5 class="card-title" style="border-bottom: 1px solid #dee2e6">Payment Summary</h5>
        <table style="width: 100%; margin-bottom: 20px">
            <tr>
                <td style="width: 50%">Payable amount:</td>
                <td style="font-weight: bolder; width: 50%; text-align: right"><?= number_format($meta['amount'],2) ?></td>
            </tr>
            <tr>
                <td style="width: 50%">Paid amount:</td>
                <td style="font-weight: bolder; width: 50%; text-align: right"><?= number_format($meta['amount_paid'],2) ?></td>
            </tr>
            <tr>
                <td style="width: 50%">Outstanding Balance:</td>
                <td style="font-weight: bolder; width: 50%; text-align: right;border-top: 1px solid black"><?= number_format($meta['amount'] - $meta['amount_paid'],2) ?></td>
            </tr>
        </table>
        <div id="qrcode"></div>
    </div>
</div>
<script>
    $(document).ready(function(){
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "http://localhost/house_rental/monthlyqrpage.php?id=<?= $_GET['id'] ?>",
            width: 256,
            height: 256,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H // Error correction level
        });
    });
</script>