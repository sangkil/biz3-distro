<?php

namespace common\classes;

use Yii;
use yii\base\Object;
use yii\caching\Cache;
use yii\di\Instance;
use yii\base\InvalidCallException;

/**
 * Description of State
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class State extends Object
{
    private $_key;
    private $_states;

    /**
     * @var Cache
     */
    public $cache = 'cache';
    public $cookieKey = 'dee_state_id';

    protected function buildKey()
    {
        if ($this->_key === null) {
            if (Yii::$app instanceof \yii\web\Application) {
                $key = md5(microtime(true) . mt_rand(0, 1000000));
                $id = Yii::$app->request->cookies->getValue($this->cookieKey, $key);

                $cookie = new Cookie([
                    'name' => $this->cookieKey,
                    'value' => $id,
                    'expire' => time() + 30 * 24 * 3600,
                ]);
                Yii::$app->response->cookies->add($cookie);
            } else {
                $id = md5(Yii::$app->basePath);
            }
            $this->_states['id'] = $id;
            $this->_key = [__CLASS__, Yii::$app->id, $id];
        }
        return $this->_key;
    }

    protected function initState()
    {
        if ($this->_states === null) {
            $this->cache = Instance::ensure($this->cache, Cache::className());
            $this->_states = $this->cache->get($this->buildKey());
            if ($this->_states === false) {
                $this->_states = [];
            }
        }
    }

    public function get($name)
    {
        $this->initState();
        return array_key_exists($name, $this->_states) ? $this->_states[$name] : null;
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function set($name, $value)
    {
        if ($name == 'id') {
            throw new InvalidCallException('Setting read-only property: ' . get_class($this) . '::' . $name);
        }
        $this->initState();
        $this->_states[$name] = $value;
        $this->cache->set($this->buildKey(), $this->_states);
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function __isset($name)
    {
        $this->initState();
        return isset($this->_states[$name]);
    }

    public function states($states = null)
    {
        $this->initState();
        if ($states === null) {
            return $this->_states;
        } else {
            unset($states['id']);
            foreach ($states as $key => $value) {
                $this->_states[$key] = $value;
            }
            $this->cache->set($this->buildKey(), $this->_states);
        }
    }
}
