
<script src="{{ URL::asset('ckeditor/ckeditor.js') }}"></script>

<body>
    <div class="container text-center">
        <div class="jumbotron">

            <h1><?php echo $course[0]['name']; ?></h1>
            <p><?php echo $course[0]['long_description'];?></p>
        </div>

        <?php echo $course[0]['content']; ?>
        <?php
        foreach ($files as $file)
        {
            ?>
            <iframe src="<?php echo $file['temp_location'];?>" style="width:1000px; height:500px;" frameborder="0"></iframe>
            <br>
        <?php
        }
        ?>

    </div>

</body>
