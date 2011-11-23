<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Uploading files with PHP</title>
		<style type="text/css">
			body{
				padding: 0;
				margin: 0;
				background: #fff;
			}
			h1{
				font: bold 16pt Arial, Helvetica, sans-serif;
				color: #000;
				text-align: center;
			}
			p{
				font: normal 10pt Arial, Helvetica, sans-serif;
				color: #000;
			}
			form{
				display: inline;
			}
			#formcontainer{
				width: 50%;
				padding: 10px;
				margin-left: auto;
				margin-right: auto;
				background: #eee;
				border: 1px solid #666;
			}
		</style>
	</head>
	<body>
		<h1>Uploading files with PHP</h1>
		<div id="formcontainer">
			<form enctype="multipart/form-data" action="upload_file.php" method="post">
				<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
				<p>File to upload <input name="userfile" type="file" />
					<input type="submit" name="send" value="Upload File" /></p>
			</form>
		</div>
	</body>
</html>
