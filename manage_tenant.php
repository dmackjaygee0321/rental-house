<?php 
include 'db_connect.php'; 
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM tenants where id= ".$_GET['id']);
foreach($qry->fetch_array() as $k => $val){
	$$k=$val;
}
}
?>
<div class="container-fluid">
	<form action="" id="manage-tenant">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="form-group row">
            <div class="col-md-4">
                <label for="" class="control-label">User</label>
                <select name="customer_id" id="" class="custom-select select2">
                    <option value="">Select User</option>
                    <?php
                    $house = $conn->query("SELECT * from customer");
                    while($row= $house->fetch_assoc()):
                        ?>
                        <option value="<?php echo $row['id'] ?>" <?php echo isset($house_id) && $house_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['fname'] . " " . $row['lname'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="" class="control-label">Property #</label>
                <select name="house_id" id="" class="custom-select select2">
                    <option value="">Select Property</option>
                    <?php
                    $house = $conn->query("SELECT * FROM houses h where (
                       (select count(id) from tenants where house_id = h.id and status = 1) = 0
                       and (select count(id) from payments where house_id = h.id and approved_date IS NULL and decline_date IS NULL) = 0) order by id asc");
                    while($row= $house->fetch_assoc()):
                        ?>
                        <option value="<?php echo $row['id'] ?>" <?php echo isset($house_id) && $house_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['house_no'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
			<div class="col-md-4">
				<label for="" class="control-label">Registration Date</label>
				<input type="date" class="form-control" name="date_in"  value="<?php echo isset($date_in) ? date("Y-m-d",strtotime($date_in)) :'' ?>" required>
			</div>
		</div>
	</form>
</div>
<script>
	
	$('#manage-tenant').submit(function(e){
		e.preventDefault()
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_tenant',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully saved.",'success')
						setTimeout(function(){
							location.reload()
						},1000)
				}
			}
		})
	})
</script>