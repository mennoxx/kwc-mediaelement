<?php
class Mediaelement_Kwc_VideoPlayer_Update_20170321PrepareVideoTable extends Kwf_Update
{
    public function update()
    {
        $db = Kwf_Registry::get('db');
        $tables = $db->listTables();
        if (!in_array('kwc_advanced_video_player', $tables)) {
            $db->query("CREATE TABLE IF NOT EXISTS `kwc_advanced_video_player` (
                  `component_id` varchar(255) NOT NULL,
                  `mp4_kwf_upload_id` int(11) DEFAULT NULL,
                  `ogg_kwf_upload_id` int(11) DEFAULT NULL,
                  `webm_kwf_upload_id` int(11) DEFAULT NULL,
                  PRIMARY KEY (`component_id`),
                  KEY `webm_kwf_upload_id` (`webm_kwf_upload_id`),
                  KEY `ogg_kwf_upload_id` (`ogg_kwf_upload_id`),
                  KEY `mp4_kwf_upload_id` (`mp4_kwf_upload_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }

        $fields = array();
        foreach ($db->query("SHOW FIELDS FROM kwc_advanced_video_player")->fetchAll() as $field) {
            $fields[$field['Field']] = $field;
        }

        if (!isset($fields['auto_play'])) {
            $db->query("ALTER TABLE `kwc_advanced_video_player` ADD `auto_play` TINYINT NOT NULL;");
        }
        if (!isset($fields['loop'])) {
            $db->query("ALTER TABLE `kwc_advanced_video_player` ADD `loop` TINYINT NOT NULL;");
        }
        if (!isset($fields['video_width'])) {
            $db->query("ALTER TABLE `kwc_advanced_video_player` ADD `video_width` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `webm_kwf_upload_id`;");
        }
        if (!isset($fields['video_height'])) {
            $db->query("ALTER TABLE `kwc_advanced_video_player` ADD `video_height` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `video_width`;");
        }
        if (!isset($fields['source_type'])) {
            $db->query("ALTER TABLE `kwc_advanced_video_player` ADD `source_type` VARCHAR( 255 ) NOT NULL AFTER `component_id`;");
            $db->query("UPDATE kwc_advanced_video_player SET source_type = 'files';");
        }
        if (!isset($fields['mp4_url'])) {
            $db->query("ALTER TABLE `kwc_advanced_video_player` ADD `mp4_url` TEXT NOT NULL AFTER `webm_kwf_upload_id`;");
        }
        if (!isset($fields['ogg_url'])) {
            $db->query("ALTER TABLE `kwc_advanced_video_player` ADD `ogg_url` TEXT NOT NULL AFTER `mp4_url`;");
        }
        if (!isset($fields['webm_url'])) {
            $db->query("ALTER TABLE `kwc_advanced_video_player` ADD `webm_url` TEXT NOT NULL AFTER `ogg_url`;");
        }
        if (!isset($fields['format'])) {
            $db->query("ALTER TABLE `kwc_advanced_video_player` ADD `format` ENUM(  '',  '16x9',  '4x3' ) NOT NULL;");
        }
        if (!isset($fields['size'])) {
            $db->query("ALTER TABLE `kwc_advanced_video_player` ADD `size` ENUM(  'contentWidth',  'userDefined' ) NOT NULL AFTER  `webm_kwf_upload_id`;");
            $db->query("UPDATE  `kwc_advanced_video_player` SET  `size` =  IF (`video_width` =  '100%' OR `video_height` = '100%', 'contentWidth', 'userDefined'),
                `video_width` =  IF (`video_width` =  '100%' OR `video_height` = '100%', '', video_width),
                `video_height` =  IF (`video_width` =  '100%' OR `video_height` = '100%', '', video_height)");
        }
        if (strtolower(substr($fields['video_width']['Type'], 0, 3)) == 'int') {
            $db->query("ALTER TABLE  `kwc_advanced_video_player` CHANGE  `video_width`  `video_width` INT( 11 ) NOT NULL;");
        }
        if (strtolower(substr($fields['video_height']['Type'], 0, 3)) == 'int') {
            $db->query("ALTER TABLE  `kwc_advanced_video_player` CHANGE  `video_height`  `video_height` INT( 11 ) NOT NULL;");
        }
        if (strtolower(substr($fields['mp4_kwf_upload_id']['Type'], 0, 3)) == 'int') {
            $db->query("ALTER TABLE `kwc_advanced_video_player` CHANGE `mp4_kwf_upload_id` `mp4_kwf_upload_id` VARBINARY(36) NULL DEFAULT NULL;");
            $db->query("ALTER TABLE `kwc_advanced_video_player` ADD FOREIGN KEY (`mp4_kwf_upload_id`) REFERENCES `kwf_uploads`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;");
        }
        if (strtolower(substr($fields['ogg_kwf_upload_id']['Type'], 0, 3)) == 'int') {
            $db->query("ALTER TABLE `kwc_advanced_video_player` CHANGE `ogg_kwf_upload_id` `ogg_kwf_upload_id` VARBINARY(36) NULL DEFAULT NULL;");
            $db->query("ALTER TABLE `kwc_advanced_video_player` ADD FOREIGN KEY (`ogg_kwf_upload_id`) REFERENCES `kwf_uploads`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;");
        }
        if (strtolower(substr($fields['webm_kwf_upload_id']['Type'], 0, 3)) == 'int') {
            $db->query("ALTER TABLE `kwc_advanced_video_player` CHANGE `webm_kwf_upload_id` `webm_kwf_upload_id` VARBINARY(36) NULL DEFAULT NULL;");
            $db->query("ALTER TABLE `kwc_advanced_video_player` ADD FOREIGN KEY (`webm_kwf_upload_id`) REFERENCES `kwf_uploads`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;");
        }
    }
}
