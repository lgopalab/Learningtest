<title>LMS-1</title>

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <img class="img-responsive img-rounded" src="http://sandraswancoaching.com/wp-content/uploads/2014/05/bigstock-woods-44771632-900x350.jpg" alt="">
        </div>

        <div class="col-md-4">
            <h1>Standard Materials</h1>
            <p>Insert any content here!</p>
            <a class="btn btn-primary btn-lg" href="#">Call to Action!</a>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <div id="filterOptions">
                    <a href="#" id="all" class="btn btn-secondary btn-lg active">All</a>
                    <?php
                        foreach ($streams as $stream)
                        { ?>
                             <a href="#" id="<?php echo $stream['stream_name'];?>" class="btn btn-secondary btn-lg active"><?php echo $stream['stream_name']; ?></a>
                        <?php
                        }
                        ?>


                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div id="Holder">
            <?php
                foreach ($courses as $course)
                {
                    ?>
                    <div class="col-md-4 portfolio-item <?php echo $course['stream']; ?>">
                        <h2><?php echo $course['name']; ?></h2>
                        <p><?php echo $course['short_description']; ?></p>
                        <a class="btn btn-default" href="home/viewcourse/<?php echo $course['stream']."/"; echo $course['name']; ?>">More Info</a>
                    </div>

                <?php
                }
                ?>
        </div>
    </div>

</div>
<br>
<script>
    $(document).ready(function () {
        $('#filterOptions a').click(function () {
            var id = $(this).attr('id');
            $('#filterOptions a').removeClass('active');
            $(this).addClass('active');

            if (id == 'all') {
                $('#Holder').children().show(1000);
            } else {
                $('#Holder').children('div:not(.' + id + ')').hide(1000);
                $('#Holder').children('div.' + id).show(1000);
            }
            return false;
        });
    });
</script>
