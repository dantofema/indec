<!-- fileupload.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Laravel File Upload With Progress bar</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
    <style>
        .progress { position:relative; width:100%; }
        .bar { background-color: #008000; width:0%; height:20px; }
         .percent { position:absolute; display:inline-block; left:50%; color: #7F98B2;}
   </style>
</head>
<body>
 
<div class="container">
        <h2>Laravel File Upload With Progress Bar</h2>
            <form method="POST" action="{{ action('FileUploadController@fileStore') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input name="file" type="file" class="form-control"><br/>
                    <div class="progress">
                        <div class="bar"></div >
                        <div class="percent">0%</div >
                    </div>
                    <br>
                    <input type="submit"  value="Submit" class="btn btn-primary">
                </div>
            </form>    
</div>
</body>
</html>

