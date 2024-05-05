<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PAYMENT SUMMARY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
    include('./header.php'); ?>
      <style>
      .container {
    width: 80%;            
    margin: 20px auto;     
    padding: 20px;          
    background-color: #D0E6F4; /
    border: 2px solid #ccc; 
    box-shadow: 0 4px 8px rgba(0,0,0,0.1); 
}
    .label{
        color:white;
    }
    </style>
  </head>
  <body>
  <?php
  include('db_connect.php');
  session_start();
  if(isset($_GET["id"])) {
      $user = $conn->query("SELECT b.*, h.house_no, c.fname, c.lname, t.customer_id FROM bills b left join tenants t on t.id = b.tenant_id left join customer c on c.id = t.customer_id left join houses h on h.id = b.house_id where b.id =" . $_GET['id']);
      foreach ($user->fetch_array() as $k => $v) {
          $meta[$k] = $v;
      }

      $payment = $meta["amount"] - $meta["amount_paid"];
  }
  ?>
  <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-body text-white">
      </div>
  </div>


  <div class="container mt-5" >
      <div id="msg"></div>
    <form id="manage-user" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $meta["id"] ?>"/>
                        <input type="hidden" name="customer_id" value="<?= $meta["customer_id"] ?>"/>
                        <input type="hidden" name="house_id" value="<?= $meta["house_id"] ?>"/>
                        <div class="row g-0 text-center">
                            <div class="col-sm-12 col-md-">
                        
                        <div class="card">
                        <div class="card-body">
                            <h4>Customer Payment</h4>
                        </div>
                        </div>
                        <div class="mb-3">
                            <label for="disabledTextInput" class="form-label">Full Name:</label>
                            <input type="text" id="disabledTextInput" class="form-control" value="<?= $meta["fname"] ." ".$meta["lname"] ?>" readonly>
                        </div>
                                <div class="mb-3">
                                    <label for="disabledTextInput" class="form-label">Due Date:</label>
                                    <input type="text" name="payable_amount" class="form-control"  value="<?= $meta["due_date"] ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="disabledTextInput" class="form-label">Property #:</label>
                                    <input type="text" name="payable_amount" class="form-control"  value="<?= $meta["house_no"] ?>" readonly>
                                </div>
                        <div class="mb-3">
                            <label for="disabledTextInput" class="form-label">Payable Amount:</label>
                            <input type="text" name="payable_amount" class="form-control"  value="<?= $payment ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="disabledTextInput" class="form-label">Amount:</label>
                            <input type="number" name="payment" class="form-control" placeholder="Enter Amount" value="<?= $payment ?>">
                        </div>
                        <div class="mb-3">
                            <label for="disabledTextInput" class="form-label" >Upload Receipt:</label>
                            <input type="file" name="file" class="form-control">
                        </div>
                        <div class="mb-3">
                        <div class="form-check">
                        </div>
                        </div>
                        <button type="submit" class="btn btn-danger">Submit</button>


                    </div>

                    <div class="col-6 col-md-4">

            </div>
        </div>
    </form>
</div>

  <script>

      window.start_load = function(){
          $('body').prepend('<di id="preloader2"></di>')
      }
      window.end_load = function(){
          $('#preloader2').fadeOut('fast', function() {
              $(this).remove();
          })
      }


      window.alert_toast= function($msg = 'TEST',$bg = 'success'){
          $('#alert_toast').removeClass('bg-success')
          $('#alert_toast').removeClass('bg-danger')
          $('#alert_toast').removeClass('bg-info')
          $('#alert_toast').removeClass('bg-warning')

          if($bg == 'success')
              $('#alert_toast').addClass('bg-success')
          if($bg == 'danger')
              $('#alert_toast').addClass('bg-danger')
          if($bg == 'info')
              $('#alert_toast').addClass('bg-info')
          if($bg == 'warning')
              $('#alert_toast').addClass('bg-warning')
          $('#alert_toast .toast-body').html($msg)
          $('#alert_toast').toast({delay:3000}).toast('show');
      }

      $('#manage-user').submit(function(e){
          e.preventDefault();
          start_load()
          const formData = new FormData($("#manage-user")[0]);
          $.ajax({
              url:'ajax.php?action=bill_payment',
              method:'POST',
              processData: false,
              contentType: false,
              data: formData,
              success:function(resp){
                  if(resp ==1){
                      alert_toast("Payment successfully submitted",'success')
                      setTimeout(function(){
                          location.href = "http://localhost/house_rental"
                      },1500)
                  }else{
                      $('#msg').html('<div class="alert alert-danger">Payment is already Process!</div>')
                      end_load()
                  }
              }
          })
      })

  </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>