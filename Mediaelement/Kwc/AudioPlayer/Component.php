<?php
class Mediaelement_Kwc_AudioPlayer_Component extends Kwc_Abstract_Composite_Component
    implements Kwf_Media_Output_IsValidInterface
{
    public static function getSettings($param = null)
    {
        $ret = parent::getSettings($param);
        $ret = array_merge($ret, array(
            'ownModel'     => 'Mediaelement_Kwc_AudioPlayer_Model',
            'componentName' => trlKwfStatic('Audio'),
            'componentCategory' => 'media',
            'componentIcon' => 'sound',
            'extConfig' => 'Kwf_Component_Abstract_ExtConfig_Form'
        ));
        $ret['assetsAdmin']['dep'][] = 'KwfFormFile';

        $ret['audio'] = array(
            'audioWidth' => '100%',
            'audioHeight' => 40,
            'startVolume' => 0.8,
            'loop' => false,
            'enableAutosize' => true,
            'features' => array(
                'playpause','progress','current','duration','tracks','volume','fullscreen'
            ),
            'alwaysShowControls' => false,
            'iPadUseNativeControls' => false,
            'iPhoneUseNativeControls' => false,
            'AndroidUseNativeControls' => false,
            'alwaysShowHours' => false,
            'showTimecodeFrameCount' => false,
            'framesPerSecond' => 25,
            'enableKeyboard' => true,
            'pauseOtherPlayers' => true,
            'keyActions' => array(),
            'autoPlay' => false
        );
        return $ret;
    }

    public function getTemplateVars(Kwf_Component_Renderer_Abstract $renderer)
    {
        $ret = parent::getTemplateVars($renderer);
        $ret['sources'] = null;

        //mp3
        $url = Kwf_Media::getUrl($this->getData()->componentClass,
            $this->getData()->componentId, 'mp3', 'audio.mp3');
        $ev = new Kwf_Component_Event_CreateMediaUrl($this->getData()->componentClass, $this->getData(), $url);
        Kwf_Events_Dispatcher::fireEvent($ev);
        $url = $ev->url;

        if ($url) {
            $ret['source'] = array(
                'src' => $url,
                'type' => 'audio/mp3',
                'title' => 'mp3',
            );
        }

        $ret['config'] = Kwc_Abstract::getSetting($this->getData()->componentClass, 'audio');
        $row = $this->getRow();
        if ($row->audio_width) {
            $ret['config']['audioWidth'] = $row->audio_width;
        }
        if ($row->audio_height) {
            $ret['config']['audioHeight'] = $row->audio_height;
        }
        if ($row->auto_play) {
            $ret['config']['autoPlay'] = true;
        }
        if ($row->loop) {
            $ret['config']['loop'] = true;
        }
        return $ret;
    }

    public static function isValidMediaOutput($id, $type, $className)
    {
        return Kwf_Media_Output_IsValidInterface::VALID;
    }

    public static function getMediaOutput($id, $type, $className)
    {
        $component =Kwf_Component_Data_Root::getInstance()->getComponentByDbId($id);
        $row = $component->getComponent()->getRow();
        if ($type == 'mp3') {
            $uploadRow = $row->getParentRow('FileMp3');
            $mimeType = 'audio/mp3';
        }
        if (!$uploadRow) return null;
        return array(
            'file'=>$uploadRow->getFileSource(),
            'mimeType' => $mimeType
        );
    }
}
