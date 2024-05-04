<?php include 'db_connect.php' ?>
<style>
   span.float-right.summary_icon {
    font-size: 3rem;
    position: absolute;
    right: 1rem;
    top: 0;
}
.imgs{
		margin: .5em;
		max-width: calc(100%);
		max-height: calc(100%);
	}
	.imgs img{
		max-width: calc(100%);
		max-height: calc(100%);
		cursor: pointer;
	}
	#imagesCarousel,#imagesCarousel .carousel-inner,#imagesCarousel .carousel-item{
		height: 60vh !important;background: black;
	}
	#imagesCarousel .carousel-item.active{
		display: flex !important;
	}
	#imagesCarousel .carousel-item-next{
		display: flex !important;
	}
	#imagesCarousel .carousel-item img{
		margin: auto;
	}
	#imagesCarousel img{
		width: auto!important;
		height: auto!important;
		max-height: calc(100%)!important;
		max-width: calc(100%)!important;
	}
</style>

<div class="containe-fluid">
	<div class="row mt-3 ml-3 mr-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <?php echo "Welcome back ". $_SESSION['login_name']."!"  ?>
                </div>
            </div>      			
        </div>


        <?php
        $i = 1;
        $house = $conn->query("SELECT * FROM houses order by id asc");
        while($row=$house->fetch_assoc()): ?>
        <div class="col-lg-4" style="padding-top: 30px">
            <div class="card" style="margin: 5px">
                <img class="card-img-top" src="https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">Property #: <?= $row["house_no"] ?></h5>
                    <p class="card-text"><?= $row["description"] ?></p>
                    <p>Price: <span style="font-weight: bolder"><?= number_format($row['price'],2) ?></span></p>
                    <a href="javascript:void(0)" data-id="<?= $row["id"] ?>" class="btn btn-primary rent-now">Rent Now</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
<script>
    $('.rent-now').click(function(){
        const id = $(this).attr("data-id")
        uni_modal("Rent Now","rent_property.php?id="+id+"&mtype=own", "", true)
    })
</script>