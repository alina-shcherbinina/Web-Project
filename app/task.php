<?php 

namespace app;

use app\database;

/**
 * class for tasks form in tunder.calendar oh man ... tired i want to sleep ... ive put database in other class yay
 */
class Task 
{
	const TYPE_MEETING='meeting';
	const TYPE_CALL='call';
	const TYPE_CONFERENCE='conference';
	const TYPE_TASK='task';

	const STATUS_PENDING = 'pending';
	const STATUS_DONE = 'done';

	public $id;
	public $status = 'pending';
	public $type;
	public $subject;
	public $place;
	public $period;
	public $comment;

	protected $errors;

	public static function getTypes()
	{
		return [static::TYPE_MEETING => 'meeting',
				static::TYPE_CALL=>'call',
				static::TYPE_CONFERENCE=>'conference',
				static::TYPE_TASK=>'task',
			];
	}

	public static function getPeriods()
	{
		
		return ['15 minutes',
				'30 minutes',
				'1 hour',
				'2 hours',
				'3 hours',
				'4 hours',
			];
	}


	protected static function getPdo()
    {
        return database::getPdo();
    }

    protected static function getWhereFromConditions(array $conditions = null)
    {
		$where= '';

		if ($conditions) {
			foreach ($conditions as $key => $value) {
				switch ($key) {
					case 'date':
						switch ($value) {
							case 'today':
								$where = 'DATE(`datetime`) = CURDATE()';
								break;
							case 'tomorrow':
								$where = 'DATE(`datetime`) = CURDATE() + INTERVAL 1 DAY';
								break;
							case 'thisweek':
								$where = 'WEEK(`datetime`) = WEEK(CURDATE())';
								break;
							case 'nextweek':
								$where = 'WEEK(`datetime`) = WEEK(CURDATE() + INTERVAL 1 WEEK)';
								break;
							default:
								$where = 'DATE(`datetime`) = "' . date('Y-m-d', $value) . '"';
								break;


						}
						break;
				}
			}
		}
						// print_r($conditions);

		return $where;
    }

	public static function getAll(array $conditions = null)
	{
		$where = static::getWhereFromConditions($conditions);

		$sql = database::getPdo()->prepare('select * from `tasks` ' . ($where ? 'where ' . $where : '') . ' order by `datetime` desc;');
		$sql->execute();
		// print_r('select * from `tasks` ' . ($where ? 'where ' . $where : '') . ' order by `datetime` desc;'); 

		$tasks = [];

		while ($task = $sql->fetchObject(Task::class)) { 
			$tasks[] = $task;
		}

		return $tasks;
	}

	public static function getAllPending(array $conditions = null)
	{
		$where = static::getWhereFromConditions($conditions);

		$sql = database::getPdo()->prepare('select * from `tasks` where `status` = :status ' . ($where ? 'and ' . $where : '') . ' order by `datetime` desc;');
		$sql->execute([
			'status' => static::STATUS_PENDING,
		]);

		$tasks = [];

		while ($task = $sql->fetchObject(Task::class)) {
			$tasks[] = $task;
		}

		return $tasks;
	}


	public static function getAllDone(array $conditions = null)
	{
		$where = static::getWhereFromConditions($conditions);
		
		$sql = database::getPdo()->prepare('select * from `tasks` where `status` = :status ' . ($where ? 'and ' . $where : '') . ' order by `datetime` desc;');
		$sql->execute([
			'status' => static::STATUS_DONE,
		]);

		$tasks = [];

		while ($task = $sql->fetchObject(Task::class)) {
			$tasks[] = $task;
		}

		return $tasks;
	}

	public static function getAllFailed(array $conditions = null)
	{
		$where = static::getWhereFromConditions($conditions);
		
		$sql = database::getPdo()->prepare('select * from `tasks` where `status` = "pending" and `datetime` < now() ' . ($where ? 'and ' . $where : '') . ' order by `datetime` desc;');
		$sql->execute();

		$tasks = [];

		while ($task = $sql->fetchObject(Task::class)) {
			$tasks[] = $task;
		}

		return $tasks;
	}

	public static function getById($id)
	{
		$sql = database::getPdo()->prepare('select * from `tasks` where `id` = :id limit 1;');
		$sql->execute([
			'id' => $id,
		]);

		$task = $sql->fetchObject(Task::class);

		return $task;
	}

	public function fill(array $values)
	{
		foreach ($values as $key => $value)
		{
			$this->$key = $value;	
		}
	}

	public function save()
	{
		if ($this->validate())
		{

			$data=[	
			'type' => $this->type,
			'status' => $this->status,
			'subject' => $this->subject,
			'place' => $this->place,
			'datetime' => $this->datetime,
			'period' => $this->period,
			'comment' => $this->comment,];


			if($this->id)
			{
				$data['id'] = $this->id;
				$sql = database::getPdo()->prepare('update `tasks` set 
					`type` = :type,
					`status` = :status,
					`subject` = :subject,
					`place` = :place,
					`datetime` = :datetime,
					`period` = :period,
					`comment` = :comment
					where `id` = :id limit 1;');
			}
			else
			{
				$sql = database::getPdo()->prepare('insert into `tasks` set 
					`type` = :type,
					`status` = :status,
					`subject` = :subject,
					`place` = :place,
					`datetime` = :datetime,
					`period` = :period,
					`comment` = :comment;');
			}

			$sql->execute($data);

			return true;
			}
		return false;	
		}

	public function validate()
	{
		$errors=[];

		if (empty($this->subject))
			$errors['subject'] = 'Theme is required!';
		else if (strlen($this->subject) < 3)
			$errors['subject'] = 'Theme must be at least 3 symbols or more';
		else if (strlen($this->subject) > 100)
			$errors['subject'] = 'Theme must be less than 100 characters';

		if (empty($this->place))
			$errors['place'] = 'Place is required!';
		else if (strlen($this->place) < 3)
			$errors['place'] = 'Place must be at least 3 symbols or more';
		else if (strlen($this->place) > 100)
			$errors['place'] = 'Place must be less than 100 characters';

		if (empty($this->datetime))
			$errors['datetime'] = 'Date and time is required!';

		if (strlen($this->comment) > 100)
			$errors['comment'] = 'Comment must be less than 100 characters';

		$this->errors = $errors;

		return !$this->errors;
	}

	public function getError($field_name)
    {
        return array_get($this->errors, $field_name);
    }

	public function getErrors()
	{
		return $this->errors;
	}


	public function isNew() 
	{
		return (bool)$this->id;
	}

	public function isDone()
	{
		return $this->status === static::STATUS_DONE;
	}

	public function isFailed()
	{
		$time = strtotime($this->datetime);

		return $this->status === static::STATUS_PENDING && $time < time();
	}

}

?>