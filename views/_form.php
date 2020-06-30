<div class="add-task-form">
	<div class="add-task-form__heading">
		<?php if($task->isNew()):  ?>
		Edit a task
		<?php else: ?>
		New task
		<?php endif; ?>
	</div>

	<form method="POST" action="">
		<?php if($task->isNew()): ?>
			<input type="hidden" name="id" value="<?= $task->id ?>">
		<?php endif; ?>
		<div class="form-group">	
			<label class="form-group__label">Theme</label>
			<input class="form-group__control" type="textbox" name="subject" value="<?= htmlspecialchars($task->subject)?>">
                <span class="error"><?= $task->getError('subject') ?></span>
		</div>

		<div class="form-group">	
			<label class="form-group__label">Type</label>
			<select class="form-group__control" name="type">
				<?php foreach ($task::getTypes() as $type => $typeName): ?>
				<option value="<?= $type ?>" <?= $task->$type === $type ? 'selected' : '' ?>><?= htmlspecialchars($typeName) ?></option>  
				<?php endforeach; ?>
			</select>
		</div>

		<div class="form-group">	
			<label class="form-group__label">Place</label>
			<input class="form-group__control" type="textbox" name="place" value="<?= htmlspecialchars($task->place)?>">
                <span class="error"><?= $task->getError('place') ?></span>
		</div>

		<div class="form-group">	
			<label class="form-group__label">Date and Time</label>
			<input class="form-group__control" type="datetime-local" name="datetime" value="<?= formatLocalTime($task->datetime)?>">
                <span class="error"><?= $task->getError('datetime') ?></span>
		</div>

		<div class="form-group">	
			<label class="form-group__label">Duraion</label>
			<select class="form-group__control" name="period">
				<?php foreach ($task::getPeriods() as $period): ?>
				<option <?= $task->$period === $period ? 'selected' : '' ?>><?= htmlspecialchars($period) ?></option>  
				<?php endforeach; ?>
			</select>
		</div>

		<div class="form-group">	
			<label class="form-group__label">Comment</label>
			<textarea class="form-group__control" name="comment"><?= htmlspecialchars($task->comment)?></textarea>
                <span class="error"><?= $task->getError('comment') ?></span>
		</div>
		<div class="form-group">
				<?php if($task->isNew()): ?>
			<button type="submit">
				Save
			</button>
			<a href="/">
				Cancel
			</a>	
				<?php else: ?>
			<button type="submit">
				Add
			</button>
				<?php endif; ?>
		</div>
	</form>
</div>