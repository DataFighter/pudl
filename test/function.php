<?php

//TIMESTAMP for December 13th, 2015 @ 9:46 AM (UTC)
$db->string()->row('table', ['column'=>pudlFunction::timestamp(1450000000)]);
pudlTest("SELECT * FROM `table` WHERE (`column`=CONVERT_TZ(FROM_UNIXTIME(1450000000), @@session.time_zone, 'UTC')) LIMIT 1");




//Verify TIMESTAMP conversion is working properly! Check pudlFunction for notes if this fails
/*if (is_a($db, 'pudlGalera')) {
	$row = $db->selectRow(['time'=>pudlFunction::timestamp()], false);
	pudlTest( strtotime(reset($row)) === $db->time() );
}*/




$db->string()->row('table', ['column'=>pudl::column('other')]);
pudlTest("SELECT * FROM `table` WHERE (`column`=`other`) LIMIT 1");




$db->string()->row('table', [pudl::column('column', 'value')]);
pudlTest("SELECT * FROM `table` WHERE (`column`='value') LIMIT 1");




$db->string()->row('table', [pudl::column('column1', pudl::column('column2'))]);
pudlTest("SELECT * FROM `table` WHERE (`column1`=`column2`) LIMIT 1");




$db->string()->row('table', [pudl::column('column1', pudl::column('column2')->not())]);
pudlTest("SELECT * FROM `table` WHERE (`column1`!=`column2`) LIMIT 1");




$db->string()->row('table', [pudl::column('column', [1,2,3])]);
pudlTest("SELECT * FROM `table` WHERE (`column` IN (1, 2, 3)) LIMIT 1");




$db->string()->row('table', [pudl::column('column', false)]);
pudlTest("SELECT * FROM `table` WHERE (`column`=FALSE) LIMIT 1");




$db->string()->row('table', [pudl::column('column', true)]);
pudlTest("SELECT * FROM `table` WHERE (`column`=TRUE) LIMIT 1");

