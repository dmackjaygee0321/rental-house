<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		
			extract($_POST);		
			$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
            $customerQry = $this->db->query("SELECT * FROM customer where email = '".$username."' and password = '".md5($password)."' ");

            if($qry->num_rows > 0){
				foreach ($qry->fetch_array() as $key => $value) {
					if($key != 'password' && !is_numeric($key))
						$_SESSION['login_'.$key] = $value;
				}
				if($_SESSION['login_type'] != 1){
					foreach ($_SESSION as $key => $value) {
						unset($_SESSION[$key]);
					}
					return 2 ;
					exit;
				}
					return 1;
			} else if($customerQry->num_rows > 0){
                foreach ($customerQry->fetch_array() as $key => $value) {
                    if($key != 'password' && !is_numeric($key))
                        $_SESSION['login_'.$key] = $value;
                }
                $_SESSION["login_name"] = $_SESSION["login_fname"] . " " . $_SESSION["login_lname"];
                $_SESSION['login_type'] = 3;
                return 1;
                exit;
            } else {
				return 3;
			}
	}
	function login2(){
		
			extract($_POST);
			if(isset($email))
				$username = $email;
		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			if($_SESSION['login_alumnus_id'] > 0){
				$bio = $this->db->query("SELECT * FROM alumnus_bio where id = ".$_SESSION['login_alumnus_id']);
				if($bio->num_rows > 0){
					foreach ($bio->fetch_array() as $key => $value) {
						if($key != 'passwors' && !is_numeric($key))
							$_SESSION['bio'][$key] = $value;
					}
				}
			}
			if($_SESSION['bio']['status'] != 1){
					foreach ($_SESSION as $key => $value) {
						unset($_SESSION[$key]);
					}
					return 2 ;
					exit;
				}
				return 1;
		}else{
			return 3;
		}
	}

    function customerSignup() {
        extract($_POST);

       if($password != $confirm_password){
            return 3;
            exit;
        }

        $chk = $this->db->query("Select * from customer where email = '$email'")->num_rows;
        if($chk > 0){
            return 2;
            exit;
        }
        $password = md5($password);
        $save = $this->db->query("INSERT INTO customer values (null, '$fname', '$lname', '$email', '$contact', '$password', current_timestamp)");

        if($save)
            return 1;
    }
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user()
    {
        extract($_POST);
        if ($_SESSION["login_type"] != 3) {
            $data = " name = '$name' ";
            $data .= ", username = '$username' ";
            if (!empty($password))
                $data .= ", password = '" . md5($password) . "' ";
            if(isset($type))
            $data .= ", type = '$type' ";
            $chk = $this->db->query("Select * from users where username = '$username' and id !='$id' ")->num_rows;
            if ($chk > 0) {
                return 2;
                exit;
            }
            if (empty($id)) {
                $save = $this->db->query("INSERT INTO users set " . $data);
            } else {
                $save = $this->db->query("UPDATE users set " . $data . " where id = " . $id);
            }
            if ($save) {
                return 1;
            }
        } else {
            $chk = $this->db->query("Select * from customer where email = '$username' and id !='$id' ")->num_rows;
            if ($chk > 0) {
                return 2;
                exit;
            }

            $updatePassword = "";
            if (!empty($password))
                $updatePassword = ", password = '" . md5($password) . "' ";

            $save = $this->db->query("UPDATE customer set fname = '$fname', lname = '$lname', email = '$username', contact = '$contact' $updatePassword where id = " . $id);


            if ($save) {
                return 1;
            }

        }
	}


    function save_customer()
    {
        extract($_POST);
            $chk = $this->db->query("Select * from customer where email = '$username' and id !='$id' ")->num_rows;
            if ($chk > 0) {
                return 2;
                exit;
            }

            $updatePassword = "";
            if (!empty($password))
                $updatePassword = ", password = '" . md5($password) . "' ";

            if (empty($id)) {
                $save = $this->db->query("INSERT INTO customer values (null,'$fname', '$lname', '$username', '$contact', '".md5($password)."', current_timestamp)");
            } else {
                $save = $this->db->query("UPDATE customer set fname = '$fname', lname = '$lname', email = '$username', contact = '$contact' $updatePassword where id = " . $id);
            }


            if ($save) {
                return 1;
            }
    }
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function signup(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("INSERT INTO users set ".$data);
		if($save){
			$uid = $this->db->insert_id;
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("INSERT INTO alumnus_bio set $data ");
			if($data){
				$aid = $this->db->insert_id;
				$this->db->query("UPDATE users set alumnus_id = $aid where id = $uid ");
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}
	function update_account(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' and id != '{$_SESSION['login_id']}' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("UPDATE users set $data where id = '{$_SESSION['login_id']}' ");
		if($save){
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("UPDATE alumnus_bio set $data where id = '{$_SESSION['bio']['id']}' ");
			if($data){
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}

	function save_settings(){
		extract($_POST);
		$data = " name = '".str_replace("'","&#x2019;",$name)."' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '".htmlentities(str_replace("'","&#x2019;",$about))."' ";
		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
					$data .= ", cover_img = '$fname' ";

		}
		
		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set ".$data);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set ".$data);
		}
		if($save){
		$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
		foreach ($query as $key => $value) {
			if(!is_numeric($key))
				$_SESSION['system'][$key] = $value;
		}

			return 1;
				}
	}

	
	function save_category(){
		extract($_POST);
		$data = " name = '$name' ";
			if(empty($id)){
				$save = $this->db->query("INSERT INTO categories set $data");
			}else{
				$save = $this->db->query("UPDATE categories set $data where id = $id");
			}
		if($save)
			return 1;
	}
	function delete_category(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM categories where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_house(){
		extract($_POST);
		$data = " house_no = '$house_no' ";
		$data .= ", description = '$description' ";
		$data .= ", price = '$price' ";

        if(!$id)
            $id = 0;
		$chk = $this->db->query("SELECT * FROM houses where house_no = '$house_no' and id !=  $id ")->num_rows;
		if($chk > 0 ){
			return 2;
			exit;
		}
			if(empty($id)){
				$save = $this->db->query("INSERT INTO houses set $data");
			}else{
				$save = $this->db->query("UPDATE houses set $data where id = $id");
			}
		if($save)
			return 1;
	}
	function delete_house(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM houses where id = ".$id);
		if($delete){
			return 1;
		}
	}

    function payment(){
        extract($_POST);

        $chk = $this->db->query("select * from houses where id = $propertyId and (
                       (select count(id) from tenants where house_id = $propertyId and status = 1) > 0
                       or (select count(id) from payments where house_id = $propertyId and approved_date IS NULL and decline_date IS NULL) > 0)")->num_rows;
        if($chk != 0)
            return 2;

        if(isset($_FILES["file"])) {
            $file = $_FILES["file"]["name"];
            $target_dir = "./uploads/";
            $target_file = $target_dir . basename($_FILES["file"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
        }

        $chk = $this->db->query("insert into payments values (null, $customerId, $propertyId, $payment, '', current_timestamp, '$file', null, null, 'Initial Payment', null)");

        if($chk)
            return 1;
    }

    function bill_payment(){
        extract($_POST);

        $chk = $this->db->query("select * from payments where invoice = '$id' and amount = $payment and approved_date is null")->num_rows;
        if($chk != 0)
            return 2;

        if(isset($_FILES["file"])) {
            $file = $_FILES["file"]["name"];
            $target_dir = "./uploads/";
            $target_file = $target_dir . basename($_FILES["file"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
        }

        $chk = $this->db->query("insert into payments values (null, $customer_id, $house_id, $payment, '$id', current_timestamp, '$file', null, null, 'Monthly Payment', null)");

        if($chk)
            return 1;
    }

    function approve_payment() {
        extract($_POST);
        date_default_timezone_set('Asia/Manila');

        $payment = $this->db->query("SELECT * FROM payments where id = $id");
        $payment = $payment->fetch_assoc();


        $house = $this->db->query("SELECT * FROM houses where id =".$payment["house_id"]);
        $house = $house->fetch_assoc();


        if ($type === "Initial Payment") {
            $currentDate = new DateTime();
            $due_date = $currentDate->format('Y-m-d');

            $remaining = ($house["price"] * 2) - $payment["amount"];

            $this->db->query("INSERT INTO tenants values(null, ".$house["id"].", 1, current_timestamp, ".$payment["customer_id"].")");

            $tenant_id = $this->db->insert_id;

            if($remaining > 0) {
                $this->db->query("INSERT INTO bills values(null, $tenant_id, ".$payment["house_id"].", $remaining, 0, '$due_date', current_timestamp, 1)");
            }

            $currentDate->add(new DateInterval('P2M'));
            for ($x = 1;$x <= 10; $x++) {
                $currentDate->add(new DateInterval('P1M'));
                $due_date = $currentDate->format('Y-m-d');
                $this->db->query("INSERT INTO bills values(null, $tenant_id, ".$payment["house_id"].", ".$house["price"].", 0, '$due_date', current_timestamp, 1)");
            }
        } else {
            $this->db->query("update bills set amount_paid = (amount_paid + ".$payment["amount"].") where id = ".$payment["invoice"]);
        }

        $this->db->query("update payments set approved_date = current_timestamp, remarks = '$remarks' where id =".$id);

        return 1;
    }

    function decline_payment() {
        extract($_POST);

        $this->db->query("update payments set decline_date = current_timestamp, remarks = '$remarks' where id =".$id);

        return 1;
    }
	function save_tenant(){
		extract($_POST);
		$data = " firstname = '$firstname' ";
		$data .= ", lastname = '$lastname' ";
		$data .= ", middlename = '$middlename' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", house_id = '$house_id' ";
		$data .= ", date_in = '$date_in' ";
			if(empty($id)){
				
				$save = $this->db->query("INSERT INTO tenants set $data");
			}else{
				$save = $this->db->query("UPDATE tenants set $data where id = $id");
			}
		if($save)
			return 1;
	}
	function delete_tenant(){
		extract($_POST);
        $tenant = $this->db->query("select * from tenants where id = $id");
        $tenant = $tenant->fetch_assoc();

		$delete = $this->db->query("UPDATE tenants set status = 0 where id = ".$id);
        $delete1 = $this->db->query("UPDATE bills set is_active = 0 where STR_TO_DATE(due_date, '%Y-%m-%d') > CURRENT_TIMESTAMP() and house_id = ".$tenant["house_id"]." and tenant_id = ".$id);
		if($delete && $delete1){
			return 1;
		}
	}

    function get_tenant() {

        extract($_POST);
        $tenants = $this->db->query("SELECT t.*, c.fname, c.lname, h.house_no, h.price, (select date_created from payments where t.customer_id = customer_id and approved_date is not null order by date_created DESC limit 1) as last_payment, (SELECT sum(amount - amount_paid) FROM `bills` WHERE STR_TO_DATE(due_date, '%Y-%m-%d') < CURRENT_TIMESTAMP() and is_active = 1 and customer_id = c.id and tenant_id = t.id) as outstanding_balance, (select sum(amount) from payments where customer_id = c.id and house_id = t.house_id) as total_paid
                    from tenants t 
                    left join customer c on c.id = t.customer_id 
                    left join houses h on h.id = t.house_id where t.id = {$id} ");

        return json_encode($tenants->fetch_assoc());
    }
	function get_tdetails(){
		extract($_POST);
		$data =array();
		$tenants =$this->db->query("SELECT t.*,concat(t.lastname,', ',t.firstname,' ',t.middlename) as name,h.house_no,h.price FROM tenants t inner join houses h on h.id = t.house_id where t.id = {$id} ");
		foreach($tenants->fetch_array() as $k => $v){
			if(!is_numeric($k)){
				$$k = $v;
			}
		}
		$months = abs(strtotime(date('Y-m-d')." 23:59:59") - strtotime($date_in." 23:59:59"));
		$months = floor(($months) / (30*60*60*24));
		$data['months'] = $months;
		$payable= abs($price * $months);
		$data['payable'] = number_format($payable,2);
		$paid = $this->db->query("SELECT SUM(amount) as paid FROM payments where id != '$pid' and tenant_id =".$id);
		$last_payment = $this->db->query("SELECT * FROM payments where id != '$pid' and tenant_id =".$id." order by unix_timestamp(date_created) desc limit 1");
		$paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;
		$data['paid'] = number_format($paid,2);
		$data['last_payment'] = $last_payment->num_rows > 0 ? date("M d, Y",strtotime($last_payment->fetch_array()['date_created'])) : 'N/A';
		$data['outstanding'] = number_format($payable - $paid,2);
		$data['price'] = number_format($price,2);
		$data['name'] = ucwords($name);
		$data['rent_started'] = date('M d, Y',strtotime($date_in));

		return json_encode($data);
	}
	
	function save_payment(){
		extract($_POST);
        $tenant = $this->db->query("select * from tenants where id = $tenant_id");
        $tenant = $tenant->fetch_assoc();

        $bills = $this->db->query("select * from bills where tenant_id = $tenant_id and amount_paid < amount and is_active = 1 order by STR_TO_DATE(due_date, '%Y-%m-%d') asc");
        $totalPayment = 0;
        $billIds = [];
        while($row=$bills->fetch_assoc())
        {
            if($amount <= 0)
                break;

            $balance = $row["amount"] - $row["amount_paid"];
            $payment = $amount -  $balance;

            if ($payment <= 0) {
                $payment = $amount;
            } else {
                $payment = $balance;
            }

            $this->db->query("update bills set amount_paid = (amount_paid + $payment) where id = ".$row["id"]);
            $totalPayment += $payment;
            $amount -= $payment;
            $billIds[] = $row["id"];
        }

        $this->db->query("insert into payments values (null, ".$tenant["customer_id"].", ".$tenant["house_id"].", $totalPayment, '".implode(",", $billIds)."', current_timestamp, '', current_timestamp, null, 'Monthly Payment', '$invoice')");

        return 1;
	}
	function delete_payment(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM payments where id = ".$id);
		if($delete){
			return 1;
		}
	}
}