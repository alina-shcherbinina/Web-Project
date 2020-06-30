<div class="task-list">
	<div class="task-list__heading">
		List of tasks
	</div>	

	<div class="task-list__toolbar">

		<div class="tool-item">
			<form method="GET" action="/">
				<div  class="form-group">
					<select class="form-group__control" name="status" onchange="this.form.submit()">
						<option value="all" <?= array_get($_GET, 'status') === 'all' ? 'selected' : '' ?>>All tasks</option>
						<option value="pending" <?= array_get($_GET, 'status') === 'pending' ? 'selected' : '' ?>>Current tasks</option>
						<option value="failed" <?= array_get($_GET, 'status') === 'failed' ? 'selected' : '' ?>>Overdue tasks</option>
						<option value="done" <?= array_get($_GET, 'status') === 'done' ? 'selected' : '' ?>>Completed tasks</option>
					</select>	
				</div>
			</form>
		</div>

		<div class="tool-item">
			<form method="GET" action="">
				<div class="form-group">
					<input type="date" name="date" value="<?= array_get($_GET, 'date') ?>" class="form-group__control" onchange="this.form.submit()">
				</div>
			</form>
		</div>

		<div class="tool-item">	
			<div class="tool-list">
				<div class="tool-list__item"> <a href="/?<?= array_get($_GET, 'status') ? 'status='.array_get($_GET, 'status').'&' : '' ?>date=today">today</a> </div>
				<div class="tool-list__item"> <a href="/?<?= array_get($_GET, 'status') ? 'status='.array_get($_GET, 'status').'&' : '' ?>date=tomorrow">tomorrow</a> </div>
				<div class="tool-list__item"> <a href="/?<?= array_get($_GET, 'status') ? 'status='.array_get($_GET, 'status').'&' : '' ?>date=thisweek">this week</a> </div>
				<div class="tool-list__item"> <a href="/?<?= array_get($_GET, 'status') ? 'status='.array_get($_GET, 'status').'&' : '' ?>date=nextweek">next week</a> </div>
			</div>
		</div>

	</div>
	<div class="task-list__table">
		<table class="table">
			<thead>
				<tr>
					<th>Status</th>
					<th>Type</th>
					<th>Task</th>
					<th>Place</th>
					<th>Date and time</th>
					<th>Comment</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($tasks as $task): ?>
				<tr>
					<td>
						<form method="post" action="/complete.php">
							<input type="hidden" name="id" value="<?= $task->id ?>">
							<input type="checkbox" name="checked" <?= $task->isDone() ? 'checked' : '' ?> onchange="this.form.submit()">
						</form>
					</td>
					<td><?= $task->type ?></td>
					<td><a href="/?task_id=<?= $task->id?>"><?= $task->subject ?></a></td>
					<td><?= $task->place ?></td>
					<td <?= $task->isFailed() ? 'failed' : '' ?>><?= $task->datetime ?></td>
					<td><?= $task->comment?></td>
				</tr>
				<?php endforeach; ?> 
			</tbody>
		</table>
	</div>
</div>