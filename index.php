<?php

include 'autoload.php';

use app\Task;

$task = new Task;
if (getenv('REQUEST_METHOD') === 'POST')
{

	$task->fill($_POST);
	
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
	if (isset($_GET['task_id'])) {
		$id = $_GET['task_id'];
		$task = Task::getById($id);
	}
}

$conditions = [];

if (isset($_GET['date'])) {
	switch ($_GET['date']) {
		case 'today':
		case 'tomorrow':
		case 'thisweek':
		case 'nextweek':
			$conditions['date'] = $_GET['date'];
			break;
		default:
			if (!empty($_GET['date']))
			{
				$conditions['date'] = strtotime($_GET['date']);
			}
			break;
	}
}


if (isset($_GET['status'])) {
	switch ($_GET['status']) {
		case 'all':
			$tasks=Task::getAll($conditions);
			break;
		
		case 'pending':
			$tasks=Task::getAllPending($conditions);
			break;

		case 'done':
			$tasks=Task::getAllDone($conditions);
			break;

		case 'failed':
			$tasks=Task::getAllFailed($conditions);
			break;
	}
}
else
{
	$tasks=Task::getAll($conditions);

}


include 'views/layout.php';

?>