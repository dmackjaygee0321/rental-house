<?php include('db_connect.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
<div class="container-fluid">
   <div class="container px-4 text-center">
        <div class="row gx-5">
                <div class="col">
                    <img src="gcash.jpg" class="img-fluid" alt="...">
                </div>

         
         <div class="col">
         <img src="singkollective.png" class="img-thumbnail" >
            <div clas="container">
                <label for="fname" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fname" name="fname" readonly>

                <label for="contact" class="form-label">Contact Number</label>
                <input type="number" class="form-control" id="fname" name="contact"  name="contact"readonly>

                <label for="contact" class="form-label">Email Address</label>
                <input type="number" class="form-control" id="fname" name="contact"  name="contact"readonly>
                </div>
            </div>
         </div>
    </div>

</div>
</body>
</html>
