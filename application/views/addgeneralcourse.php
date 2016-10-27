<br>
<br>
<br>
<br>
<body>
    <div class="container">

        <div class="" id="message"></div>

            <form action="" method="post" enctype="multipart/form-data" id="upload" class="form-group">
                <br>
            <div class="col-md-12">
                <div class="row">
                <label class="col-md-4" for="course_name">Course Name:</label>
                <input class="col-md-8" type="text" name="course_name" id="course_name">
                </div>

                <br>

                <div class="row">
                    <label class="col-md-4" for="department">Department</label>
                    <div class="radio col-md-8">
                        <?php
                        foreach ($streams as $stream) {
                            ?>
                            <input type="radio" name="department" id="department" value="<?php echo $stream['stream_name']; ?>"><?php echo $stream['stream_name']; ?><br>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <br>

                <div class="row">
                <label class="col-md-4" for="short_description">Enter Short Description</label>
                <input class="col-md-8" id="short_description" name="short_description">
                <br>
                <p>&nbsp&nbsp&nbsp(Just enter 1 line)</p>
                </div>

                <br>

                <div class="row">
                <label class="col-md-4" for="long_description">Enter Long Description</label>
                <input class="col-md-8" id="long_description" name="long_description">
                </div>

                <br>

                <div class="row">
                    <label class="col-md-4" for="textarea">Enter Course Content</label>
                    <div class="container col-md-8">
                        <textarea id="textarea" name="content">Add your course content here. You can format the content in Word and paste it here for better results.</textarea>
                    </div>
                </div>

                <br>

                <div class="row" id="filediv">

                </div>

                <div class="row">
                    <label class="col-md-4" for="file1"></label>
                    <input type="button" class="btn btn-default btn-file col-md-8" id = "addfilebutton" value="Add files" onClick="addInput('filediv')">
                </div>

                <br>

                <div class="row">
                    <input class="btn btn-default btn-file col-md-4 col-md-offset-4"  type="submit" value="Upload">
                </div>
                <br>
                <br>
                <br>
                <br>

                <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </div>

            </form>
    </div>

</body>
<script>

    var form = document.getElementById('upload');
    var request = new XMLHttpRequest();
    form.addEventListener('submit', function(e){
        e.preventDefault();
        var formdata = new FormData(form);
        request.open('post', '<?php echo site_url('home/addgeneralcourse'); ?>');
        request.addEventListener("load", transferComplete);
        request.send(formdata);
    });

    function transferComplete(data){
        alert(data.currentTarget.response);
        try
        {
            response = JSON.parse(data.currentTarget.response);
        }
        catch(err)
        {
            alert(err);
            document.getElementById('message').innerHTML = data.currentTarget.response;
        }

        if(response.success)
        {
            document.getElementById("message").className = "alert alert-success";
            document.getElementById('message').innerHTML = "Successfully Uploaded Files!";
        }
        else
        {
            if(response.message == "invalid_file")
            {
                document.getElementById("message").className = "alert alert-danger";
                document.getElementById('message').innerHTML = "Please upload the correct format file.";
            }
            else if(response.message == "not_all_files")
            {
                document.getElementById("message").className = "alert alert-danger";
                document.getElementById('message').innerHTML = "Not all files were uploaded.";
            }
            else if(response.message == "name_exists")
            {
                document.getElementById("message").className = "alert alert-danger";
                document.getElementById('message').innerHTML = "Course name already exists. Try editing the content.";
            }

        }
    }
</script>
<script>
    var counter = 0;
    var limit = 5;

    function addInput(divName){
        if (counter == limit)  {
            alert("You have reached the limit of adding " + counter + " inputs");
        }
        else {
            var newdiv = document.createElement('div');
            newdiv.setAttribute('id','extrafile'+(counter));
            newdiv.innerHTML = "<br><div class='row'><label class='col-md-4' for='file"+(counter)+"'></label><input class='btn btn-default btn-file col-md-6' type='file' name='file[]' id='file"+(counter)+"'><button class='btn btn-danger col-md-1' onClick='removeInput("+(counter)+")' value='file"+(counter)+"'>Remove</button></div><br>";
            document.getElementById(divName).appendChild(newdiv);
            counter++;
            if (counter>0)
            {
                document.getElementById("addfilebutton").value = "Add another file";
            }

        }
    }

    function removeInput(number){

        var id = "extrafile"+number;
        document.getElementById(id).remove();
        counter--;
        if(counter == 0)
        {
            document.getElementById("addfilebutton").value = "Add files";
        }
    }

</script>