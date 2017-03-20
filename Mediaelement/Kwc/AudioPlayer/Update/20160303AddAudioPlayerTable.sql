CREATE TABLE IF NOT EXISTS `kwc_advanced_audio_player` (
  `component_id` varchar(255) NOT NULL,
  `mp3_kwf_upload_id` int(11) DEFAULT NULL,
  `audio_width` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `audio_height` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `auto_play` TINYINT NOT NULL,
  PRIMARY KEY (`component_id`),
  KEY `mp3_kwf_upload_id` (`mp3_kwf_upload_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
