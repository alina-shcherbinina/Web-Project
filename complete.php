<?php

include 'autoload.php';

use app\Task;

$task = new Task;
if (getenv('REQUEST_METHOD') === 'POST')
{
	$task = Task::getById($_POST['id']);
	if(isset($_POST['checked']))
	{
		$task->status = Task::STATUS_DONE;
	}
	else
	{
		$task->status = Task::STATUS_PENDING;
	}
	if ($task->save())
	{
		header('Status: 302 Found');
		header('HTTP/1.1 302 Found');
		header('Location: /');
		exit;
	}
}
else
{
	header('Status: 302 Found');
	header('HTTP/1.1 302 Found');
	header('Location: /');
}


?>