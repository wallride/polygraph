<?php
	final class SequenceUtils
	{

		/**
		 * @static
		 * @return SequenceUtils
		 */
		public static function create() {
			return new self();
		}

		/**
		 * Вызывает функцию plpgsql, которая увеличивает счетчик и возвращает новое значение
		 * @param Company $company
		 * @param SequenceType $type
		 * @return int
		 */
		public function getCounter($company, SequenceType $type)
		{
			$db = DBPool::me()->getLink();
			if (null == $company)
			{
				$result = $db->queryRaw("SELECT * FROM update_sequence_counter_nullcompany('".$type->getId()."')");
			}
			else
			{
				$result = $db->queryRaw("SELECT * FROM update_sequence_counter('".$company->getId()."', '".$type->getId()."')");
			}

			if (pg_num_rows($result) != 1)
			{
				return false;
			}
			while ($row = pg_fetch_row($result))
			{
			   return $row[0];
			}
		}

	}

?>
