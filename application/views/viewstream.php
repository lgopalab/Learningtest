<html>
<script src="{{ URL::asset('ckeditor/ckeditor.js') }}"></script>

<body>
    <div class="container">
        <?php
            foreach($courses as $course)
            {
            ?>
                <div class="row">
                    <div class="jumbotron col-md-4 text-center">
                        <h2 style="word-wrap: break-word;"><?php echo $course['name'];?></h2>
                    </div>
                    <div class="col-md-8">
                        <p><?php echo $course['long_description'];?></p>
                        <a class="btn btn-default" href="<?php echo site_url('home/viewcourse')."/".$course['stream']."/".$course['name'];?>">More Info</a>
                    </div>
                </div>
            <hr>

          <?php
            }

            ?>

    </div>

</body>
</html>