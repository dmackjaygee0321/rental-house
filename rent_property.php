<?php
include('db_connect.php');
session_start();
$user = $conn->query("SELECT * FROM houses where id =" . $_GET['id']);
foreach ($user->fetch_array() as $k => $v) {
            $meta[$k] = $v;
        }
?>
<div>
	<div id="msg"></div>
        <div class="col-lg-12">
                <img class="card-img-top" src="https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">Property #: <?= $meta["house_no"] ?></h5>
                    <p class="card-text"><?= $meta["description"] ?></p>
                    <p>Monthly Rent: <span style="font-weight: bolder"><?= number_format($meta['price'],2) ?></span></p>
                    <h5 class="card-title" style="border-bottom: 1px solid #dee2e6">Payment Summary</h5>
                    <table style="width: 100%; margin-bottom: 20px">
                        <tr>
                            <td style="width: 50%">1 Month Deposit:</td>
                            <td style="font-weight: bolder; width: 50%; text-align: right"><?= number_format($meta['price'],2) ?></td>
                        </tr>
                        <tr>
                            <td>1 Month Advance:</td>
                            <td style="font-weight: bolder; text-align: right"><?= number_format($meta['price'],2) ?></td>
                        </tr>
                        <tr>
                            <td>Total Payment:</td>
                            <td style="font-weight: bolder; text-align: right; border-top: 1px solid black"><?= number_format(($meta['price'] * 2),2) ?></td>
                        </tr>
                    </table>
                    <div id="qrcode"></div>
                </div>
            </div>

</div>
<script>
    // Create the QR code
    $(document).ready(function(){
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "http://localhost/house_rental/qr.php",
            width: 256,
            height: 256,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H // Error correction level
        });
    });
</script>