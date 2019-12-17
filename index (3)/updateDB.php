<?php
	// check this file's MD5 to make sure it wasn't called before
	$prevMD5=@implode('', @file(dirname(__FILE__).'/setup.md5'));
	$thisMD5=md5(@implode('', @file("./updateDB.php")));
	if($thisMD5==$prevMD5) {
		$setupAlreadyRun=true;
	}else{
		// set up tables
		if(!isset($silent)) {
			$silent=true;
		}

		// set up tables
		setupTable('Teachers_Table', "create table if not exists `Teachers_Table` (   `Name` INT unsigned not null auto_increment , primary key (`Name`), `Email_id` VARCHAR(40) null , `Ph_number` INT unsigned null ) CHARSET utf8", $silent, array( "ALTER TABLE Teachers_Table ADD `field1` VARCHAR(40)","ALTER TABLE Teachers_Table ADD `field2` VARCHAR(40)","ALTER TABLE `Teachers_Table` CHANGE `field1` `Name` VARCHAR(40) null ","ALTER TABLE Teachers_Table ADD `field3` VARCHAR(40)","ALTER TABLE `Teachers_Table` CHANGE `field3` `Ph_number` VARCHAR(40) null ","ALTER TABLE `Teachers_Table` CHANGE `field2` `Email_id` VARCHAR(40) null ","ALTER TABLE `Teachers_Table` CHANGE `Name` `Name` VARCHAR(40) not null "," ALTER TABLE `Teachers_Table` CHANGE `Name` `Name` INT not null "," ALTER TABLE `Teachers_Table` CHANGE `Name` `Name` INT not null auto_increment "," ALTER TABLE `Teachers_Table` CHANGE `Name` `Name` INT unsigned not null auto_increment ","ALTER TABLE `Teachers_Table` CHANGE `Ph_number` `Ph_number` VARCHAR(40) not null ","ALTER TABLE `Teachers_Table` ADD INDEX (`Ph_number`)","ALTER TABLE `Teachers_Table` DROP PRIMARY KEY"," ALTER TABLE `Teachers_Table` CHANGE `Ph_number` `Ph_number` INT null ","ALTER TABLE `Teachers_Table` CHANGE `Ph_number` `Ph_number` INT not null "," ALTER TABLE `Teachers_Table` CHANGE `Ph_number` `Ph_number` INT not null auto_increment "," ALTER TABLE `Teachers_Table` CHANGE `Ph_number` `Ph_number` INT unsigned not null auto_increment ","ALTER TABLE `Teachers_Table` ADD INDEX (`Ph_number`)","ALTER TABLE `Teachers_Table` DROP PRIMARY KEY"," ALTER TABLE `Teachers_Table` CHANGE `Ph_number` `Ph_number` INT unsigned null ","ALTER TABLE `Teachers_Table` ADD PRIMARY KEY (`Name`)"));
		setupIndexes('Teachers_Table', array('Ph_number'));
		setupTable('Students_Table', "create table if not exists `Students_Table` (   `Name` INT unsigned not null auto_increment , primary key (`Name`), `Email_id` VARCHAR(40) null , `ph_number` INT unsigned null ) CHARSET utf8", $silent, array( "ALTER TABLE Students_Table ADD `field1` VARCHAR(40)","ALTER TABLE Students_Table ADD `field2` VARCHAR(40)","ALTER TABLE `Students_Table` CHANGE `field1` `Name` VARCHAR(40) null ","ALTER TABLE Students_Table ADD `field3` VARCHAR(40)","ALTER TABLE `Students_Table` CHANGE `field2` `Email_id` VARCHAR(40) null ","ALTER TABLE Students_Table ADD `field4` VARCHAR(40)","ALTER TABLE `Students_Table` CHANGE `field3` `ph_number` VARCHAR(40) null ","ALTER TABLE `Students_Table` DROP `field4`","ALTER TABLE `Students_Table` CHANGE `Name` `Name` VARCHAR(40) not null "," ALTER TABLE `Students_Table` CHANGE `Name` `Name` INT not null "," ALTER TABLE `Students_Table` CHANGE `Name` `Name` INT unsigned not null "," ALTER TABLE `Students_Table` CHANGE `Name` `Name` INT unsigned not null auto_increment "," ALTER TABLE `Students_Table` CHANGE `ph_number` `ph_number` INT null ","ALTER TABLE `Students_Table` ADD PRIMARY KEY (`Name`)"));
		setupIndexes('Students_Table', array('ph_number'));


		// save MD5
		if($fp=@fopen(dirname(__FILE__).'/setup.md5', 'w')) {
			fwrite($fp, $thisMD5);
			fclose($fp);
		}
	}


	function setupIndexes($tableName, $arrFields) {
		if(!is_array($arrFields)) {
			return false;
		}

		foreach($arrFields as $fieldName) {
			if(!$res=@db_query("SHOW COLUMNS FROM `$tableName` like '$fieldName'")) {
				continue;
			}
			if(!$row=@db_fetch_assoc($res)) {
				continue;
			}
			if($row['Key']=='') {
				@db_query("ALTER TABLE `$tableName` ADD INDEX `$fieldName` (`$fieldName`)");
			}
		}
	}


	function setupTable($tableName, $createSQL='', $silent=true, $arrAlter='') {
		global $Translation;
		ob_start();

		echo '<div style="padding: 5px; border-bottom:solid 1px silver; font-family: verdana, arial; font-size: 10px;">';

		// is there a table rename query?
		if(is_array($arrAlter)) {
			$matches=array();
			if(preg_match("/ALTER TABLE `(.*)` RENAME `$tableName`/", $arrAlter[0], $matches)) {
				$oldTableName=$matches[1];
			}
		}

		if($res=@db_query("select count(1) from `$tableName`")) { // table already exists
			if($row = @db_fetch_array($res)) {
				echo str_replace("<TableName>", $tableName, str_replace("<NumRecords>", $row[0],$Translation["table exists"]));
				if(is_array($arrAlter)) {
					echo '<br>';
					foreach($arrAlter as $alter) {
						if($alter!='') {
							echo "$alter ... ";
							if(!@db_query($alter)) {
								echo '<span class="label label-danger">' . $Translation['failed'] . '</span>';
								echo '<div class="text-danger">' . $Translation['mysql said'] . ' ' . db_error(db_link()) . '</div>';
							}else{
								echo '<span class="label label-success">' . $Translation['ok'] . '</span>';
							}
						}
					}
				}else{
					echo $Translation["table uptodate"];
				}
			}else{
				echo str_replace("<TableName>", $tableName, $Translation["couldnt count"]);
			}
		}else{ // given tableName doesn't exist

			if($oldTableName!='') { // if we have a table rename query
				if($ro=@db_query("select count(1) from `$oldTableName`")) { // if old table exists, rename it.
					$renameQuery=array_shift($arrAlter); // get and remove rename query

					echo "$renameQuery ... ";
					if(!@db_query($renameQuery)) {
						echo '<span class="label label-danger">' . $Translation['failed'] . '</span>';
						echo '<div class="text-danger">' . $Translation['mysql said'] . ' ' . db_error(db_link()) . '</div>';
					}else{
						echo '<span class="label label-success">' . $Translation['ok'] . '</span>';
					}

					if(is_array($arrAlter)) setupTable($tableName, $createSQL, false, $arrAlter); // execute Alter queries on renamed table ...
				}else{ // if old tableName doesn't exist (nor the new one since we're here), then just create the table.
					setupTable($tableName, $createSQL, false); // no Alter queries passed ...
				}
			}else{ // tableName doesn't exist and no rename, so just create the table
				echo str_replace("<TableName>", $tableName, $Translation["creating table"]);
				if(!@db_query($createSQL)) {
					echo '<span class="label label-danger">' . $Translation['failed'] . '</span>';
					echo '<div class="text-danger">' . $Translation['mysql said'] . db_error(db_link()) . '</div>';
				}else{
					echo '<span class="label label-success">' . $Translation['ok'] . '</span>';
				}
			}
		}

		echo "</div>";

		$out=ob_get_contents();
		ob_end_clean();
		if(!$silent) {
			echo $out;
		}
	}
?>