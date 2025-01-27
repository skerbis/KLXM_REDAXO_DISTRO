<?php
/**
 * @author Joachim Doerr
 * @package redaxo5
 * @license MIT
 */

namespace FriendsOfRedaxo\MForm\Inputs;

use FriendsOfRedaxo\MForm;
use FriendsOfRedaxo\MForm\Utils\MFormModuleHelper;

abstract class MFormInputsAbstract
{
    protected array $config = [];
    protected MForm $mform;

    public function __construct(MForm $mform, array $inputsConfig = [])
    {
        $this->mform = $mform;
        if (!is_null($inputsConfig)) {
            self::mergeConfig($inputsConfig);
        }
    }

    protected function getContentFrom(int|string|null $id = null)
    {
        if (!empty($this->config['contentMForm']) && $this->config['contentMForm'] instanceof MForm) {
            return $this->config['contentMForm']->setShowWrapper(false);
        } else {
            return MForm::factory()->setShowWrapper(false)
                ->addInputs($id, 'bootstrap/card', $this->config);
        }
    }

    protected function getConfigForm(int|string|null $id = null)
    {
        $configForm = MForm::factory()->setShowWrapper(false);
        if (!isset($this->config['configKeys']) || !is_array($this->config['configKeys'])) return $configForm;
        $keys = $this->config['configKeys'];
        if (in_array('bgImg', $keys) && isset($this->config['bgImg']) && $this->config['bgImg'] === true) {
            $configForm->addMediaField($id.'bgImg', ['preview' => 1], null, ['label' => $this->config['bgImgLabel']]);
            $keys = array_diff($keys, ['bgImg']);
        }
        if (in_array('bgColor', $keys) && isset($this->config['bgColor']) && is_array($this->config['bgColor']) && count($this->config['bgColor']) > 0) {
            $configForm->addSelectField($id.'bgColor', $this->config['bgColor'], ['label' => $this->config['bgColorLabel']], 1, $this->config['bgColorDefaultValue']);
            $keys = array_diff($keys, ['bgColor']);
        }
        foreach ($keys as $key) {
            if (!empty($this->config[$key]) && is_array($this->config[$key])) {
                $configForm->addRadioImgField($id . $key, $this->config[$key], ['label' => $this->config[$key . 'Label']], $this->config[$key . 'DefaultValue']);
            }
        }
        return $configForm;
    }

    protected function mergeConfig(array $inputsConfig = []): void
    {
        $this->config = MFormModuleHelper::mergeInputConfig($this->config, $inputsConfig);
    }
}