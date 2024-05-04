<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PAYMENT SUMMARY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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


  <div class="container mt-5" >
    <form>
                        <div class="row g-0 text-center">
                            <div class="col-sm-12 col-md-">
                        
                        <div class="card">
                        <div class="card-body">
                            <h4>Customer Payment</h4>
                        </div>
                        </div>
        
        
                        <div class="mb-3">
                            <label for="disabledTextInput" class="form-label">Full Name:</label>
                            <input type="text" id="disabledTextInput" class="form-control" value="Jansen Mark Gestala" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="disabledTextInput" class="form-label">Payable Amount:</label>
                            <input type="text" class="form-control"  value="5000" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="disabledTextInput" class="form-label">Amount:</label>
                            <input type="number" class="form-control" placeholder="Enter Amount">
                        </div>
                        
                       


                        <div class="mb-3">
                            <label for="disabledTextInput" class="form-label" >Upload Receipt:</label>
                            <input type="file" class="form-control" placeholder="Enter Amount">
                        </div>

                        <div class="mb-3">
                        <div class="form-check">
                        </div>
                        </div>
                        <button type="submit"class="btn btn-danger">Submit</button>


                    </div>

                    <div class="col-6 col-md-4">

            </div>
        </div>
    </form>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>