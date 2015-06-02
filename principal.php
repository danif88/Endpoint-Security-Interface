<?php
	$config = require 'config.php';

	if(!isset($_GET["session_id"])){
		echo "<meta http-equiv='refresh' content='0;url=" . $config['uri_int'] . "'>";
		exit();
	}
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	    $ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
	    $ip = $_SERVER['REMOTE_ADDR'];
	}

	$opts = array(
	  'http'=>array(
	    'method'=>"GET",
	    'header'=>"X-Forwarded-For: $ip"
	  )
	);

$context = stream_context_create($opts);
	$result = file_get_contents($config['uri_api'] . "/getUserRole?session=" . $_GET["session_id"], false, $context);

	$result_array=json_decode($result,true);

	if($result_array['status'] !== 1){
		echo "<meta http-equiv='refresh' content='0;url=" . $config['uri_int'] . "'>";
	}

	$role=$result_array["data"];
	
	$result_graphs = file_get_contents($config['uri_api'] . "/getGraphsPermited?session=" . $_GET["session_id"], false, $context);

	$result_graphs_array=json_decode($result_graphs,true);

	if($result_graphs_array['status'] !== 1){
		echo "<meta http-equiv='refresh' content='0;url=" . $config['uri_int'] . "'>";
	}

	$graphs=$result_graphs_array["data"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Bootstrap 3, from LayoutIt!</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

	<!--link rel="stylesheet/less" href="less/bootstrap.less" type="text/css" /-->
	<!--link rel="stylesheet/less" href="less/responsive.less" type="text/css" /-->
	<!--script src="js/less-1.3.3.min.js"></script-->
	<!--append ‘#!watch’ to the browser URL, then refresh the page. -->
	
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">

  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
  <![endif]-->

  <!-- Fav and touch icons -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-57-precomposed.png">
  <link rel="shortcut icon" href="img/favicon.png">
  
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery.form.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/bootbox.min.js"></script>
	<script type="text/javascript" src="js/scripts.js"></script>
</head>

<body>
<input type="hidden" id="session_id" value="<?php echo $_GET['session_id'];?>">
<input type="hidden" id="uri_int" value="<?php echo $config['uri_int'];?>">
<input type="hidden" id="uri_api" value="<?php echo $config['uri_api'];?>">
<input type="hidden" id="uri_fuseki" value="<?php echo $config['uri_fuseki'];?>">

<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
			<nav class="navbar navbar-default" role="navigation">
				<div class="navbar-header">
					 <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> <a class="navbar-brand" href="#" onclick="location.href = '<?php echo $config['uri_int'] . "/principal.php?session_id=" . $_GET['session_id']. "&name=" . $_GET['name'];?>';">Security RDF</a>
				</div>
				
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<?php if($role=="superUser" or $role=="graphOwner"){ ?>
					<ul class="nav navbar-nav">
						<li class="active">
							<a href="#" onclick="location.href = '<?php echo $config['uri_int'] . "/admin.php?session_id=" . $_GET['session_id'] . "&name=" . $_GET['name'] ;?>';">Admin Graphs</a>
						</li>
					</ul>
					<?php }?>
					<?php if($role=="superUser"){ ?>
                                        <ul class="nav navbar-nav">
                                                <li class="active">
                                                        <a href="#" onclick="location.href = '<?php echo $config['uri_int'] . "/createUser.php?session_id=" . $_GET['session_id']. "&name=" . $_GET['name'] ;?>';">Users</a>
                                                </li>
                                        </ul>
                                        <?php }?>
					<ul class="nav navbar-nav navbar-right">
                                                <li>
                                                	<b><?php echo "User: " . $_GET['name'];?></b>
						</li>
                                        </ul>
					<ul class="nav navbar-nav navbar-right">
						<li>
							<a onclick="logOut();">Log Out</a>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li>
							<a onclick="changePass();">Change Password</a>
						</li>
					</ul>
				</div>
				
			</nav>
			<div class="row clearfix">
				<div class="col-md-12 column">
					<h2>
						SPARQL Query
						<button type="button" class="btn btn-default" onclick="exampleQueries();">Examples</button>
					</h2>
					<textarea class="form-control" rows="5" id="query_select">PREFIX a: &lt;http://example.org/&gt;&#10;SELECT * where {GRAPH a:ng1 {?o ?p ?q}}</textarea>
					<div class="btn-group">
						 <select class="selectpicker" id="outputType">
						    <option value="text">Text</option>
						    <option value="json">JSON</option>
					        <option value="xml">XML</option>
					        <option value="csv">CSV</option>
					        <option value="tsv">TSV</option>
						 </select>
					</div> <button type="button" class="btn btn-default" onclick="doQuery();">Query</button>
				</div>
			</div>
			<div class="row clearfix">
				<div class="col-md-12 column">
					<h2>
						SPARQL Update
						<button type="button" class="btn btn-default" onclick="exampleUpdates();">Examples</button>
					</h2>
					<select id="graphs_permited" onclick="url_update();">
                                        <?php
                                                $array = json_decode($graphs);
                                                foreach ($array as $key1 => $value1) {
                                        ?>
                                                <option value="<?php echo $key1;?>"><?php echo $key1;?></option>
                                        <?php } ?>
                                        </select>
					<label id="msg_error_update" style="color:red"></label>
					<form id="updateForm" action="<?php echo $config['uri_api'] . '/update'?>" enctype="multipart/form-data" method="post">
					<textarea class="form-control" rows="5" id="query" name="query"></textarea>
					<input type="hidden" name="session" value="<?php echo $_GET['session_id'];?>">
					<button type="submit" class="btn btn-default">Update</button>
					</form>
				</div>
			</div>
			<?php if($role=="superUser" or $role=="graphOwner"){ ?>
			<div class="row clearfix">
				<div class="col-md-12 column">
					<h2>
						File Upload
					</h2>
					<label style="color:gray"><b>Example:  </b>http://example.org/ng1</label>
					<br>
					<label id="msg_error" style="color:red"></label>
					<form id="fileForm" action="<?php echo $config['uri_api'] . '/upload'?>" enctype="multipart/form-data" method="post">
					<div class="form-group">
					  <label for="usr">Graph:</label>
					  <input type="text" class="form-control" id="graph" name="name" placeholder="http://example.org/ng1" value="http://example.org/ng1">
					</div>
					<div class="form-group">
					    <label for="exampleInputFile">File input</label>
					    <input type="file" id="exampleInputFile" name="file">
					</div>
					<input type="hidden" name="session" value="<?php echo $_GET['session_id'];?>">
					<button type="submit" class="btn btn-default">Upload</button>
					<form>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
</body>
</html>
