<?php
class Mediaelement_Kwc_AudioPlayer_Update_20170321PrepareAudioTable extends Kwf_Update
{
    public function update()
    {
        $db = Kwf_Registry::get('db');
        $tables = $db->listTables();
        if (!in_array('kwc_advanced_audio_player', $tables)) {
            $db->query("CREATE TABLE `kwc_advanced_audio_player` (
                  `component_id` varchar(255) NOT NULL,
                  `mp3_kwf_upload_id` int(11) DEFAULT NULL,
                  PRIMARY KEY (`component_id`),
                  KEY `mp3_kwf_upload_id` (`mp3_kwf_upload_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }

        $fields = array();
        foreach ($db->query("SHOW FIELDS FROM kwc_advanced_audio_player")->fetchAll() as $field) {
            $fields[$field['Field']] = $field;
        }

        if (!isset($fields['auto_play'])) {
            $db->query("ALTER TABLE `kwc_advanced_audio_player` ADD `auto_play` TINYINT NOT NULL;");
        }
        if (!isset($fields['loop'])) {
            $db->query("ALTER TABLE `kwc_advanced_audio_player` ADD `loop` TINYINT NOT NULL;");
        }
        if (!isset($fields['audio_width'])) {
            $db->query("ALTER TABLE `kwc_advanced_audio_player` ADD `audio_width` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
        }
        if (!isset($fields['audio_height'])) {
            $db->query("ALTER TABLE `kwc_advanced_audio_player` ADD `audio_height` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
        }

        if (strtolower(substr($fields['mp3_kwf_upload_id']['Type'], 0, 3)) == 'int') {
            $db->query("ALTER TABLE `kwc_advanced_audio_player` CHANGE `mp3_kwf_upload_id` `mp3_kwf_upload_id` VARBINARY(36) NULL DEFAULT NULL;");
            $db->query("ALTER TABLE `kwc_advanced_audio_player` ADD FOREIGN KEY (`mp3_kwf_upload_id`) REFERENCES `kwf_uploads`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;");
        }
    }
}
