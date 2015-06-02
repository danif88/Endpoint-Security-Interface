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

        $role=$result_array["data"];

	if($result_array['status'] !== 1 or (strcmp($role,"superUser")!==0 and strcmp($role,"graphOwner")!==0)){
		echo "<meta http-equiv='refresh' content='0;url=" . $config['uri_int'] . "'>";
	}

	$result = file_get_contents($config['uri_api'] . "/getUserGraphs?session=" . $_GET["session_id"], false, $context);

	$result_array=json_decode($result,true);

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
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/bootbox.min.js"></script>
	<script type="text/javascript" src="js/utilities.js"></script>
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
                                                        <a href="#" onclick="location.href = '<?php echo $config['uri_int'] . "/admin.php?session_id=" . $_GET['session_id']. "&name=" . $_GET['name'];?>';">Admin Graphs</a>
                                                </li>
                                        </ul>
                                        <?php }?>
                                        <?php if($role=="superUser"){ ?>
                                        <ul class="nav navbar-nav">
                                                <li class="active">
                                                        <a href="#" onclick="location.href = '<?php echo $config['uri_int'] . "/createUser.php?session_id=" . $_GET['session_id']. "&name=" . $_GET['name'];?>';">Users</a>
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
				</div>
			</nav>
		</div>
	</div>
	<div class="row clearfix">
		<h2>Graphs</h2>
		<label id="msg_error_graphs" style="color:red"></label>
		<table id="events-id2" data-url="data1.json" data-height="299" data-search="true" class="table table-hover">
		    <thead>
		    <tr>
		        <th data-field="id" data-sortable="true">Graph</th>
		        <th data-field="name" data-sortable="true">Delete Graph</th>
		        <th data-field="name" data-sortable="true">Add User to Graph</th>
		    </tr>
		    </thead>
		    <tbody>
		    	<?php
		    		$array = json_decode($result_array["data"]);
		    		$n=0;
		    		foreach ($array as $key1 => $value1) {
		    			$n++;
		    	?>
		    	<tr data-index="0" id="<?php echo "graphs_" . $n; ?>">
		    		<td style=""><?php echo $key1;?></td>
		    		<td style="">
	    				<a class="remove ml10" onclick="<?php echo 'deleteGraph(\''.$key1.'\','.$n.')';?>" title="Remove">
	    					<i class="glyphicon glyphicon-remove"></i>
		    			</a>
				</td>
				<td>
		    			<a class="edit ml10" onclick="<?php echo 'addUserToGraph(\''.$key1.'\')';?>" title="Edit">
		    				<i class="glyphicon glyphicon-plus"></i>
		    			</a>
		    		</td>
		    	</tr>
		    	<?php } ?>
		    </tbody>
		</table>
	</div>
	<div class="row clearfix">
		<h2>Users</h2>
		<label id="msg_error_users" style="color:red"></label>
		<table id="events-id2" data-url="data1.json" data-height="299" data-search="true" class="table table-hover">
		    <thead>
		    <tr>
		        <th data-field="id" data-sortable="true">Graph</th>
		        <th data-field="name" data-sortable="true">User</th>
		        <th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents">Item Operate</th>
		    </tr>
		    </thead>
		    <tbody>
		    	<?php
		    		$array = json_decode($result_array["data"]);
		    		$n=0;
		    		foreach ($array as $key1 => $value1) {
		    			$n++;
		    			foreach ($value1 as $key => $value) {
		    	?>
		    	<tr data-index="0" id="<?php echo "users_" . $n; ?>">
		    		<td style=""><?php echo $key1;?></td>
		    		<td style=""><?php echo $value;?></td>
		    		<td style="">
	    				<a class="remove ml10" onclick="<?php echo 'deleteUserFromGraph(\''.$value.'\',\''.$key1.'\','.$n.')';?>" title="Remove">
	    					<i class="glyphicon glyphicon-remove"></i>
		    			</a>
		    		</td>
		    	</tr>
		    	<?php }} ?>
		    </tbody>
		</table>
	</div>
	<div class="row clearfix">
		<h2>Create New User</h2>
		<div class="col-md-12 column">
			<label id="msg_error" style="color:red"></label>
			<div class="form-group">
		  		<label for="usr">Name:</label>
		  		<input type="text" class="form-control" id="user" name="user">
			</div>
			<div class="form-group">
		  		<label for="pwd">Password:</label>
		  		<input type="password" class="form-control" id="password" name="password">
			</div>
			<button type="button" class="btn btn-default" onclick="createUser();">Create</button>
			<label id="msg"></label>
		</div>
	</div>
</div>
</body>
</html>
